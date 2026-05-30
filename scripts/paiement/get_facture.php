<?php

use App\Consommation;

try {

    $role = $session->getRole();

    $code_abonne = $role == 'abonne' ? $session->getUserCode() : null;
    
    $conso_inst = new Consommation();
    
    $Consommation = $role == 'agent' ? $conso_inst->getFactureJoinConsoJoinAbonne() : $conso_inst->getFacJoinConsoJoinAbByAb($code_abonne);
    
    if (empty($Consommation)) {
        messageServer("error", "Aucune facture trouvé");
    }

    messageServer('success','Facture trouvé avec succès', $Consommation);

} catch (\Throwable $th) {
    die('Erreur serveur à la recuperation de données'. $th->getMessage());
}