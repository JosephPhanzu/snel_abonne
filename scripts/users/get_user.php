<?php

use App\Proprietaire;

try {
        
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page -1) * $limit;
    
    if (!class_exists('App\Proprietaire')) {
        messageServer('error', 'Class Proprietaire not found');
    }

    new Proprietaire();
    
    $total_user = count(Proprietaire::getAll());

    $total_page = ceil($total_user / $limit);

    $user = Proprietaire::getPaginate($limit, $offset);
    if (empty($user)) {
        messageServer('error', 'Aucun user trouvé');
    }

    messageServer('success', 'user trouvé avec success', $user, $total_page);

} catch (\Throwable $th) {
    die('Erreur serveur à la recuperation de données'. $th->getMessage());
}