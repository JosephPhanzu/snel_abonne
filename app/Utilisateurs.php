<?php

namespace App;
class Utilisateurs extends Database {

    protected static $table = 'utilisateurs';
    protected static $nom, $prenom, $email, $telephone, $mdp, $role;
    protected static $config;

    public function __construct($nom = null, $prenom = null, $email = null, $telephone = null, $mdp = null, $role = null) {

        self::$nom = $nom;
        self::$prenom = $prenom;
        self::$email = $email;
        self::$telephone = $telephone;
        self::$mdp = $mdp;
        self::$role = $role;

        self::$config = (ConfigDB::getInstance())->getConfig();

        parent::__construct(self::$config);

    }

    public static function getAll() {
        return self::all(self::$table);
    }

    public static function exist (){

        $params = 'email = :email';
        $data = [
            'email' => self::$email
        ];
        return self::findByParams(self::$table, $params, $data);
    }

    public static function existNoData (){

        $params = 'telephone = :telephone';
        $data = [
            'telephone' => self::$telephone
        ];
        return self::findByParamsNoData(self::$table, $params, $data);
    }

    public static function getByCode($code){
        return self::find(self::$table, $code);
    }

    public static function getPaginate($limit, $offset) {
        return self::paginate(self::$table, $limit, $offset);
    }

    public static function add(): bool {

        $data = [
            'nom' => self::$nom,
            'prenom' => self::$prenom,
            'email' => self::$email,
            'telephone' => self::$telephone,
            'mdp' => password_hash(password: self::$mdp, algo: PASSWORD_BCRYPT),
            'role' => self::$role,
            'code' => bin2hex(random_bytes(16)),
            'temps' => time()
        ];

        try {
            return self::insert(self::$table, $data);
        } catch (\Exception $th) {
            die('Erreur lors de l\'insertion utilisateur'. $th->getMessage());
        }
    }

    public static function getAllemploye () {
        try {
            return self::findAllByParams(self::$table, 'role = :role', ['role' => 'employe']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function login($email, $mdp) {

        $user = self::findByParams(self::$table, 'email = :email', ['email' => $email]);

        if ($user) {
            if (password_verify($mdp, $user['mdp'])) :
                return $user;
            endif;
        }
        
        return [];
    }

    public static function deleteOne($code) {
        try {
            return self::delete(self::$table, $code);
        } catch (\Throwable $th) {
            die('Erreur lors de la suppression'. $th->getMessage());
        }
    }
}
