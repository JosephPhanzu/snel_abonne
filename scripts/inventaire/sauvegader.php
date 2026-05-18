<?php

use App\Produit;
use App\Pharmacie;
require_once  __DIR__ . '/../function.php';

try {

    $code_pharmacie = isset($_POST['code_pharmacie']) ? filter_input(INPUT_POST, securisation('code_pharmacie'), FILTER_SANITIZE_SPECIAL_CHARS) : '';

    $pharmacie_inst = new Pharmacie();

    if (empty($pharmacie_inst->findOne($code_pharmacie))) :
        messageServer('error', 'Pharmacie invalide!');
    endif;
    
    $produit_inst = new Produit();

    $save = $produit_inst->sauvegarderActuelle($code_pharmacie);
    if ($save) :
        messageServer('success','Etat sauvegarder avec succès.');
    endif;
    
    messageServer('error','Une erreur est survenue lors du sauvegarder.');

} catch (Exception $e) {
    messageServer('error', $e->getMessage());
}
