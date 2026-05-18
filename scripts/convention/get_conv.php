<?php

use App\Convention;
use App\Pharmacie;

try {

    $code_pharma = isset($_GET['code_pharmacie']) ? $_GET['code_pharmacie'] : $session->getUserCode();
    $role = $session->getRole();

    if (isset($_GET['code_pharmacie']) && $role !== "admin") {
        // Vérifier si la pharmacie existe
        $pharmacie_inst = new Pharmacie();
        $pharmacie_info = $pharmacie_inst->getByCode($code_pharma);
        if (empty($pharmacie_info)) {
            messageServer("error", "La pharmacie spécifiée n'existe pas!");
        }
    }
    $convention_inst = new Convention();
    $convention = $convention_inst->getByCodePharma($code_pharma);
    $total_convention = count($convention);
    
    if (empty($convention)) {
        messageServer("error", "Aucune convention trouvée!");
    }

    messageServer(statut: 'success', message: 'convention trouvée avec success', data: $convention, total: $total_convention);

} catch (\Throwable $th) {
    die('Erreur serveur à la recuperation de données'. $th->getMessage());
}