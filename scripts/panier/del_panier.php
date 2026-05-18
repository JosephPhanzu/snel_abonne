<?php

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $code_produit = $data['code_produit'] ?? null;

    if (isset($code_produit)) {

        if (isset($_SESSION['panier']) && isset($_SESSION['panier'][$code_produit])) {
            unset($_SESSION['panier'][$code_produit]);

            echo json_encode([
                'status' => 'success',
                'message' => 'Produit retiré du panier',
                'data' => $_SESSION['panier']
            ]);
        } else {

            echo json_encode([
                'status' => 'error',
                'message' => 'Produit non trouvé dans le panier'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Produit ID manquant'
        ]);
    }
} catch (\Throwable $th) {
    messageServer('error', $th->getMessage());
}
