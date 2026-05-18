<?php

require_once  __DIR__ . '/../function.php';

use App\Produit;
use App\Pharmacie;

try {
    
    $produit_inst = new Produit();

    $search = isset($_GET['r']) ? filter_input(INPUT_GET, securisation('r'), FILTER_SANITIZE_SPECIAL_CHARS) : null;
    $code_pharmacie = isset($_GET['code_pharmacie']) ? securisation(filter_input(INPUT_GET, 'code_pharmacie', FILTER_SANITIZE_SPECIAL_CHARS)) : null;
    
    $pharmacie_inst = new Pharmacie();

    if (empty($pharmacie_inst->findOne($code_pharmacie))) :
        messageServer('error', 'La pharmacie choisi n\'existe pas!');
    endif;

    if (!$search) :
        messageServer('error', 'Parametre vide!');
    endif;

    $produits = $produit_inst->searchByPharma($search, $code_pharmacie);
    
    if (empty($produits)) :
        messageServer('error', 'Aucun résultat trouvé!');
    endif;

    messageServer('success', 'Résultat trouvé', $produits);

} catch (Exception $e) {
    messageServer('error', $e->getMessage());
}
