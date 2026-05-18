<?php

use App\Pharmacie;
use App\Mouvement_stock;
require_once __DIR__ . '/../function.php';
try {
    
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;

    $code_pharmacie = isset($_GET['code_pharmacie']) ? filter_input(INPUT_GET, securisation('code_pharmacie'), FILTER_SANITIZE_SPECIAL_CHARS) : null;
    
    $debut = date('Y-m-d', strtotime('2026-01-01')); // Par défaut, début il y a 1 mois
    
    $fin = date('Y-m-d', strtotime('2026-02-01') - 1); // Par défaut, fin aujourd'hui
    
    $pharmacie_inst = new Pharmacie();
    
    if (empty($pharmacie_inst->findOne($code_pharmacie))) :
        messageServer('error', 'La pharmacie choisi n\'existe pas!');
    endif;
    
    $mouvement_inst = new Mouvement_stock();
    
    $details_mouvements = $mouvement_inst->getMvmByPharmaJoin($code_pharmacie, $debut, $fin);
    // testDebug($code_pharmacie);

    if (empty($details_mouvements)) :
        messageServer('error', 'Aucun produit trouvé!');
    endif;

    messageServer('success','Rapport trouvé avec succès', $details_mouvements);

} catch (Exception $e) {
    messageServer('error', 'Erreur serveur :' . $e->getMessage());
}

