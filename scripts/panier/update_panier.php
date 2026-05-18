<?php

use App\Produit;

try {

    $produit_ist = new Produit("", "", "", "", "", "", "");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données de la requête
        $data = json_decode(file_get_contents('php://input'), true);
    
        // Vérifier que les paramètres nécessaires sont fournis
        if (!isset($data['code_produit'], $data['quantite'])) {
            echo json_encode(['status' => 'error', 'message' => 'Paramètres manquants.']);
            exit;
        }
    
        $code_produit = $data['code_produit'];
        $quantite = (int)$data['quantite'];
    
        // Vérifier que la quantité est valide
        if ($quantite < 1) {
            echo json_encode(['status' => 'error', 'message' => 'Quantité invalide.']);
            exit;
        }

        $produit = $produit_ist->getByCode($code_produit);

        if (!$produit) {
            echo json_encode(['status' => 'error', 'message' => 'Produit non trouvé!']);
            exit;
        }

        if($produit['quantite'] < $quantite){
            echo json_encode(['status' => 'error', 'message' => 'Stock insuffisant!']);
            exit;
        }
    
        // Vérifier si le produit existe dans le panier
        if (isset($_SESSION['panier'][$code_produit])) {
            // Mettre à jour la quantité du produit
            $_SESSION['panier'][$code_produit]['quantite'] = $quantite;
            
    
            echo json_encode([
                'status' => 'success',
                'message' => 'Quantité mise à jour avec succès.',
                'produit' => $_SESSION['panier'][$code_produit],
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Produit introuvable dans le panier.']);
        }
        exit;
    }    
} catch (\Throwable $th) {
    echo json_encode([
        'error' => true,
        'status' => 'error',
        'message' => 'Erreur serveur : ' . $e->getMessage()
    ]);
}

