<?php

use App\Produit; 
require_once __DIR__ . '/../function.php';

try {

    $code_produit = isset($_POST['code_produit']) ? filter_input(INPUT_POST, securisation('code_produit'), FILTER_SANITIZE_SPECIAL_CHARS) : "";
    $nom = isset($_POST['nom']) ? filter_input(INPUT_POST, securisation('nom'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $nom_scientifique = isset($_POST['nom_scientifique']) ? filter_input(INPUT_POST, securisation('nom_scientifique'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $description = isset($_POST['description']) ? filter_input(INPUT_POST, securisation('description'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $categorie = isset($_POST['categorie']) ? filter_input(INPUT_POST, securisation('categorie'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $code_pharmacie = isset($_POST['code_pharmacie']) ? filter_input(INPUT_POST, securisation('code_pharmacie'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $fournisseur = isset($_POST['fournisseur']) ? filter_input(INPUT_POST, securisation('fournisseur'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $stock_min = isset($_POST['stock_min']) ? (int)filter_input(INPUT_POST, securisation('stock_min'), FILTER_VALIDATE_INT) : '';
    $date = isset($_POST['date_peremption']) ? filter_input(INPUT_POST, securisation('date_peremption'), FILTER_SANITIZE_SPECIAL_CHARS) : '';

    if (!empty($nom) && !empty($description) && !empty($fournisseur) && !empty($categorie) && !empty($nom_scientifique) && !empty($stock_min) && !empty($date)) :

        $produit_inst = new Produit($nom, $nom_scientifique,$description, null, $date, $categorie, $code_pharmacie, $fournisseur, null, null, null, null, $stock_min);

        if (empty($produit_inst->getByCode($code_produit))) :
            messageServer('error', 'Le produit n\'existe pas!');
        endif;

        if ($produit_inst->updateProduit($code_produit)) :
            messageServer('success', 'Produit modifié avec succèss!');
        endif;

        messageServer('error', 'Problème lors de la  modification!');
        
    endif;

    messageServer('error', 'Tous les champs sont obligatoires!');

} catch (\Exception $th) {
    messageServer('error', $th->getMessage());
}