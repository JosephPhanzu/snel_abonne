<?php

use App\Produit; 
require_once __DIR__ . '/../function.php';

try {
    $code_produit = isset($_POST['code_produit']) ? filter_input(INPUT_POST, securisation('code_produit'), FILTER_SANITIZE_SPECIAL_CHARS) : "";
    $marge = isset($_POST['marge']) ? filter_input(INPUT_POST, securisation('marge'), FILTER_VALIDATE_INT) : "";

    if (empty($marge) && $marge !== 0) :
        messageServer('error', 'Champ obligatoire');
    endif;

    $produit_inst = new Produit();

    if (empty($produit_inst->getByCode($code_produit))) :
        messageServer('error', 'Le produit n\'existe pas!');
    endif;

    if ($produit_inst->updateMarge($code_produit, $marge)) :
        messageServer('success', 'Marge pour ce produit moifié avec succès!');
    endif;

    messageServer('error', 'Problème lors de l\'update marge produit');

} catch (\Exception $th) {
    messageServer('error', $th->getMessage());
}