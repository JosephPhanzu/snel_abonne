<?php

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $code_produit = $data['code_produit'] ?? null;

    if (isset($code_produit)) {

        $panier = $_SESSION['panier'] ?? [];

        if (isset($panier) && isset($panier[$code_produit])) {
            
            $produit_courant = $panier[$code_produit];
            
            $newQuantite = $produit_courant['quantite'] - 1;

            $_SESSION['panier'][$code_produit]['quantite'] = $newQuantite;

            if ($newQuantite <= 0) :
                unset($_SESSION['panier'][$code_produit]);
            endif;

            messageServer('success', 'Produit soustrait du panier');
        } else {
            messageServer('error','Produit non trouvé dans le panier');
        }
    } else {
        messageServer('error','Produit ID manquant');
    }
} catch (\Throwable $th) {
    messageServer('error', $th->getMessage());
}
