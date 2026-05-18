<?php
namespace App;
use App\Pharmacie;

class Permission extends Database {

    protected $table = 'permission';
    private $type, $code_user, $peut_connecter, $peut_gerer;
    private static $config;
    public function __construct($type = null, $code_user = null, $peut_connecter = null, $peut_gerer = null){

        $this->type = $type;
        $this->code_user = $code_user;
        $this->peut_connecter = $peut_connecter;
        $this->peut_gerer = $peut_gerer;

        self::$config = (ConfigDB::getInstance())->getConfig();

        parent::__construct(self::$config);

    }

    public function add(){
        try {
            $data = [
                'type' => $this->type,
                'peut_connecter' => $this->peut_connecter,
                'peut_gerer' => $this->peut_gerer,
                'code_user' => $this->code_user,
            ];
            return self::insert($this->table, $data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getPermission($code){
        try {
            return self::findByParams($this->table, 'code_user = :code_user', ['code_user' => $code]);
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function getPermissionEmploeur($code){
        try {
            $permissions = self::findByParams($this->table, 'code_user = :code_user AND type = :type', ['code_user' => $code, 'type' => 'Employeur']);
            return $permissions['peut_connecter'];
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function getUser_per($code, $type = null){
        try {
            $condition = 'code_user = :code_user';
            $params = ['code_user' => $code];

            if ($type !== null) {
                $condition .= ' AND type = :type';
                $params['type'] = $type;
            }

            $permissions = self::findByParams($this->table, $condition, $params);

            return !empty($permissions) ? $permissions['peut_connecter'] : false;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getPermission_gerer($code, $type = null){
        try {
            $condition = 'code_user = :code_user';
            $params = ['code_user' => $code];

            if ($type !== null) {
                $condition .= ' AND type = :type';
                $params['type'] = $type;
            }

            $permissions = self::findByParams($this->table, $condition, $params);

            return !empty($permissions) ? $permissions['peut_gerer'] : false;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function updatePermission($column, $params, $data){
        try {
            return self::updateByParam($this->table, $column, $params, $data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function sauvegarderActuelle(){

        try {
            $stmt = self::$db->prepare("
                INSERT IGNORE INTO permission (type, peut_connecter, peut_gerer, peut_gerer_pharma, peut_gerer_vendeur, code_user)
                SELECT 'proprietaire', 1, 1, 1, 1, code FROM proprietaire

                INSERT IGNORE INTO permission (type, peut_connecter, peut_gerer, peut_gerer_pharma, peut_gerer_vendeur, code_user)
                SELECT 'pharmacien', 1, 1, 0, 0, code FROM employe

                INSERT IGNORE INTO permission (type, peut_connecter, peut_gerer, peut_gerer_pharma, peut_gerer_vendeur, code_user)
                SELECT 'pharmacie', 1, 1, 1, 1, code FROM pharmacie

            ");
            echo "Permissions initialisées avec succès !";
        } catch (\Throwable $th) {
            echo "Erreur : " . $th->getMessage();
        }
        
    }

}