<?php

use App\Employeur;
require_once __DIR__ . '/../function.php';
try {
        
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page -1) * $limit;

    new Employeur();

    $all = Employeur::getAll();
    
    $Employeur = Employeur::getPaginate($limit, $offset);

    // $total_Employeur = count($all);
    
    if (empty($Employeur)) {
        messageServer("error", "Aucun Employeur trouvé");
    }

    messageServer('success','Employeur touvés avec succèss', $Employeur, $all);

} catch (\Throwable $th) {
    die('Erreur serveur à la recuperation de données'. $th->getMessage());
}