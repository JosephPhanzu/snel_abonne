<?php
use App\Paiement;
use App\Facture;
require_once __DIR__ . '/../function.php';

try {

    $info = '';

    $date = isset($_POST['date']) ? filter_input(INPUT_POST, securisation('date'), FILTER_SANITIZE_SPECIAL_CHARS) : null;

    $code_facture = isset($_POST['code_facture']) ? filter_input(INPUT_POST, securisation('code_facture'), FILTER_SANITIZE_SPECIAL_CHARS) : null;
    
    $montant = isset($_POST['montant']) ? filter_input(INPUT_POST, securisation('montant'), FILTER_SANITIZE_SPECIAL_CHARS) : null;

    $methode = isset($_POST['methode']) ? filter_input(INPUT_POST, securisation('methode'), FILTER_SANITIZE_SPECIAL_CHARS) : null;

    if (!empty($methode)) :
        if ($methode === 'mobile') :
            $fournisseur = isset($_POST['fournisseur']) ? filter_input(INPUT_POST, securisation('fournisseur'), FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $telephone = isset($_POST['telephone']) ? filter_input(INPUT_POST, securisation('telephone'), FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $info = $fournisseur.' '.$telephone;
            if (!empty($fournisseur) && !empty($telephone)) :
                if (!preg_match('/^[0-9]{10}$/', $telephone)) :
                    messageServer('error', 'Le numéro de téléphone doit contenir de chiffre et ne dépasse pas 10 caractère!');
                endif;
            else :
                messageServer('error', 'Veuillez remplir tous les champs obligatoires pour le paiement mobile!');
            endif;
                
        else :
            $cardType = isset($_POST['carteType']) ? filter_input(INPUT_POST, securisation('carteType'), FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $titulaire = isset($_POST['titulaire']) ? filter_input(INPUT_POST, securisation('titulaire'), FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $last4 = isset($_POST['last4']) ? filter_input(INPUT_POST, securisation('last4'), FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $info = $cardType.' '.$titulaire.' '.$last4;
            if (!empty($cardType) && !empty($titulaire) && !empty($last4)) :
                if (!preg_match('/^[0-9]{4}$/', $last4)) :
                    messageServer('error', 'Les 4 derniers chiffres de la carte doivent contenir de chiffre et ne dépasse pas 4 caractère!');
                endif;
            else :
                messageServer('error', 'Veuillez remplir tous les champs obligatoires pour le paiement par carte!');
            endif;
        endif;
    endif;

    $facture_inst = new Facture();

    if (empty($facture_inst->getByCode($code_facture))) {
        messageServer('error', 'Facture non trouvée');
    }
    // testDebug($info);
    if (!empty($date) && !empty($montant) && !empty($code_facture)) :

        $paiement_inst = new Paiement($code_facture, $montant, 'Payée', $date, $methode, $info);

        if ($paiement_inst->exist()) :
            messageServer('error','Le Paiement renseigné existe déjà');
        endif;
        
        if ($info_paiement = $paiement_inst->add()) :

            if ($facture_inst->updateStatut($code_facture, 'Payée')) :
                messageServer('success','Paiement effectué avec succès!');
            endif;

            messageServer('error','Erreur lors de la mise à jour de la facture');

        endif;

        messageServer('error','Erreur lors du Paiement');

    endif;

    messageServer('error','Veuillez renseiger tout le champs obligatoire');

} catch (\Throwable $th) {
    die('Erreur Serveur'. $th->getMessage());
}