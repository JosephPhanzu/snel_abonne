<?php

namespace App;

class Produit extends Database{

    private $table = 'produits';
    private $nom, $nom_scientifique, $description, $quantite, $date, $categorie, $num_lot, $code_pharmacie, $fournisseur, $prix_achat, $prix_achat_unitaire, $qte_boite, $qte_par_boite, $stock_min;

    private static $config;
    
    public function __construct($nom = null, $nom_scientifique = null, $description = null, $quantite = null, $date = null, $categorie = null, $num_lot = null, $code_pharmacie = null, $fournisseur = null, $prix_achat = null, $prix_achat_unitaire = null, $qte_boite = null, $qte_par_boite = null, $stock_min = null) {
        
        $this->nom = $nom;
        $this->nom_scientifique = $nom_scientifique;
        $this->description = $description;
        $this->quantite = $quantite;
        $this->date = $date;
        $this->categorie = $categorie;
      	$this->num_lot = $num_lot;
        $this->code_pharmacie = $code_pharmacie;
        $this->fournisseur = $fournisseur;
        $this->prix_achat = $prix_achat;
        $this->prix_achat_unitaire = $prix_achat_unitaire;
        $this->qte_boite = $qte_boite;
        $this->qte_par_boite = $qte_par_boite;
        $this->stock_min = $stock_min;

        self::$config = (ConfigDB::getInstance())->getConfig();
        parent::__construct(self::$config);
    }

    public function getAll(){
        return self::all($this->table);
    }

    public function getPaginate($limit, $offset) {
        return self::paginate($this->table, $limit, $offset);
    }

    public function exist(){
        return self::findByParams($this->table, 'nom = :nom AND categorie = :categorie AND code_pharmacie = :code', [
            'nom' => $this->nom, 'categorie' => $this->categorie, 'code' => $this->code_pharmacie
        ]);
    }

    public function searchOne($search){
        try {
            return self::search($this->table, 'nom', $search, '*');
        } catch (\Throwable $th) {
            die($th->getMessage());
        }
        
    }

    public function searchByPharma($search, $code_pharmacie): array {

        $stmt = self::$db->prepare("SELECT * FROM $this->table WHERE CONCAT(LOWER(nom), LOWER(nom_scientifique)) LIKE :keyword AND code_pharmacie = :code_pharma LIMIT :limit OFFSET :offset");
        $stmt->bindValue('keyword', '%'.strtolower($search).'%', \PDO::PARAM_STR);
        $stmt->bindValue("code_pharma", $code_pharmacie, \PDO::PARAM_STR);
        $stmt->bindValue("limit", 10, \PDO::PARAM_INT);
        $stmt->bindValue("offset", 0, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];

    }

    public function getByPharma($code_pharmacie): array {
        try {
            return self::findAllByParams($this->table, 'code_pharmacie = :code_pharma', ['code_pharma' => $code_pharmacie]);
        } catch (\Exception $th) {
            die("Error lors de le recup par pharma" . $th->getMessage());
        }
    }

    public function getRappotByPharma($code_pharmacie, $limit, $offset){
        try {
            $query = "SELECT ROW_NUMBER() OVER (PARTITION BY code_pharmacie ORDER BY id) AS id, nom, nom_scientifique, description, quantite, date_peremption, categorie, fournisseur, prix_achat, prix_achat_unitaire FROM $this->table WHERE code_pharmacie = :code ORDER BY id LIMIT :limit OFFSET :offset";
            $stmt = self::$db -> prepare($query);
            $stmt -> bindValue("code", $code_pharmacie, \PDO::PARAM_STR);
            $stmt -> bindValue(":limit", (int)$limit, \PDO::PARAM_INT);
            $stmt -> bindValue(":offset", (int)$offset, \PDO::PARAM_INT);
            $stmt -> execute();
            return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];
        } catch (\Throwable $th) {
            die("Error lors de le recup Paginate par pharma" . $th->getMessage());
        }
    }
    
    public function paginateByPharma($code_pharmacie, $limit, $offset){
        try {
            $query = "SELECT * FROM $this->table WHERE code_pharmacie = :code LIMIT :limit OFFSET :offset";
            $stmt = self::$db -> prepare($query);
            $stmt -> bindValue("code", $code_pharmacie, \PDO::PARAM_STR);
            $stmt -> bindValue(":limit", (int)$limit, \PDO::PARAM_INT);
            $stmt -> bindValue(":offset", (int)$offset, \PDO::PARAM_INT);
            $stmt -> execute();
            return $stmt->rowCount() > 0 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : [];
        } catch (\Throwable $th) {
            die("Error lors de le recup Paginate par pharma" . $th->getMessage());
        }
    }

    public function getByCode($code){
        return self::find($this->table, $code);
    }

    public function updateQte($code, $quantite){

        try {
            return self::updateByParam($this->table, 'quantite = ?', 'code = ?', [
                $quantite, $code
            ]);
        } catch (\Exception $th) {
            return 'Erreur lors de la modification de la quantité'. $th;
        }
    
    }

    public function updateQteBoite($code, $qte_boite){

        try {
            return self::updateByParam($this->table, 'qte_boite = ?', 'code = ?', [
                $qte_boite, $code
            ]);
        } catch (\Exception $th) {
            return 'Erreur lors de la modification de la quantité'. $th;
        }
    
    }

    public function updateMarge($code, $marge){

        try {
            return self::updateByParam($this->table, 'marge = ?', 'code = ?', [
                $marge, $code
            ]);
        } catch (\Exception $th) {
            return 'Erreur lors de la modification de la quantité'. $th;
        }
    
    }

    public function updateOne($column, $param, $data){

        try {
            return self::updateByParam($this->table, $column, $param, $data);
        } catch (\Exception $th) {
            return 'Erreur lors de la modification de la quantité'. $th;
        }
    
    }

    public function updateProduit($code_produit){

        $data = [
            $this->nom,
            $this->nom_scientifique,
            $this->description,
            $this->date,
            $this->categorie,
            $this->fournisseur,
            $this->stock_min,
            $code_produit
        ];
        try {
            return self::updateByParam($this->table, 'nom = ?, nom_scientifique = ?, description = ?, date_peremption = ?, categorie = ?, fournisseur = ?, stock_min = ?', 'code = ?', $data);
        } catch (\Exception $th) {
            die($th->getMessage());
        }
    }

    public function add(){

        $data = [
            'nom' => $this->nom,
            'nom_scientifique'=> $this->nom_scientifique,
            'description' => $this->description,
            'quantite' => $this->quantite,
            'date_peremption' => $this->date,
            'categorie' => $this->categorie,
          	'num_lot' => $this->num_lot,
            'code_pharmacie' => $this->code_pharmacie,
            'fournisseur' => $this->fournisseur,
            'prix_achat' => $this->prix_achat,
            'prix_achat_unitaire' => $this->prix_achat_unitaire,
            'qte_boite' => $this->qte_boite,
            'qte_par_boite' => $this->qte_par_boite,
            'stock_min' => $this->stock_min,
            'code' => bin2hex(random_bytes(16)),
            'temps' => time()
        ];

        try {
            return self::insert($this->table, $data);
        } catch (\Throwable $th) {
            die($th->getMessage());
        }        
    }



    // Inventaire
    public function sauvegarderActuelle($code_pharmacie) {
        try {
            $stmt = self::$db->prepare("
                INSERT INTO inventaire (code_produit, quantite_actuelle, qte_boite_actuelle, difference_qte, difference_qte_boite, code)
                SELECT 
                    p.code,
                    0,  -- valeur saisie par l’utilisateur (exemple ici initialisé à 0)
                    0,  -- idem
                    p.quantite - 0,
                    p.qte_boite - 0,
                    :code_invent
                FROM produits p
                WHERE p.code_pharmacie = :code
            ");
            $stmt->bindValue('code_invent', bin2hex(random_bytes(16)), \PDO::PARAM_STR);
            $stmt->bindValue('code', $code_pharmacie, \PDO::PARAM_STR);
            return $stmt->execute();
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function updateActuelle($column, $params, $data){
        try {
            return self::updateByParam('inventaire', $column, $params, $data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
