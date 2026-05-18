<?php

use App\Proprietaire;

try {

    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page -1) * $limit;
    
    new Proprietaire();
        
    $partenaire = Proprietaire::getProprio_valide($limit, $offset);
    $total_partenaire = count(Proprietaire::getAll());

    if (empty($partenaire)) {
        messageServer("error", "Aucun partenaire trouvé!");
    }

    messageServer(statut: 'success', message: 'partenaire trouvé avec success', data: $partenaire, total: $total_partenaire );

} catch (\Throwable $th) {
    die('Erreur serveur à la recuperation de données'. $th->getMessage());
}