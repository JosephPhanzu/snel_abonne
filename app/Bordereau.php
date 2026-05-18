<?php

namespace App;

class Bordereau extends Database {
    protected $table = 'bordereaux';
    private $code_employe, $code_agent, $date_demande, $statut, $date_validation;

    private static $config;

    public function __construct($code_employe = null, $code_agent = null, $date_demande = null, $date_validation = null, $statut = null)
    {
        $this->code_employe = $code_employe;
        $this->code_agent = $code_agent;
        $this->date_demande = $date_demande;
        $this->date_validation = $date_validation;
        $this->statut = $statut;

        self::$config = (ConfigDB::getInstance())->getConfig();
        parent::__construct(self::$config);
    }

    public function add() {
        $data = [
            'code_employe' => $this->code_employe,
            'code_agent' => $this->code_agent,
            'date_demande' => $this->date_demande,
            'date_validation' => $this->date_validation,
            'statut' => $this->statut,
            'code' => bin2hex(random_bytes(16))
        ];
        try {
            self::insert($this->table, $data);
            $id = self::$db->lastInsertId();
            return self::findByParams($this->table, 'id = :id', ['id' => $id]);
        } catch (\Exception $th) {
            die('Erreur lors de l\'insertion'. $th->getMessage());
        }
    }

    public function updateInfo($code_pharmacie, $code_employe, $code_agent, $statut, $type) {

        $data = [
            $code_employe,
            $code_agent,
            $statut,
            $type,
            $code_pharmacie
        ];
        try {
            return self::updateByParam($this->table, 'code_employe = ?, code_agent = ?, statut = ?, date_validation = ?', 'code = ?', $data);
        } catch (\Exception $th) {
            die($th->getMessage());
        }
    }

    public function getTotalByEmployeur($code_employeur) {
        $stmt = self::$db->prepare("SELECT COUNT(*) AS total FROM $this->table WHERE code_employeur = :code_employeur");
        $stmt->bindValue('code_employeur', $code_employeur, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;
     }

     public function getByEmployeur($code_employeur) {
        try {
            return self::findByParams($this->table, 'code_employeur = :code_employeur', ['code_employeur' => $code_employeur]);
        } catch (\Throwable $th) {
            die('Erreur lors de getByEmployeur'. $th->getMessage());
        }
     }


    public function getPaginate($limit, $offset) {
        try {
            return self::paginate($this->table, $limit, $offset);
        } catch (\Exception $th) {
            die('Erreur lors de la pagination'. $th->getMessage());
        }
    }

    public function getAll() {
        return self::all($this->table);
    }

    public function exist() {
        try {
            return self::findByParams($this->table, 'code_employe = :code_employe AND date_demande = :proprio', ['code_employe' => $this->code_employe, 'proprio' => $this->date_demande]);
        } catch (\Throwable $th) {
            die('Erreur lors de existSymptome'. $th->getMessage());
        }
    }

    public function getByCode($code) {
        try {
            return self::findByParams($this->table, 'code = :code', ['code' => $code]);
        } catch (\Throwable $th) {
            die('Erreur lors de getByCode'. $th->getMessage());
        }
    }

    public function findOne($code) {
        try {
            return self::find($this->table, $code);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getJoinProprio($date_demande, $limit, $offset) {
        try {
            $sql = 
            "
            	SELECT 
                    p.code_employe AS code_employe_pharmacie,
                    p.statut,
                    p.date_validation,
                    p.code_agent,
                    p.code AS code_pharmacie,
                    pr.code_employe AS code_employe_proprio,
                    MAX(pe.peut_connecter) AS peut_connecter,
                    MAX(pe.peut_vendre) AS peut_vendre
                FROM $this->table p
                JOIN proprietaire pr ON pr.code = p.date_demande
                LEFT JOIN permission pe ON pe.code_user = p.code
                WHERE p.date_demande = :code_proprio
                GROUP BY 
                    p.id, p.code_employe, p.statut, p.date_validation, p.code_agent, p.code, pr.code_employe
                ORDER BY p.id
                LIMIT :limit OFFSET :offset
            ";

            $stmt = self::$db->prepare($sql);
            $stmt->bindValue('code_proprio', $date_demande, \PDO::PARAM_STR);
            $stmt->bindValue('limit', (int)$limit, \PDO::PARAM_INT);
            $stmt->bindValue('offset', (int)$offset, \PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];

        } catch (\Exception $th) {
            die('Erreur lors de getByCode'. $th->getMessage());
        }
    }

    public function getAllJoinProprio($date_demande) {
        try {
            $sql = 
            "
            	SELECT
                    p.code AS code_pharmacie,
                    MAX(pe.peut_connecter) AS peut_connecter,
                    MAX(pe.peut_vendre) AS peut_vendre
                FROM pharmacie p
                LEFT JOIN permission pe ON pe.code_user = p.code
                WHERE p.date_demande = :code_proprio
                GROUP BY p.code
            ";

            $stmt = self::$db->prepare($sql);
            $stmt->bindValue('code_proprio', $date_demande, \PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];

        } catch (\Exception $th) {
            die('Erreur lors de getByCode'. $th->getMessage());
        }
    }


    public function getAllInfoPharmaByProprio($date_demande){
        try {
            $sql = "
                SELECT 
                    p.id,
                    p.code AS code_pharmacie,
                    p.code_employe AS code_employe_pharmacie,
                    p.statut,
                    p.date_validation,
                    p.code_agent,
                    pr.code_employe AS code_employe_proprio,

                    COALESCE(MAX(pe.peut_connecter), 0) AS peut_connecter,
                    COALESCE(MAX(pe.peut_vendre), 0) AS peut_vendre,

                    COALESCE(emp.nb_employes, 0) AS nb_employes,
                    COALESCE(prod.nb_produits, 0) AS nb_produits,
                    COALESCE(fc.chiffre_affaire, 0) AS chiffre_affaire,
                    COALESCE(prod.cout_total_achats, 0) AS cout_total_achats

                FROM pharmacie p
                JOIN proprietaire pr ON pr.code = p.date_demande
                LEFT JOIN permission pe ON pe.code_user = p.code
                LEFT JOIN (
                    SELECT code_pharmacie, COUNT(*) AS nb_employes
                    FROM employe
                    GROUP BY code_pharmacie
                ) emp ON emp.code_pharmacie = p.code
                LEFT JOIN (
                    SELECT code_pharmacie, COUNT(*) AS nb_produits, SUM(prix_achat) AS cout_total_achats
                    FROM produits
                    GROUP BY code_pharmacie
                ) prod ON prod.code_pharmacie = p.code
                LEFT JOIN (
                    SELECT code_pharmacie, SUM(total) AS chiffre_affaire
                    FROM factures
                    GROUP BY code_pharmacie
                ) fc ON fc.code_pharmacie = p.code

                WHERE p.date_demande = :code_proprio

                GROUP BY 
                    p.id,                   -- ← AJOUT
                    p.code,
                    p.code_employe,
                    p.statut,
                    p.date_validation,
                    p.code_agent,
                    pr.code_employe

                ORDER BY p.id ASC;
            ";

            $stmt = self::$db->prepare($sql);
            $stmt->bindParam(':code_proprio', $date_demande, \PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $result ?: [];

        } catch (\Exception $e) {
            error_log("Erreur dans getAllInfoPharmaByProprio : " . $e->getMessage());
            return [];
        }
    }




}