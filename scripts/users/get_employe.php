<?php

use App\Employe;
require_once __DIR__ . '/../function.php';
try {
        
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page -1) * $limit;
    testDebug('Hello');
    $code_pharmacie = isset($_GET['code_pharmacie']) ? filter_input(INPUT_GET, securisation('code_pharmacie'), FILTER_SANITIZE_SPECIAL_CHARS) : null;

    if ($code_pharmacie === "" || $code_pharmacie === null) : 
        messageServer("error", "Code agence non valid");
    endif;

    new Employe();
    
    $employe = Employe::getByPharma($code_pharmacie);

    $total_employe = count($employe);
    
    if (empty($employe)) {
        messageServer("error", "Aucun employe trouvé");
    }

    messageServer('success','Employé touvé avec succèss', $employe, $total_employe);

} catch (\Throwable $th) {
    die('Erreur serveur à la recuperation de données'. $th->getMessage());
}