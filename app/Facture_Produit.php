<?php

namespace App;

class Facture_Produit extends Database{

    private $table = "facture_produit";
    private $code_facture, $code_produit, $quantite, $prix, $nom;
    private static $config;

    public function __construct(){

        self::$config = (ConfigDB::getInstance())->getConfig();
        parent::__construct(self::$config);
    }

    public function getAll(){
        return self::all($this->table);
    }

    public function findOne($id){
        return self::findByParams($this->table, 'code_produit = :code_produit', ['code_produit' => $id]);
    }

    public function add($code_facture, $code_produit, $quantite, $prix, $nom){
        
        $this->code_facture =  $code_facture;
        $this->code_produit = $code_produit;
        $this->quantite = $quantite;
        $this->prix = $prix;
        $this->nom = $nom;

        $data = [
            'code_facture' => $this->code_facture,
            'code_produit' => $this->code_produit,
            'quantite' => $this->quantite,
            'prix' => $this->prix,
            'nom_produit' => $this->nom,
        ];
        
        try {
            return self::insert($this->table, $data);
        } catch (\Exception $th) {
            die('Erreur d\'enregistrement'. $th);
        }
    
    }
}