<?php

use App\Produit;
use App\Marge;

try {
    $produit_inst = new Produit();
    $marge_inst = new Marge();
    
    $code_produit = $_POST['code_produit'] ?? null;
    $type_qte = $_POST['type_qte'] ?? null;
    $code_pharmacie = $_POST['code_pharmacie'];
    $quantite = $_POST['quantite'] ?? 1;
    $demi_plaquette = isset($_POST['demi_plaquette']) && $_POST['demi_plaquette'] == 1 ? $_POST['demi_plaquette'] : 0; // ou bool

    // testDebug($demi_plaquette);

    if (empty($marges = $marge_inst->getByCode($code_pharmacie))) :
        messageServer('error', 'Aucune marge trouvée pour cette pharmacie!');
    endif;

    if (!$code_produit) {
        messageServer('error', 'Paramètres invalides.');
    }

    // Charger ou initialiser le panier
    $panier = $_SESSION['panier'] ?? [];

    $getProduit = $produit_inst->getByCode($code_produit);

    if (!$getProduit) {
        messageServer('error', 'Produit non trouvé!');
    }

    $prix_achat = $getProduit['prix_achat'];
    $marge = $getProduit['marge'] === null || $getProduit['marge'] === 0 ? $marges['marge'] : $getProduit['marge'];

    if ($type_qte === "boite") :
        $quantite = $quantite * $getProduit['qte_par_boite'];     
        $getProduit['qte_boite'] ?? exit;
    else:
        if ($demi_plaquette == 1) {
            $quantite = $quantite * 0.5;
        }
    endif;

    // Calculer le prix de vente unitaire avec marge
    $prix_vente_unitaire = $getProduit['prix_achat_unitaire'] * (1 + ($marge / 100));

    // Vérifier si le produit est déjà dans le panier
    if (isset($panier[$code_produit])) {
        $panier[$code_produit]['quantite'] += $quantite;
    } else {
        // Ajouter dans le panier
        $produit = [
            'code' => $code_produit,
            'nom' => $getProduit['nom'],
            'type_qte' => $type_qte, 
            'demi_plaquette' => $demi_plaquette,
            'quantite' => $quantite,
            'prix_unitaire' => round($prix_vente_unitaire, 2),
            'qte_par_boite' => $type_qte === "boite" ? $getProduit['qte_par_boite'] : null,
        ];
        $panier[$code_produit] = $produit;
    }

    $_SESSION['panier'] = $panier;
    $countSession = count($panier);

    echo json_encode([
        'status' => 'success',
        'message' => 'Produit ajouté au panier.',
        'data' => $panier,
        'totalProduits' => $countSession,
        'quantite' => $panier[$code_produit]['quantite'],
    ]);

} catch (\Throwable $th) {
    messageServer('error', $th->getMessage());
}
