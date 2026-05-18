<?php

use App\Produit; 
require_once __DIR__ . '/../function.php';

try {

    $code_produit = isset($_POST['code_produit']) ? filter_input(INPUT_POST, securisation('code_produit'), FILTER_SANITIZE_SPECIAL_CHARS) : "";
    $prix_achat = isset($_POST['prix_achat']) ? filter_input(INPUT_POST, securisation('prix_achat'), FILTER_VALIDATE_FLOAT) : '';

    $quantite = isset($_POST['stock']) ? filter_input(INPUT_POST, securisation('stock'), FILTER_VALIDATE_FLOAT) : '';

    $qte_boite = isset($_POST['qte_boite']) ? (int)filter_input(INPUT_POST, securisation('qte_boite'), FILTER_VALIDATE_INT) : null;
    $qte_par_boite = isset($_POST['qte_par_boite']) ? (int)filter_input(INPUT_POST, securisation('qte_par_boite'), FILTER_VALIDATE_INT) : null;

    if (isset($_POST['qte_boite']) && isset($_POST['qte_par_boite'])) :
        $quantite = $qte_par_boite * $qte_boite;
    endif;

    $prix_achat_unitaire = round($prix_achat / $quantite, 2);

    if (empty($quantite) && empty($prix_achat)) :
        messageServer('error', 'Tous les champs sont obligatoires!');
    endif;

    $produit_inst = new Produit();

    if (empty($produit = $produit_inst->getByCode($code_produit))) :
        messageServer('error', 'Le produit n\'existe pas!');
    endif;
    $quantiteBdd = $produit['quantite'];
    $qte_boiteBdd = $produit['qte_boite'];
    $qte_par_boiteBdd = $produit['qte_par_boite'];
    $prix_achat_unitaireBdd = $produit['prix_achat_unitaire'];

    $quantite += $quantiteBdd;
  	//testDebug($quantite);
    if(isset($_POST['qte_boite']) && isset($_POST['qte_par_boite'])) :

        $qte_boite += $qte_boiteBdd;
        if (!$produit_inst->updateOne('qte_boite = ?, quantite = ?', 'code = ?', [$qte_boite, $quantite, $code_produit])):
            messageServer('error', 'Problème lors des mise à jour');
        endif;
    else :
        if (!$produit_inst->updateOne('quantite = ?', 'code = ?', [$quantite, $code_produit])):
            messageServer('error', 'Problème lors de la mise à jour');
        endif;
    endif;

    if ($prix_achat_unitaireBdd !== $prix_achat_unitaire) :
        if (!$produit_inst->updateOne('prix_achat_unitaire = ?', 'code = ?', [$prix_achat_unitaire, $code_produit])) :
            messageServer('error', 'Erreur lord de la mise à jour prix');
        endif;
    endif;

    messageServer('success', 'Mise à jour quantité fait avec succès!');

} catch (\Exception $th) {
    messageServer('error', $th->getMessage());
}