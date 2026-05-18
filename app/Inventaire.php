<?php

namespace App;

class Inventaire extends Database{

    private $table = 'inventaire';
    private $code_pharmacie, $date_debut, $date_fin, $statut;

    private static $config;
    
    public function __construct($code_pharmacie = null, $date_debut = null, $date_fin = null, $statut = null){
        
        $this->code_pharmacie = $code_pharmacie;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->statut = $statut;

        self::$config = (ConfigDB::getInstance())->getConfig();
        parent::__construct(self::$config);
    }

    public function exist(){
        return self::findByParams($this->table, 'code_pharmacie = :code_pharmacie AND date_debut = :date_debut AND statut = :statut', [
            'code_pharmacie' => $this->code_pharmacie, 'date_debut' => $this->date_debut, 'statut' => 'en_cours'
        ]);
    }

    public function getByPharma($code_pharmacie): array {
        try {
            return self::findAllByParams($this->table, 'code_pharmacie = :code_pharma', ['code_pharma' => $code_pharmacie]);
        } catch (\Exception $th) {
            die("Error lors de le recup par pharma" . $th->getMessage());
        }
    }

    public function existProdInActivedInventaire($code_produit){
        try {
            $query = "SELECT * FROM inventaire_ligne il JOIN $this->table i ON i.code = il.code_inv WHERE il.code_produit = :code AND i.statut = 'en_cours'";
            $stmt = self::$db -> prepare($query);
            $stmt -> bindValue("code", $code_produit, \PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt->rowCount() > 0 ? $stmt->fetch(\PDO::FETCH_ASSOC) : [];
        } catch (\Throwable $th) {
            die("Error lors de le recup par pharma" . $th->getMessage());
        }
    }

    public function getActivedInventaireByPharma($code_pharmacie){
        try {
            $query = "SELECT * FROM $this->table WHERE code_pharmacie = :code AND statut = 'en_cours'";
            $stmt = self::$db -> prepare($query);
            $stmt -> bindValue("code", $code_pharmacie, \PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt->rowCount() > 0 ? $stmt->fetch(\PDO::FETCH_ASSOC) : [];
        } catch (\Throwable $th) {
            die("Error lors de le recup par pharma" . $th->getMessage());
        }
    }

    public function getAllInfoInventaireByPharma($code_pharmacie, $limit, $offset){
        try {
            $sql = "
                SELECT 
                    i.code,
                    i.code_pharmacie,
                    i.date_debut,
                    i.date_fin,
                    i.statut,
                    COUNT(il.id) AS nombre_erreurs,
                    SUM(ABS(il.difference_qte)) AS total_ecart_qte,
                    SUM(ABS(il.difference_qte_boite)) AS total_ecart_qte_boite
                FROM inventaire i
                LEFT JOIN inventaire_ligne il 
                    ON il.code_inv = i.code
                WHERE i.code_pharmacie = :code
                GROUP BY 
                    i.code,
                    i.code_pharmacie,
                    i.date_debut,
                    i.date_fin,
                    i.statut
                ORDER BY i.date_debut DESC
                LIMIT :limit OFFSET :offset
            ";
            $stmt = self::$db->prepare($sql);
            $stmt -> bindValue("code", $code_pharmacie, \PDO::PARAM_STR);
            $stmt -> bindValue("limit", (int)$limit, \PDO::PARAM_INT);
            $stmt -> bindValue("offset", (int)$offset, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            die("Error lors de le recup GetAllActivedInventaireByPharma" . $th->getMessage());
        }
    }

    public function getAllInfoByInventaire($code_inventaire){
        try {
            $sql = "
            SELECT 
                i.code,
                i.date_debut,
                i.date_fin,
                i.statut,
                p.nom, p.nom_scientifique,
                il.quantite_systeme, il.qte_boite_system, il.quantite_actuelle, il.qte_boite_actuelle,
                il.difference_qte,
                il.difference_qte_boite,
                COUNT(il.id) AS nombre_produits_inv
            FROM $this->table i
            JOIN inventaire_ligne il ON i.code = il.code_inv
            JOIN produits p ON p.code = il.code_produit
            WHERE i.code = :code_inventaire
            GROUP BY 
                i.code,
                i.date_debut,
                i.date_fin,
                i.statut,
                p.nom, p.nom_scientifique,
                il.quantite_systeme, il.qte_boite_system, il.quantite_actuelle, il.qte_boite_actuelle,
                il.difference_qte,
                il.difference_qte_boite,
                il.id
            ORDER BY il.id DESC
            ";
            $stmt = self::$db->prepare($sql);
            $stmt -> bindValue("code_inventaire", $code_inventaire, \PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            die("Error lors de le recup GetAllInfoByInventaire" . $th->getMessage());
        }
    }
    public function getRapportInvent($code_inventaire){
        try {
            $sql = "
            SELECT
                p.nom,
                il.quantite_systeme AS nbr_plaquette_sys, il.qte_boite_system AS nbr_boite_sys, il.quantite_actuelle AS nbr_plaquette_reel, il.qte_boite_actuelle AS nbr_boite_reel,
                il.difference_qte AS difference_plaquette, il.difference_qte_boite AS difference_boite                
            FROM $this->table i
            JOIN inventaire_ligne il ON i.code = il.code_inv
            JOIN produits p ON p.code = il.code_produit
            WHERE i.code = :code_inventaire
            ORDER BY p.nom
            ";
            $stmt = self::$db->prepare($sql);
            $stmt -> bindValue("code_inventaire", $code_inventaire, \PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            die("Error lors de le recup GetAllInfoByInventaire" . $th->getMessage());
        }
    }


    public function getProdAlreadyInInventaire($code_pharmacie){
        try {
            $query = "SELECT * FROM inventaire_ligne il JOIN $this->table i ON i.code = il.code_inv WHERE i.code_pharmacie = :code_pharmacie AND i.statut = 'en_cours'";
            $stmt = self::$db -> prepare($query);
            $stmt -> bindValue("code_pharmacie", $code_pharmacie, \PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];
        } catch (\Throwable $th) {
            die("Error lors de le recup par pharma" . $th->getMessage());
        }
    }


    public function updateOne($column, $param, $data){

        try {
            return self::updateByParam($this->table, $column, $param, $data);
        } catch (\Exception $th) {
            return 'Erreur lors de la modification de la quantité'. $th;
        }
    
    }

    public function getLastInvByPharma($code_pharmacie){
        try {
            $query = "SELECT * FROM $this->table WHERE code_pharmacie = :code ORDER BY id DESC LIMIT 1";
            $stmt = self::$db -> prepare($query);
            $stmt -> bindValue("code", $code_pharmacie, \PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt->rowCount() > 0 ? $stmt->fetch(\PDO::FETCH_ASSOC) : [];
        } catch (\Throwable $th) {
            die("Error lors de le recup du dernier inventaire par pharma" . $th->getMessage());
        }
    }

    public function add(): bool{

        $data = [
            'code_pharmacie' => $this->code_pharmacie,
            'date_debut'=> $this->date_debut,
            // 'date_fin' => null,
            'statut' => $this->statut,
            'code' => bin2hex(random_bytes(16))
        ];

        try {
            return self::insert($this->table, $data);
        } catch (\Throwable $th) {
            die($th->getMessage());
        }        
    }

    public function addInvLigne($code_inventaire = null, $code_produit = null, $quantite_systeme = null, $qte_boite_system = null, $type = null, $quantite = null, $difference = null){

        $data = [
            'code_inv' => $code_inventaire,
            'code_produit'=> $code_produit,
            'quantite_systeme' => $quantite_systeme,
            'qte_boite_system' => $qte_boite_system,
            'quantite_actuelle' => $type !== 'edit-boite' ? $quantite : null,
            'qte_boite_actuelle' => $type === 'edit-boite' ? $quantite : null,
            'difference_qte' => $type !== 'edit-boite' ? $difference : null,
          	'difference_qte_boite' => $type === 'edit-boite' ? $difference : null,
            'code' => bin2hex(random_bytes(16)),
            'temps' => time()
        ];

        try {
            return self::insert('inventaire_ligne', $data);
        } catch (\Throwable $th) {
            die($th->getMessage());
        }        
    }

    public function updateActuelle($column, $params, $data){
        try {
            return self::updateByParam('inventaire_ligne', $column, $params, $data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function deleteInvLigneDifNull($code_inventaire){
        try {
            $query = "DELETE FROM inventaire_ligne WHERE code_inv = :code_inventaire AND difference_qte IS NULL OR difference_qte = 0 AND difference_qte_boite IS NULL OR difference_qte_boite = 0";
            $stmt = self::$db -> prepare($query);
            $stmt -> bindValue("code_inventaire", $code_inventaire, \PDO::PARAM_STR);
            return $stmt->execute();
        } catch (\Throwable $th) {
            die("Error lors de la suppression de la ligne d'inventaire" . $th->getMessage());
        }
    }
}
