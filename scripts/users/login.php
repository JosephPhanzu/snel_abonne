<?php
    
    use App\Employeur;
    use App\Agent;
    // use App\Employeur;
    use App\Permission;
    
    require_once __DIR__ . '/../function.php';

    try {
        
        $email = strtolower(trim(securisation($_POST['email'] ?? '')));
        $mdp = securisation($_POST['mdp'] ?? '');
        // testDebug('Keep calm and code on!');
        if (isset($email) && isset($mdp)) {
            if (!empty($email) && !empty($mdp)) {
                if (preg_match('/(@cnss.org)$/i', $email)) :
                    new Employeur();
                    $permission = new Permission();

                    $loginStatus = Employeur::getLoginAttemptStatus($email, 'backoffice_login');
                    if ($loginStatus['locked']) {
                        $remainingTime = Employeur::formatRemainingLockTime($loginStatus['remaining_seconds']);
                        messageServer('error', 'Compte temporairement bloqué apres plusieurs tentatives. Reessayez dans ' . $remainingTime . '.');
                    }
                    
                    $agent_info = Agent::login($email, $mdp);
                    $employeur_info = Employeur::login($email, $mdp);

                    if (!empty($agent_info) || !empty($employeur_info)) :

                        $role = !empty($agent_info) ? 'Admin' : 'Employeur';
                        $utilisateur_info = !empty($agent_info) ? $agent_info : $employeur_info;

                        if (!($permission->getUser_per($utilisateur_info['code']))) :
                            messageServer('error', 'Vous n\'avez pas le droit de vous connecter veillez vous adresser au près de l\'administrateur!!');
                        endif;
                        
                        $userData = [
                            'code' => $utilisateur_info['code'],
                            'nom' => $utilisateur_info['nom'],
                            'email' => $utilisateur_info['email'] ?? null,
                            'role' => $role,
                        ];
                        $session->login($userData);
                        Employeur::clearLoginAttempt($email, 'backoffice_login');

                        $route = '/dashboard';

                        echo json_encode([
                            'status' => 'success',
                            'message' => 'Connexion réussie!!!',
                            'route' => $route
                        ]);
                        exit;
                    endif;

                    $loginStatus = Employeur::recordFailedLoginAttempt($email, 'backoffice_login');

                    if ($loginStatus['locked']) {
                        $remainingTime = Employeur::formatRemainingLockTime($loginStatus['remaining_seconds']);
                        messageServer('error', 'Trop de tentatives de connexion. Reessayez dans ' . $remainingTime . '.');
                    }

                    messageServer('error', 'Information incorrect. Tentatives restantes : ' . $loginStatus['remaining_attempts']);
                
                else :
                    messageServer('error', 'Format email incorrect');
                endif;
            }else{
                messageServer('error', 'Tous les champs sont obligatoires!');
            }
        }else{
            messageServer('error', 'Champs non existants!');

        }
} catch (\Throwable $th) {
    messageServer('error', 'Erreur serveur lors de la connexion: ' . $th->getMessage());
}
