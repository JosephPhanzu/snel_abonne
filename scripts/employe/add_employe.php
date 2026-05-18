<?php
    
    use App\Employe;
    use App\Employeur;
    use App\Cotisation;
    
    require_once __DIR__ . '/../function.php';
    
    try {

        $code_employeur = isset($_POST['code_employeur']) ? securisation(filter_input(INPUT_POST, 'code_employeur', FILTER_SANITIZE_SPECIAL_CHARS)) : null;
        
        $date_embauche = isset($_POST['date_embauche']) ? securisation(filter_input(INPUT_POST, 'date_embauche', FILTER_SANITIZE_SPECIAL_CHARS)) : null;
        $telephone = isset($_POST['tel']) ? securisation(filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_SPECIAL_CHARS)) : null;
        $sexe = isset($_POST['sexe']) ? securisation(filter_input(INPUT_POST, 'sexe', FILTER_SANITIZE_SPECIAL_CHARS)) : null;
        $statut = isset($_POST['statut']) ? securisation(filter_input(INPUT_POST, 'statut', FILTER_SANITIZE_SPECIAL_CHARS)) : null;
        $nom = isset($_POST['nom']) ? securisation( filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS)) : null;
        $prenom = isset($_POST['prenom']) ? securisation(filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS)) : null;
        $salaire = isset($_POST['salaire']) ? securisation(filter_input(INPUT_POST, 'salaire', FILTER_SANITIZE_SPECIAL_CHARS)) : null;        
        
        $matricule = isset($_POST['matricule']) ? securisation(filter_input(INPUT_POST, 'matricule', FILTER_SANITIZE_SPECIAL_CHARS)) : null;
        
        $valid_payment = isset($_POST['valid_payment']) ? securisation(filter_input(INPUT_POST, 'valid_payment', FILTER_SANITIZE_SPECIAL_CHARS)) : null;
        // testDebug($valid_payment);
        
        if (!empty($nom) && !empty($prenom) && !empty($telephone) && !empty($salaire) && !empty($date_embauche) && !empty($code_employeur) && !empty($matricule) && !empty($sexe) && !empty($valid_payment)) {
            if (preg_match('/^[0-9]{10}$/', $telephone)) :
                
                new Employeur();
                
                if (empty(Employeur::getByCode($code_employeur))) :
                    messageServer('error', 'Non autorisé!');
                    $session->logout();
                    header('Location: /');
                endif;
                $montant_cotisation = $salaire * 0.15;
                $statut = $statut ?? 'Actif';
                new Employe($code_employeur, $nom, $prenom, $salaire, $montant_cotisation, $date_embauche, $telephone, $sexe, $matricule, $statut);
                
                if (Employe::exist()) :
                    messageServer('error', 'Cet employé avec ce matricule existe déjà!');
                endif;

                if ($valid_payment === 'true') :
                        
                    $date_paiement = date('Y-m-d');
                    $cotisation_statut = 'Payé';
                    $numero_paiement = isset($_POST['numero_paiement']) ? securisation(filter_input(INPUT_POST, 'numero_paiement', FILTER_SANITIZE_SPECIAL_CHARS)) : null;
                    $nom_tit_carte = isset($_POST['nom_tit_carte']) ? securisation(filter_input(INPUT_POST, 'nom_tit_carte', FILTER_SANITIZE_SPECIAL_CHARS)) : null;
                    $expiry = isset($_POST['expiry']) ? securisation(filter_input(INPUT_POST, 'expiry', FILTER_SANITIZE_SPECIAL_CHARS)) : null;
                    $cvv = isset($_POST['cvv']) ? securisation(filter_input(INPUT_POST, 'cvv', FILTER_SANITIZE_SPECIAL_CHARS)) : null;
                    // testDebug($cvv);
                    if (!empty($numero_paiement) && !empty($nom_tit_carte) && !empty($expiry) && !empty($cvv)) :
                        // Validation expiry en regex
                        if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiry)) :
                            messageServer('error', 'Format de date d\'expiration invalide, respecter le format MM/AA!');
                        endif;

                        // Validation numero de carte en regex
                        if (!preg_match('/^[0-9]{4} [0-9]{4} [0-9]{4} [0-9]{4}$/', $numero_paiement)) :
                            messageServer('error', 'Format de numéro de carte invalide!');
                        endif;

                        // Validation CVV en regex
                        if (!preg_match('/^[0-9]{3}$/', $cvv)) :
                            messageServer('error', 'Format de CVV invalide!');
                        endif;
                    else :
                        messageServer('error', 'Veuillez remplir tous les champs du paiement!');
                    endif;

                else :
                    $cotisation_statut = 'En attente';
                    $date_paiement = null;
                    $numero_paiement = null;
                    $nom_tit_carte = null;
                    
                endif;
                
                if ($employe = Employe::add()) {
                    
                    $code_employe = $employe['code'];
                                        
                    $cotisation = new Cotisation($code_employe, $montant_cotisation, $date_paiement, $cotisation_statut, $numero_paiement, $nom_tit_carte);
                
                    if ($cotisation->add()) :
                        messageServer('success', 'Employé ajouté avec succès!');
                    endif;
                    messageServer('error', 'Erreur lors de l\'enregistrement de la cotisation!');
                    
                } else {
                    messageServer('error', 'Erreur lors de l\'enregistrement employé!');
                }
                
            else :
                messageServer('error', 'Le numéro de téléphone doit contenir de chiffre et ne dépasse pas 10 caractère!');
            endif;
        }else{
            messageServer('error', 'Veuillez remplir tous les champs obligatoires!');
        }
    } catch (\Throwable $th) {
        throw $th;
    }
