<?php
session_start();
require_once __DIR__ .'/vendor/autoload.php';

use App\AuthentSession;
$session = AuthentSession::getInstance();

function testDebug($entre): never {
    echo json_encode(['message' => $entre]);
    exit;
}

function messageServer($statut, $message, $data = null, $total = null, $session = null): never {
    echo json_encode(['status' => $statut, 'message' => $message, 'data' => $data, 'total'=> $total, 'session', $session]);
    exit;
}

$requestUri = $_SERVER['REQUEST_URI'];

// Supprime les paramètres de requête, si présents
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// Table de routage (URI => fichier PHP à inclure)
$routesGetMethode = [

    // Route for views
    '/' => __DIR__ . '/views/login.html',
    '/login' => __DIR__ . '/views/login.html',
    '/register' => __DIR__ . '/views/register.html',
    '/dashboard' => __DIR__ . '/views/home/dashboard.php',
    '/abonnes'=> __DIR__ . '/views/abonne/abonne.php',
    '/consommation' => __DIR__ . '/views/conso/conso.php',
    '/factures' => __DIR__ . '/views/facture/facture.php',
    '/parametres' => __DIR__ . '/views/parametres/parametres.php',
    '/paiements' => __DIR__ . '/views/paiement/paiement.php',
    
    // Route for Datas
    '/deconnexion' => __DIR__ . '/scripts/deconnexion.php',
    '/get_conso' => __DIR__ . '/scripts/consommation/get_conso.php',
    '/get_abonnes' => __DIR__ . '/scripts/abonne/get_abonnes.php',
    '/get_facture' => __DIR__ . '/scripts/consommation/get_facture.php',

];

$routesPostMethod = [

    '/login' => __DIR__ . '/scripts/users/login.php',
    '/add_abonne' => __DIR__ . '/scripts/abonne/add_abonne.php',
    '/add_conso' => __DIR__ . '/scripts/consommation/add_conso.php',
    '/add_employe'=> __DIR__ . '/scripts/employe/add_employe.php',
    '/add_paiement' => __DIR__ . '/scripts/employe/add_paiement.php',
    '/add_produit'=> __DIR__ . '/scripts/produit/add_produit.php',

    '/delete'=> __DIR__ . '/scripts/GenFunc/delete.php',
];

$routesPutMethod = [
    '/update_doctor' => __DIR__ . '/scripts/medecin/update_medecin.php',
];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    if (array_key_exists($requestPath, $routesGetMethode)) {
        require $routesGetMethode[$requestPath];
    } else {
        http_response_code(404);
        echo "Page not found 404.";
    }   
}elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){

    if (array_key_exists($requestPath, $routesPostMethod)) {
        require $routesPostMethod[$requestPath];
    } else {
        http_response_code(404);
        echo "Scripts not found. Et non trouvé!";
    }
}elseif ($_SERVER['REQUEST_METHOD'] == 'PUT'){

    if (array_key_exists($requestPath, $routesPutMethod)) {
        require $routesPutMethod[$requestPath];
    } else {
        http_response_code(404);
        echo "Scripts not found. Et non trouvé!";
    }
}else{
    echo "Méthode non autorisé";
}
