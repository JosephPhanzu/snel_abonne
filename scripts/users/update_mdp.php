<?php

use App\Employeur;
use App\Agent;

require_once __DIR__ . '/../function.php';

try {

    $code_user = $session->getUserCode();
    $role = $session->getRole();
    $anc_mdp = isset($_POST['anc_mdp']) ? filter_input(INPUT_POST, securisation('anc_mdp'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $nouveau_mdp = isset($_POST['nouveau_mdp']) ? filter_input(INPUT_POST, securisation('nouveau_mdp'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $confirme_mdp = isset($_POST['confirme_mdp']) ? filter_input(INPUT_POST, securisation('confirme_mdp'), FILTER_SANITIZE_SPECIAL_CHARS) : '';
    
    new Employeur();
    new Agent();

    if (empty($role === 'Employeur' ? (new Employeur())->getByCode($code_user) : (new Agent())->getByCode($code_user))) :
        messageServer('error', 'L\'utilisateur n\'existe pas!');
    endif;

    if (empty($anc_mdp) || empty($nouveau_mdp) || empty($confirme_mdp)) :
        messageServer('error', 'Tous les champs sont obligatoires!');
    endif;

    if (strlen($nouveau_mdp) < 6) :
        messageServer('error', 'Le nouveau mot de passe doit contenir au moins 6 caractères!');
    endif;

    if ($nouveau_mdp !== $confirme_mdp) :
        messageServer('error', 'Les nouveaux mots de passe ne correspondent pas!');
    endif;

    if (!($role === 'Employeur' ? Employeur::verifyPassword($code_user, $anc_mdp, 'employeurs') : Agent::verifyPassword($code_user, $anc_mdp, 'agents'))) :
        messageServer('error', 'Votre ancien mot de passe est incorrect!');
    endif;

    if (($role === 'Employeur' ? Employeur::updatePassword($code_user, $nouveau_mdp, 'employeurs') : Agent::updatePassword($code_user, $nouveau_mdp, 'agents'))) :
        messageServer('success', 'Votre mot de passe a été modifié avec succès!');
    endif;

    messageServer('error', 'Tous les champs sont obligatoires!');

} catch (\Exception $th) {
    messageServer('error', $th->getMessage());
}