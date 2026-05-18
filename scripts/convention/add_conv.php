<?php
use App\Convention;
require_once __DIR__ . '/../function.php';

try {

    $nom = isset($_POST['nom']) ? filter_input(INPUT_POST, securisation('nom'), FILTER_SANITIZE_SPECIAL_CHARS) : null;
    $code_pharmacie = isset($_POST['code_pharmacie']) ? filter_input(INPUT_POST, securisation('code_pharmacie'), FILTER_SANITIZE_SPECIAL_CHARS) : "";
    $description = isset($_POST['description']) ? filter_input(INPUT_POST, securisation('description'), FILTER_SANITIZE_SPECIAL_CHARS) : "";

    // $role = $session->getRole();

    // $code_proprio = $session->getUserCode();
    // testDebug($code_pharmacie);
    $csrf = isset($_POST['csrf']) ? $_POST['csrf'] : null;

    if ($csrf == null && $csrf !== $_SESSION['csrf']) :
        messageServer('error','Invalid Token');
    endif;
    
    if (!empty($nom) && !empty($code_pharmacie) && !empty($description)) :

        $conv_inst = new Convention($nom, $code_pharmacie, $description);

        if ($conv_inst->exist()) :
            messageServer('error','La Convention renseigné existe déjà');
        endif;
        
        if ($conv_inst->add()) :
            messageServer('success','Convention ajouté avec success!');
        endif;

        messageServer('error','Erreur lors de l\'insertion du Convention');

    endif;

    messageServer('error','Veuillez renseiger tout le champs obligatoire');

} catch (\Throwable $th) {
    die('Erreur Serveur'. $th->getMessage());
}