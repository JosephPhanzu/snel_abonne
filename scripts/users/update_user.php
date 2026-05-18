<?php

use App\Proprietaire;
use App\Employe;
require_once __DIR__ . '/../function.php';

try {

    $code_user = $session->getUserCode();
    $role = $session->getRole();
    $nom = isset($_POST['nom']) ? filter_input(INPUT_POST, securisation('nom'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $email = isset($_POST['email']) ? filter_input(INPUT_POST, securisation('email'), FILTER_SANITIZE_EMAIL) : '';
    $telephone = isset($_POST['telephone']) ? filter_input(INPUT_POST, securisation('telephone'), FILTER_SANITIZE_SPECIAL_CHARS) : '';

    if ($role === 'proprietaire') :
        $adresse = isset($_POST['adresse']) ? filter_input(INPUT_POST, securisation('adresse'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $prenom = isset($_POST['prenom']) ? filter_input(INPUT_POST, securisation('prenom'), FILTER_SANITIZE_SPECIAL_CHARS) : '';

        if (empty($prenom) && empty($adresse)) :
            messageServer('error', 'Tous les champs sont obligatoires!');
        endif;

    endif;

    if (!empty($nom) && !empty($telephone) && !empty($email)) :

        new Proprietaire();
        new Employe();

        if (empty($role === 'proprietaire' ? (new Proprietaire())->getByCode($code_user) : (new Employe())->getByCode($code_user))) :
            messageServer('error', 'L\'utilisateur n\'existe pas!');
        endif;

        if ($role === 'proprietaire' ? Proprietaire::updateInfo($code_user, $nom, $prenom, $email, $telephone, $adresse) : Employe::updateInfo($code_user, $nom, $email, $telephone)) :
            $userData = [
                'code' => $utilisateur_info['code'],
                'nom' => $nom,
                'prenom' => $prenom ?? null,
                'email' => $email,
                'role' => $role,
                'code_pharmacie' => null
            ];
            if ($session->updateUserInfo($userData)) :
                messageServer('success', 'Informations modifiées avec succès!');
            endif;
            messageServer('error', 'Problème lors de la mise à jour de la session!');
        endif;

        messageServer('error', 'Problème lors de la  modification!');
        
    endif;

    messageServer('error', 'Tous les champs sont obligatoires!');

} catch (\Exception $th) {
    messageServer('error', $th->getMessage());
}