<?php

use App\Produit;
use App\Inventaire;
require_once __DIR__ . '/../function.php';

try {
    $code_produit = isset($_POST['code_produit']) ? filter_input(INPUT_POST, securisation('code_produit'), FILTER_SANITIZE_SPECIAL_CHARS) : "";
    $code_pharmacie = isset($_POST['code_pharmacie']) ? filter_input(INPUT_POST, securisation('code_pharmacie'), FILTER_SANITIZE_SPECIAL_CHARS) : "";
    $quantite = isset($_POST['quantite']) ? filter_input(INPUT_POST, securisation('quantite'), FILTER_VALIDATE_FLOAT) : "";
    $type = isset($_POST['type']) ? filter_input(INPUT_POST, securisation('type'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    
    if($quantite === '' && $quantite !== 0) :
        messageServer('error', 'Vous devez entrer une quantié');
    endif;

    $produit_inst = new Produit();
    $inventaire_inst = new Inventaire();

    if (empty($info_inventaire = $inventaire_inst->getActivedInventaireByPharma($code_pharmacie))) :
        messageServer('error', 'Aucun inventaire en cours pour cette pharmacie!');
    endif;
    
    if(empty($produit_info = $produit_inst->getByCode($code_produit))) :
        messageServer('error', 'Produit invalid, veuillez choisir un produit valide!');
    endif;

    $difference = $type === 'edit-boite' ? $produit_info['qte_boite'] - $quantite 
    : $difference = $produit_info['quantite'] - $quantite;
    
    $column = $type === 'edit-boite' ? 'qte_boite_actuelle = ?, difference_qte_boite = ?' 
    : 'quantite_actuelle = ?, difference_qte = ?';

    $data = [$quantite, $difference, $code_produit];
    // testDebug($difference);
    // if ($difference == 0) :
    //     messageServer('success', 'Aucune modification de quantité pour ce produit!');
    // endif;
    
    if ($inventaire_inst->existProdInActivedInventaire($code_produit)) :
        
        if ($inventaire_inst->updateActuelle($column, 'code_produit = ?', $data)) :
            messageServer('success', 'quantite pour ce produit moifié avec succès!');
        endif;

        messageServer('error', 'Problème lors de l\'update quantite produit!');
    endif;

    if ($inventaire_inst->addInvLigne($info_inventaire['code'], $code_produit, $produit_info['quantite'], $produit_info['qte_boite'], $type, $quantite, $difference)) :
        messageServer('success', 'quantite pour ce produit ajouté avec succès!');
    endif;

    messageServer('error', 'Problème lors de l\'update quantite produit');

} catch (\Exception $th) {
    messageServer('error', $th->getMessage());
}