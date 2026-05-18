<?php

    try {

        $session->logout();
        header('Location: /login');
   
    } catch (\Throwable $th) {
        echo json_encode([
            'error' => true,
            'status' => 'error',
            'message' => 'Erreur serveur : ' . $th->getMessage()
        ]);
    }
