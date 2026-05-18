<?php

use App\Pharmacie;
use App\Inventaire;
require_once __DIR__ . '/../function.php';
try {

    $code_pharmacie = isset($_GET['code_pharmacie']) ? filter_input(INPUT_GET, securisation('code_pharmacie'), FILTER_SANITIZE_SPECIAL_CHARS) : null;
    
    $pharmacie_inst = new Pharmacie();
    
    if (empty($pharmacie_inst->findOne($code_pharmacie))) :
        messageServer('error', 'La pharmacie choisi n\'existe pas!');
    endif;
    
    $inventaire_inst = new Inventaire();

    if (empty($info = $inventaire_inst->getActivedInventaireByPharma($code_pharmacie))) :
        messageServer('error', 'Aucun inventaire en cours trouvé pour cette pharmacie!');
    endif;
    
    $details_inventaires = $inventaire_inst->getRapportInvent($info['code']);

    if (empty($details_inventaires)) :
        messageServer('error', 'Aucun produit trouvé!');
    endif;

    messageServer('success','Rapport trouvé avec succès', $details_inventaires);

} catch (Exception $e) {
    messageServer('error', 'Erreur serveur :' . $e->getMessage());
}

