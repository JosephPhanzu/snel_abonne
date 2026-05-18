<?php

use App\Inventaire;
use App\Pharmacie;
require_once __DIR__ . '/../function.php';

try {

    $code_pharmacie = isset($_POST['code_pharmacie']) ? filter_input(INPUT_POST, securisation('code_pharmacie'), FILTER_SANITIZE_SPECIAL_CHARS) : "";
    $action = isset($_POST['action']) ? filter_input(INPUT_POST, securisation('action'), FILTER_SANITIZE_SPECIAL_CHARS) : "";

    if ($code_pharmacie == "") :
        messageServer('error','Veuillez renseiger tout le champs obligatoire');
    endif;
    
    $role = $session->getRole();
    $code_proprio = $session->getUserCode();

    $pharmacie_inst = new Pharmacie();

    if ($action === 'Terminer') :
        $inventaire_inst = new Inventaire();


        if (empty($info = $inventaire_inst->getActivedInventaireByPharma($code_pharmacie))) :
            messageServer('error', 'Aucun inventaire en cours trouvé pour cette pharmacie!');
        endif;

        if ($inventaire_inst->updateOne('date_fin = ?, statut = ?', 'code_pharmacie = ? AND statut = ?', [date('Y-m-d'), 'termine', $code_pharmacie, 'en_cours'])) :

            if ($inventaire_inst->deleteInvLigneDifNull($info['code'])) :
                messageServer('success','Inventaire arrêté avec success!');
            endif;
        endif;
        messageServer('error','Erreur lors de l\'arrêt de l\'inventaire');
    endif;

    $inventaire_inst = new Inventaire($code_pharmacie, date('Y-m-d'), null, 'en_cours');
    
    if (empty($pharmacie_inst->findOne($code_pharmacie))) :
        messageServer('error', 'La pharmacie choisi n\'existe pas!');
    endif;
    
    if ($inventaire_inst->exist()) :
        messageServer('error', 'Un inventaire est déjà en cours pour cette pharmacie!');
    endif;
    
    if ($inventaire_inst->add()) :
        messageServer('success','Inventaire démarré avec success!');
    endif;
    
    messageServer('error','Erreur lors du démarrage de l\'inventaire');

} catch (\Throwable $th) {
    die('Erreur Serveur'. $th->getMessage());
}