<?php

use App\Produit;
use App\Pharmacie;
require_once __DIR__ . '/../function.php';

try {
    
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
    $offset = ($page - 1) * $limit;

    $code_pharmacie = isset($_GET['code_pharmacie']) ? filter_input(INPUT_GET, securisation('code_pharmacie'), FILTER_SANITIZE_SPECIAL_CHARS) : null;

    $pharmacie_inst = new Pharmacie();

    if (empty($pharmacie_inst->findOne($code_pharmacie))) :
        messageServer('error', 'La pharmacie choisi n\'existe pas!');
    endif;

    $produit_inst = new Produit();
    
    $total = $produit_inst->getByPharma($code_pharmacie);
    
    $produits = $produit_inst->paginateByPharma($code_pharmacie, $limit, $offset);
    $rappot = $produit_inst->getRappotByPharma($code_pharmacie, $limit, $offset);

    if (empty($produits)) :
        messageServer('error', 'Aucun produit trouvé!');
    endif;

    // messageServer('success','Produit trouvé avec succès', $produits, $total);
    echo json_encode([
        'status' => 'success',
        'message' => 'Produit trouvé avec succès',
        'data' => $produits,
        'total' => $total,
        'rappot' => $rappot
    ]);
    exit();

} catch (Exception $e) {
    echo json_encode([
        'error' => true,
        'status' => 'error',
        'message' => 'Erreur serveur : ' . $e->getMessage()
    ]);
}
exit;
?>
