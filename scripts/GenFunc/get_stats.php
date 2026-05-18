<?php

use App\Employe;
use App\Employeur;
use App\Cotisation;
use App\Bordereau;

try {

    $role = $session->getRole();
    
    $cotisation_inst = new Cotisation();
    $bordereau_inst = new Bordereau();
    // testDebug('Keep calm and code on!');
    switch ($role) :
        case 'Admin':
            $stats = [
                'total_employeurs' => Employeur::all('employeurs'),
                'total_employes' => Employe::all('employes'),
                'total_cotisations' => $cotisation_inst->all('cotisations'),
                'total_bordereaux' => $bordereau_inst->all('bordereaux')
            ];
            break;
        
        case 'Employeur':
            $code_employeur = $session->getUserCode();
            // testDebug($code_employeur);
            $stats = [
                'total_employes' => Employe::getAllByEmployeur($code_employeur),
                'total_cotisations' => $cotisation_inst->totalCotPaye($code_employeur),
                'total_bordereaux' => $bordereau_inst->getTotalByEmployeur($code_employeur)
            ];
            break;

        default:
            messageServer('error', 'Rôle non reconnu!');
    endswitch;

    messageServer('success','Statistiques trouvées avec succès', $stats);

} catch (\Throwable $th) {
    die('Erreur serveur à la recuperation de données'. $th->getMessage());
}