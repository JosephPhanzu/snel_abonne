<?php

use App\Pharmacie;
use App\Inventaire;
require_once __DIR__ . '/../function.php';
try {
    
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;

    $code_pharmacie = isset($_GET['code_pharmacie']) ? filter_input(INPUT_GET, securisation('code_pharmacie'), FILTER_SANITIZE_SPECIAL_CHARS) : null;
    $code_inventaire = isset($_GET['code_inventaire']) ? filter_input(INPUT_GET, securisation('code_inventaire'), FILTER_SANITIZE_SPECIAL_CHARS) : null;

    $pharmacie_inst = new Pharmacie();
    
    if (empty($pharmacie_inst->findOne($code_pharmacie))) :
        messageServer('error', 'La pharmacie choisi n\'existe pas!');
    endif;
    
    $inventaire_inst = new Inventaire();
    
    $details_inventaires = $inventaire_inst->getAllInfoByInventaire($code_inventaire);

    // testDebug($code_pharmacie);

    if (empty($details_inventaires)) :
        messageServer('error', 'Aucun produit trouvé!');
    endif;

    messageServer('success','Produit trouvé avec succès', $details_inventaires);

} catch (Exception $e) {
    messageServer('error', 'Erreur serveur :' . $e->getMessage());
}

