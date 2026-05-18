<?php

use App\Produit; 
require_once __DIR__ . '/../function.php';

try {
    $code_produit = isset($_POST['code_produit']) ? securisation($_POST['code_produit']) : "";
    $marge = isset($_POST['marge']) ? securisation($_POST['marge']) : "";

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