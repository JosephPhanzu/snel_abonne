<?php

namespace App;

class Database {
    protected static $db;
    protected static $bruteForceTableReady = false;
    protected const LOGIN_ATTEMPT_LIMIT = 7;
    protected const LOGIN_ATTEMPT_WINDOW = 900;
    protected const LOGIN_LOCK_DURATION = 900;

    public function __construct($config) {
        try {
            self::$db = new \PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
                $config['username'],
                $config['password']
            );
            self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            self::$db->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
            
        } catch (\PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    // Méthode pour récupérer un enregistrement par code
    protected static function getClientIp(): string {
        $keys = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR',
        ];

        foreach ($keys as $key) {
            if (empty($_SERVER[$key])) {
                continue;
            }

            $value = trim(explode(',', $_SERVER[$key])[0]);
            if (filter_var($value, FILTER_VALIDATE_IP)) {
                return $value;
            }
        }

        return 'unknown';
    }

    protected static function getLoginAttemptKey(string $identifier, string $scope = 'default'): string {
        $identifier = strtolower(trim($identifier));
        return hash('sha256', $scope . '|' . self::getClientIp() . '|' . $identifier);
    }

    protected static function ensureBruteForceTable(): void {
        if (self::$bruteForceTableReady) {
            return;
        }

        $sql = "
            CREATE TABLE IF NOT EXISTS login_attempts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                attempt_key VARCHAR(64) NOT NULL UNIQUE,
                identifier VARCHAR(191) NOT NULL,
                ip_address VARCHAR(45) NOT NULL,
                scope VARCHAR(100) NOT NULL DEFAULT 'default',
                attempt_count INT NOT NULL DEFAULT 0,
                first_attempt_at DATETIME DEFAULT NULL,
                last_attempt_at DATETIME DEFAULT NULL,
                locked_until DATETIME DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_login_attempt_identifier (identifier),
                INDEX idx_login_attempt_scope (scope),
                INDEX idx_login_attempt_locked_until (locked_until)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";

        self::$db->exec($sql);
        self::$bruteForceTableReady = true;
    }

    protected static function cleanupExpiredLoginAttempts(): void {
        // self::ensureBruteForceTable();

        $sql = "
            DELETE FROM login_attempts
            WHERE (locked_until IS NULL OR locked_until < NOW())
              AND last_attempt_at < DATE_SUB(NOW(), INTERVAL " . self::LOGIN_ATTEMPT_WINDOW . " SECOND)
        ";

        self::$db->exec($sql);
    }

    public static function getLoginAttemptStatus(string $identifier, string $scope = 'default'): array {
        self::cleanupExpiredLoginAttempts();

        $key = self::getLoginAttemptKey($identifier, $scope);
        $stmt = self::$db->prepare("
            SELECT attempt_count, locked_until
            FROM login_attempts
            WHERE attempt_key = :attempt_key
            LIMIT 1
        ");
        $stmt->execute(['attempt_key' => $key]);
        $attempt = $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];

        $remainingSeconds = 0;
        if (!empty($attempt['locked_until'])) {
            $remainingSeconds = max(0, strtotime($attempt['locked_until']) - time());
        }

        $attempts = (int) ($attempt['attempt_count'] ?? 0);

        return [
            'attempts' => $attempts,
            'remaining_attempts' => max(0, self::LOGIN_ATTEMPT_LIMIT - $attempts),
            'locked' => $remainingSeconds > 0,
            'remaining_seconds' => $remainingSeconds,
        ];
    }

    public static function recordFailedLoginAttempt(string $identifier, string $scope = 'default'): array {
        self::cleanupExpiredLoginAttempts();

        $key = self::getLoginAttemptKey($identifier, $scope);
        $normalizedIdentifier = strtolower(trim($identifier));
        $ipAddress = self::getClientIp();

        $stmt = self::$db->prepare("
            SELECT attempt_count, last_attempt_at
            FROM login_attempts
            WHERE attempt_key = :attempt_key
            LIMIT 1
        ");
        $stmt->execute(['attempt_key' => $key]);
        $attempt = $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];

        $attemptCount = (int) ($attempt['attempt_count'] ?? 0);
        if (!empty($attempt['last_attempt_at'])) {
            $lastAttemptTs = strtotime($attempt['last_attempt_at']);
            if ((time() - $lastAttemptTs) > self::LOGIN_ATTEMPT_WINDOW) {
                $attemptCount = 0;
            }
        }

        $attemptCount++;
        $lockedUntil = $attemptCount >= self::LOGIN_ATTEMPT_LIMIT
            ? date('Y-m-d H:i:s', time() + self::LOGIN_LOCK_DURATION)
            : null;

        $upsert = self::$db->prepare("
            INSERT INTO login_attempts (
                attempt_key, identifier, ip_address, scope, attempt_count, first_attempt_at, last_attempt_at, locked_until
            ) VALUES (
                :attempt_key, :identifier, :ip_address, :scope, :attempt_count, NOW(), NOW(), :locked_until
            )
            ON DUPLICATE KEY UPDATE
                identifier = VALUES(identifier),
                ip_address = VALUES(ip_address),
                scope = VALUES(scope),
                attempt_count = VALUES(attempt_count),
                last_attempt_at = NOW(),
                locked_until = VALUES(locked_until)
        ");
        $upsert->execute([
            'attempt_key' => $key,
            'identifier' => $normalizedIdentifier,
            'ip_address' => $ipAddress,
            'scope' => $scope,
            'attempt_count' => $attemptCount,
            'locked_until' => $lockedUntil,
        ]);

        return self::getLoginAttemptStatus($normalizedIdentifier, $scope);
    }

    public static function clearLoginAttempt(string $identifier, string $scope = 'default'): void {
        self::cleanupExpiredLoginAttempts();

        $key = self::getLoginAttemptKey($identifier, $scope);
        $stmt = self::$db->prepare("DELETE FROM login_attempts WHERE attempt_key = :attempt_key");
        $stmt->execute(['attempt_key' => $key]);
    }

    public static function formatRemainingLockTime(int $seconds): string {
        $seconds = max(0, $seconds);
        $minutes = (int) floor($seconds / 60);
        $remainingSeconds = $seconds % 60;

        if ($minutes > 0 && $remainingSeconds > 0) {
            return $minutes . ' min ' . $remainingSeconds . ' s';
        }

        if ($minutes > 0) {
            return $minutes . ' min';
        }

        return $remainingSeconds . ' s';
    }

    public static function find($table, $code) {
        $stmt = self::$db->prepare("SELECT * FROM $table WHERE code = :code");
        $stmt->execute(['code' => $code]);

        return $stmt->rowCount() > 0 ? $stmt->fetch(\PDO::FETCH_ASSOC) : [];
    
    }

    public static function findByParamsNoData($table, $params, $data) {
        $stmt = self::$db->prepare("SELECT COUNT(*) FROM $table WHERE $params");
        $stmt->execute($data);
        return $stmt->fetchColumn();
    }

    // Méthode Pour trouver les données par paramètre
    public static function findByParams($table, $params, $data) {
        $stmt = self::$db->prepare("SELECT * FROM $table WHERE $params");
        $stmt->execute($data);
        return $stmt->rowCount() > 0 ? $stmt->fetch(\PDO::FETCH_ASSOC) : [];
    }

    // Méthode Pour trouver les données par paramètre
    public static function findAllByParams($table, $params, $data) {
        $stmt = self::$db->prepare("SELECT * FROM $table WHERE $params");
        $stmt->execute($data);
        return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];
    }

    // Méthode pour récupérer tous les enregistrements
    public static function all($table) {
        $stmt = self::$db->prepare("SELECT * FROM $table");
        return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];
    }
    // Méthode pour récupérer une pagination
    public static function paginate($table, $limit, $offset){
        $query = "SELECT * FROM $table LIMIT :limit OFFSET :offset";
        $stmt = self::$db -> prepare($query);
        $stmt -> bindParam(":limit", $limit, \PDO::PARAM_INT);
        $stmt -> bindParam(":offset", $offset, \PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];
    }

    public static function paginateByParams($table, $params, $data, $limit, $offset){
        $query = "SELECT * FROM $table WHERE $params LIMIT :limit OFFSET :offset";
        $stmt = self::$db -> prepare($query);
        $stmt -> bindValue(":limit", (int)$limit, \PDO::PARAM_INT);
        $stmt -> bindValue(":offset", (int)$offset, \PDO::PARAM_INT);
        $stmt -> execute($data);
        return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];
    }

    //méthode pour la recherche dans la bdd
    public static function search($table, $columns, $keyword, $field = '*') {
        $query = "SELECT $field FROM $table WHERE $columns LIKE :keyword";
        $stmt = self::$db->prepare($query);
        $stmt->execute(['keyword' => '%'.$keyword.'%']);
        return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];
    }

    public static function searchByParams($table, $columns, $keyword, $field, $params) {
        $query = "SELECT $field FROM $table WHERE $columns LIKE :keyword AND $params";
        $stmt = self::$db->prepare($query);
        $stmt->execute(['keyword' => '%'.$keyword.'%']);
        return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];
    }


    // Méthode pour supprimer un enregistrement
    public static function delete($table, $code) {
        $stmt = self::$db->prepare("DELETE FROM $table WHERE code = :code");
        $stmt->execute(['code' => $code]);
        return $stmt->rowCount();
    }

    // Méthode pour insérer un enregistrement
    public static function insert($table, $data) {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $stmt = self::$db->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
        return $stmt->execute($data);
    }

    // Méthode pour mettre à jour un enregistrement par paramettre
    public static function updateByParam($table, $columns, $params, $data) {

        $query = " UPDATE $table SET $columns WHERE $params";
        $stmt = self::$db->prepare($query);

        if ($stmt->execute(array_values($data))) {
            return $stmt;
        }
        return false;
    }

    // Méthode pour mettre à jour un enregistrement
    public static function updateByCode($table, $data, $code) {
        $columns = '';
        foreach ($data as $key => $value) {
            $columns .= "$key = :$key, ";
        }
        $columns = rtrim($columns, ', ');
        $stmt = self::$db->prepare("UPDATE $table SET $columns WHERE id = :id");
        $data['id'] = $code;
        return $stmt->execute($data);
    }

    public static function getJoinTable($table1, $table2, $on, $dataGet ='*', $condition = '', $data = []){
        $query = "
                    SELECT 
                        $dataGet
                    FROM 
                        $table1
                    INNER JOIN 
                        $table2 
                    ON 
                        $on
                ";
                if (!empty($condition)) {
                    $query .= " WHERE $condition";
                }
                $stmt = self::$db->prepare($query);
                $stmt->execute($data);

                return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];
    }

    public static function getJoinTablePaginate($table1, $table2, $on, $dataGet, $order, $limit, $offset){
        $query = "
                    SELECT 
                        $dataGet
                    FROM 
                        $table1
                    INNER JOIN 
                        $table2 
                    ON 
                        $on
                    ORDER BY
                        $order
                    LIMIT :limit OFFSET :offset
                ";
            
                $stmt = self::$db->prepare($query);
                $stmt -> bindParam(":limit", $limit, \PDO::PARAM_INT);
                $stmt -> bindParam(":offset", $offset, \PDO::PARAM_INT);
                $stmt->execute();

                return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];
    }

    // self::$db = null;

}
