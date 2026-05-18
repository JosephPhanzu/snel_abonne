<?php

    try {
        $panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];

        $totalPanier = 0;
        
        // Calcule le total du panier
        foreach ($panier as $produit) :
            // testDebug($produit['prix_unitaire']);
            $totalPanier +=  $produit['prix_unitaire'] * $produit['quantite'];
            
        endforeach;

        if (!empty($panier)) {

            $countSession = count($panier);

            echo json_encode([
                'status' => 'success',
                'data' => $panier,
                'message' => 'Panier récupéré avec succès',
                'total' => round($totalPanier, 2),
                'totalProduits' => $countSession,
            ]);
        } else {
            messageServer('error', 'Le panier est vide.');
        }
    } catch (\Throwable $th) {
        messageServer('error',$th->getMessage());
    }

    
