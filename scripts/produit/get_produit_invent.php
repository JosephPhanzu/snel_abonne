<?php

use App\Produit;
use App\Pharmacie;
use App\Inventaire;
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
    $inventaire_inst = new Inventaire();
    
    $total = $produit_inst->getByPharma($code_pharmacie);
    
    $produits = $produit_inst->paginateByPharma($code_pharmacie, $limit, $offset);
    $dernierInventaire = $inventaire_inst->getLastInvByPharma($code_pharmacie);
    $inv_ligne = $inventaire_inst->getProdAlreadyInInventaire($code_pharmacie);
    
    if (empty($produits) && empty($dernierInventaire)) :
        messageServer('error', 'Aucun produit trouvé!');
    endif;
    
    $inventaireIndexe = [];
    foreach ($inv_ligne as $ligne) {
        $code = $ligne['code_produit'];
        // Ne garder que les champs utiles de l'inventaire
        $inventaireIndexe[$code] = [
            'quantite_actuelle' => $ligne['quantite_actuelle'] ?? null,
            'qte_boite_actuelle' => $ligne['qte_boite_actuelle'] ?? null,
            'difference_qte' => $ligne['difference_qte'] ?? null,
            'difference_qte_boite' => $ligne['difference_qte_boite'] ?? null,
            // 'date_inventaire' => $ligne['date_creation'] ?? $ligne['date'] ?? null
        ];
    }

    // Fusionner les données
    $data = array_map(function($produit) use ($inventaireIndexe) {
        $code = $produit['code'];
        
        if (isset($inventaireIndexe[$code])) {
            // Ajouter les données d'inventaire au produit
            return array_merge($produit, $inventaireIndexe[$code]);
        }
        
        // Pas d'inventaire, retourner le produit seul
        return $produit;
    }, $produits);

    $test = $inventaire_inst->getRapportInvent($dernierInventaire['code']);
    // testDebug($test);
        
    // testDebug('Keep on');
    echo json_encode([
        'status' => 'success',
        'message' => 'Produit trouvé avec succès',
        'data' => $data,
        'testProd' => $test,
        'dernierInventaire' => $dernierInventaire,
        'total' => $total,
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
