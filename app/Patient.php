<?php

namespace App;
class Patient extends Database {

    protected static $table = "patients";
    private static $code_receptioniste, $nom, $prenom, $telephone, $sexe, $date_naissance, $statut, $num_fiche, $assigned_medecin;
    private static $config;

    public function __construct($code_receptioniste = null, $nom = null, $prenom = null, $date_naissance = null, $telephone = null, $sexe = null, $num_fiche = null, $statut = null, $assigned_medecin = null) {

        self::$code_receptioniste = $code_receptioniste;
        self::$nom = $nom;
        self::$prenom = $prenom;
        self::$date_naissance = $date_naissance;
        self::$telephone = $telephone;
        self::$sexe = $sexe;
        self::$statut = $statut;
        self::$num_fiche = $num_fiche;
        self::$assigned_medecin = $assigned_medecin;
        self::$config = (ConfigDB::getInstance())->getConfig();

        parent::__construct(self::$config);

    }

    public static function add() {

        $data = [
            'code_receptioniste' => self::$code_receptioniste,
            'nom' => self::$nom,
            'prenom' => self::$prenom,
            'telephone' => self::$telephone,
            'sexe' => self::$sexe,
            'date_naissance'=> self::$date_naissance,
            'statut' => self::$statut,
            'code' => bin2hex(random_bytes(16)),
            'num_fiche' => self::$num_fiche,
            'assigned_medecin' => self::$assigned_medecin,
        ];

        try {
            return self::insert(self::$table, $data);
            // $id = self::$db->lastInsertId();
            // return self::findByParams(self::$table, 'id = :id', ['id' => $id]);
        } catch (\Exception $th) {
            die('Erreur lors de l\'insertion utilisateur'. $th->getMessage());
        }
    }

    public static function updateInfo($code_employe, $nom, $prenom, $telephone, $statut) {

         $data = [
            $nom,
            $prenom,
            $telephone,
            $statut,
            $code_employe
        ];  
        try {
            return self::updateByParam(self::$table, 'nom = ?, prenom = ?, salaire = ?, telephone = ?, statut = ?', 'code = ?', $data);
        } catch (\Exception $th) {
            die($th->getMessage());
        }
    }

    public static function getAll() {
        return self::all(self::$table);
    }

    public static function exist (){

        $params = 'num_fiche = :num_fiche';
        $data = [
            'num_fiche' => self::$num_fiche
        ];
        return self::findByParams(self::$table, $params, $data);
    }

    public static function getByCode($code){
        return self::find(self::$table, $code);
    }

    public static function getPaginate($limit, $offset) {
        return self::paginate(self::$table, $limit, $offset);
    }

    public static function getByMedecin($code_receptioniste, $limit, $offset) {
        try {

            $stmt = self::$db->prepare(query: "
                SELECT e.code, e.nom, e.prenom, e.salaire, e.telephone, e.sexe, e.date_naissance, e.statut, e.num_fiche, c.montant, c.date_paiement, c.statut AS statut_cotisation
                FROM ".self::$table." e
                INNER JOIN cotisations c ON e.code = c.code_employe
                WHERE e.code_receptioniste = :code_receptioniste
                ORDER BY e.nom
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue("code_receptioniste", $code_receptioniste, \PDO::PARAM_STR);
            $stmt->bindValue("limit", $limit, \PDO::PARAM_INT);
            $stmt->bindValue("offset", $offset, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $th) {
            die("Error lors de le recup par employeur" . $th->getMessage());
        }
    }

    public static function getAllByEmployeur($code_receptioniste) {
        try {

            $stmt = self::$db->prepare(query: "
                SELECT e.statut, e.sexe
                FROM ".self::$table." e
                WHERE e.code_receptioniste = :code_receptioniste
                ORDER BY e.nom
            ");
            $stmt->bindValue("code_receptioniste", $code_receptioniste, \PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $th) {
            die("Error lors de le recup par pharma" . $th->getMessage());
        }
    }

    public static function deleteOne($code) {
        try {
            return self::delete(self::$table, $code);
        } catch (\Throwable $th) {
            die('Erreur lors de la suppression'. $th->getMessage());
        }
    }
}
