<?php

namespace App;

class GenFunc extends Database
{

    private static $config;
    
    public function __construct() {

        self::$config = (ConfigDB::getInstance())->getConfig();
        parent::__construct(self::$config);
    }



    public function findOne($code, $table) {
        try {
            return self::find($table, $code);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteOne($code, $table) {
        try {
            return self::delete($table, $code);
        } catch (\Throwable $th) {
            die('Erreur lors de la suppression'. $th->getMessage());
        }
    }


    public function updateQte($code, $quantite, $colomn, $params, $table) {

        try {
            return self::updateByParam($table, $colomn.' = :'.$colomn, $params.' = :'.$params, [
                'quantite' => $quantite, 'code' => $code
            ]);
        } catch (\Exception $th) {
            return 'Erreur lors de la modification de la quantité'. $th;
        }
    
    }

    

}
