<?php

use App\Pharmacie;
use App\Inventaire;
require_once __DIR__ . '/../function.php';
try {
    
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;

    $code_pharmacie = isset($_GET['code_pharmacie']) ? filter_input(INPUT_GET, securisation('code_pharmacie'), FILTER_SANITIZE_SPECIAL_CHARS) : null;

    $pharmacie_inst = new Pharmacie();

    if (empty($pharmacie_inst->findOne($code_pharmacie))) :
        messageServer('error', 'La pharmacie choisi n\'existe pas!');
    endif;
    
    $inventaire_inst = new Inventaire();

    $total = COUNT($inventaire_inst->getByPharma($code_pharmacie));
    
    $inventaires = $inventaire_inst->getAllInfoInventaireByPharma($code_pharmacie, $limit, $offset);
    // testDebug($inventaires);
    if (empty($inventaires)) :
        messageServer('error', 'Aucun produit trouvé!');
    endif;

    messageServer('success','Produit trouvé avec succès', $inventaires, $total);

} catch (Exception $e) {
    messageServer('error', 'Erreur serveur :' . $e->getMessage());
}

