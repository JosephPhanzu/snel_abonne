<?php

use App\Produit;
use App\Pharmacie;
require_once  __DIR__ . '/../function.php';

try {
    
    
    $nom = isset($_POST['nom']) ? filter_input(INPUT_POST, securisation('nom'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $nom_scientifique = isset($_POST['nom_scientifique']) ? filter_input(INPUT_POST, securisation('nom_scientifique'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $description = isset($_POST['description']) ? filter_input(INPUT_POST, securisation('description'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $date = isset($_POST['date_peremption']) ? filter_input(INPUT_POST, securisation('date_peremption'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $categorie = isset($_POST['categorie']) ? filter_input(INPUT_POST, securisation('categorie'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
  	$num_lot = isset($_POST['num_lot']) ? filter_input(INPUT_POST, securisation('num_lot'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $code_pharmacie = isset($_POST['code_pharmacie']) ? filter_input(INPUT_POST, securisation('code_pharmacie'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $fournisseur = isset($_POST['fournisseur']) ? filter_input(INPUT_POST, securisation('fournisseur'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $prix_achat = isset($_POST['prix_achat']) ? filter_input(INPUT_POST, securisation('prix_achat'), FILTER_VALIDATE_FLOAT) : '';
    $quantite = isset($_POST['stock']) ? filter_input(INPUT_POST, securisation('stock'), FILTER_VALIDATE_FLOAT) : '';
    $qte_boite = isset($_POST['qte_boite']) ? (int)filter_input(INPUT_POST, securisation('qte_boite'), FILTER_VALIDATE_INT) : null;
    $qte_par_boite = isset($_POST['qte_par_boite']) ? (int)filter_input(INPUT_POST, securisation('qte_par_boite'), FILTER_VALIDATE_INT) : null;
    $stock_min = isset($_POST['stock_min']) ? (int)filter_input(INPUT_POST, securisation('stock_min'), FILTER_VALIDATE_INT) : '';
	
  	
  
    if (isset($_POST['qte_boite']) && isset($_POST['qte_par_boite'])) :
        $quantite = $qte_par_boite * $qte_boite;
    endif;
    
    // Validation des données
    if (!empty($nom) && !empty($description) && !empty($quantite) && !empty($fournisseur) && !empty($date) && !empty($categorie) && !empty($nom_scientifique) && !empty($prix_achat)) :

        $prix_achat_unitaire = round($prix_achat / $quantite, 2);

        $pharmacie_inst = new Pharmacie();

        if (empty($pharmacie_inst->findOne($code_pharmacie))) :
            messageServer('error', 'Pharmacie invalide!');
        endif;
        
        $produit_inst = new Produit($nom, $nom_scientifique,$description, $quantite, $date, $categorie, $num_lot, $code_pharmacie, $fournisseur, $prix_achat, $prix_achat_unitaire, $qte_boite, $qte_par_boite, $stock_min);
        
        $exist = $produit_inst->exist();
        if ($exist) :
            messageServer('error', 'Cet produit existes déjà en stock');
        endif;
		
        $save = $produit_inst->add();
  		
        if ($save) :
            messageServer('success','Produit ajouter avec succès.');
        endif;
        
        messageServer('error','Une erreur est survenue lors de l\'enregistrement.');
        
    endif;
    messageServer('error', 'Tous les champs sont obligatoires!');

} catch (Exception $e) {
    messageServer('error', $e->getMessage());
}
