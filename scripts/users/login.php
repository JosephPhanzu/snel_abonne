<?php
    
    use App\Abonne;
    use App\Agent;
    use App\Permission;
    
    require_once __DIR__ . '/../function.php';

    try {
        
        $email = strtolower(trim(securisation($_POST['email'] ?? '')));
        $mdp = securisation($_POST['mdp'] ?? '');
        
        if (isset($email) && isset($mdp)) {
            if (!empty($email) && !empty($mdp)) {
                if (preg_match('/(@snel.org.cd)$/i', $email)) :
                    new Abonne();
                    $permission = new Permission();

                    $loginStatus = Abonne::getLoginAttemptStatus($email, 'backoffice_login');
                    if ($loginStatus['locked']) {
                        $remainingTime = Abonne::formatRemainingLockTime($loginStatus['remaining_seconds']);
                        messageServer('error', 'Compte temporairement bloqué apres plusieurs tentatives. Reessayez dans ' . $remainingTime . '.');
                    }
                    
                    $agent_info = Agent::login($email, $mdp);
                    $abonne_info = Abonne::login($email, $mdp);

                    if (!empty($agent_info) || !empty($abonne_info)) :

                        $role = !empty($agent_info) ? 'agent' : 'abonne';
                        $utilisateur_info = !empty($agent_info) ? $agent_info : $abonne_info;

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
                        Abonne::clearLoginAttempt($email, 'backoffice_login');

                        $route = '/dashboard';

                        echo json_encode([
                            'status' => 'success',
                            'message' => 'Connexion réussie!!!',
                            'route' => $route
                        ]);
                        exit;
                    endif;

                    $loginStatus = Abonne::recordFailedLoginAttempt($email, 'backoffice_login');

                    if ($loginStatus['locked']) {
                        $remainingTime = Abonne::formatRemainingLockTime($loginStatus['remaining_seconds']);
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
