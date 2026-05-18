<?php
    
    use App\Employeur;
    use App\Permission;
    require_once __DIR__ . '/../function.php';

    try {

        $nom = isset($_POST['nom']) ? filter_input(INPUT_POST, securisation('nom'), FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $email = isset($_POST['email']) ? filter_input(INPUT_POST, securisation('email'), FILTER_VALIDATE_EMAIL) : null;
        $telephone = isset($_POST['tel']) ? filter_input(INPUT_POST, securisation('tel'), FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $adresse = isset($_POST['adresse']) ? filter_input(INPUT_POST, securisation('adresse'), FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $statut = isset($_POST['statut']) ? filter_input(INPUT_POST, securisation('statut'), FILTER_SANITIZE_SPECIAL_CHARS) : "Actif";
        $mdp1 = isset($_POST['mdp1']) ? filter_input(INPUT_POST, securisation('mdp1'), FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $mdp2 = isset($_POST['mdp2']) ? filter_input(INPUT_POST, securisation('mdp2'), FILTER_SANITIZE_SPECIAL_CHARS) : null;

        if (!empty($nom) && !empty($adresse) && !empty($email) && !empty($telephone) && !empty($mdp1) && !empty($adresse)) {
            if (preg_match('/^[0-9]{10}$/', $telephone)) {
                if (preg_match('/(@cnss.org)$/i', $email)) :

                    if ($mdp1 !== $mdp2) :
                        messageServer('error','Les mot de  passe ne sont pas identique!');
                    endif;

                    new Employeur($nom, $adresse, $telephone, $email, $mdp1, $statut);
                    
                    if (Employeur::exist()) :
                        messageServer('error', 'Cet email est déjà utilisé par un autre compte!');
                    endif;
                    
                    if ($proprio = Employeur::add()) {
                        $code_user = $proprio['code'];
                        $permission = new Permission('Employeur',  $code_user, 1, 1);
                        if ($permission->add()) :
                            messageServer('success', 'Compte employeur créé avec success!');
                        endif;
                        messageServer('error', 'Problème lors de l\'enregistrement permission!');
                    } else {
                        messageServer('error', 'Erreur lors de l\'enregistrement!');
                    }
                        
                else :
                    messageServer('error', 'Format email incorrect');
                endif;
                
            }else{
                messageServer('error', 'Le numéro de téléphone doit contenir de chiffre et ne dépasse pas 10 caractère!');
            }
        }else{
            messageServer('error', 'Veuillez remplir tous les champs obligatoires!');
        }
    } catch (\Throwable $th) {
        throw $th;
    }
