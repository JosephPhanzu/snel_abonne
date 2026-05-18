<?php

use App\Employe;
use App\Employeur;

try {
        
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page -1) * $limit;

    $code_employeur_get = isset($_GET['code_employeur']) ? filter_input(INPUT_GET, 'code_employeur', FILTER_SANITIZE_SPECIAL_CHARS) : null;
    $role = $session->getRole();

    $code_employeur = $role === 'Employeur' ? $session->getUserCode() : $code_employeur_get;

    if ($code_employeur === "" || $code_employeur === null) : 
        messageServer("error", "Code employeur non valid");
    endif;

    $Employeur_inst = new Employeur();

    if (empty($Employeur_inst->getByCode($code_employeur))) :
        messageServer('error', 'L\'Employeur choisi n\'existe pas!');
    endif;
    
    new Employe();
    
    $employe = Employe::getByEmployeur($code_employeur, $limit, $offset);
    // testDebug($code_employeur);
    $total_employe = Employe::getAllByEmployeur($code_employeur);
    
    if (empty($employe)) {
        messageServer("error", "Aucun employé trouvé");
    }

    messageServer('success','Employé trouvé avec succès', $employe, $total_employe);

} catch (\Throwable $th) {
    die('Erreur serveur à la recuperation de données'. $th->getMessage());
}