<?php

use App\Employeur;
use App\Agent;
require_once __DIR__ . '/../function.php';

try {

    $code_user = $session->getUserCode();
    $role = $session->getRole();

    $nom = isset($_POST['nom']) ? filter_input(INPUT_POST, securisation('nom'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $email = isset($_POST['email']) ? filter_input(INPUT_POST, securisation('email'), FILTER_SANITIZE_EMAIL) : '';
    $telephone = isset($_POST['telephone']) ? filter_input(INPUT_POST, securisation('telephone'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $adresse = isset($_POST['adresse']) ? filter_input(INPUT_POST, securisation('adresse'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $prenom = isset($_POST['prenom']) ? filter_input(INPUT_POST, securisation('prenom'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $code_employeur = isset($_POST['code_employeur']) ? filter_input(INPUT_POST, securisation('code_employeur'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $statut = isset($_POST['statut']) ? filter_input(INPUT_POST, securisation('statut'), FILTER_SANITIZE_SPECIAL_CHARS) : '';

    if ($role !== 'Admin' && !empty(Agent::getByCode($code_user))) :
        messageServer('error', 'Accès refusé!');
    endif;

    if (!empty($nom) && !empty($telephone) && !empty($email) && !empty($adresse) && !empty($code_user) ) :

        new Employeur();

        if (empty( Employeur::getByCode($code_employeur))) :
            messageServer('error', 'L\'employeur n\'existe pas!');
        endif;

        if (Employeur::updateInfo($code_employeur, $nom, $adresse, $email, $telephone, $statut)) :
            messageServer('success', 'Informations modifiées avec succès!');
        endif;

        messageServer('error', 'Problème lors de la  modification!');
        
    endif;

    messageServer('error', 'Tous les champs sont obligatoires!');

} catch (\Exception $th) {
    messageServer('error', $th->getMessage());
}