<?php

use App\Permission;
use App\Employe;
use App\Proprietaire;
use App\Pharmacie;
require_once __DIR__ . '/../function.php';

try {
    $code = isset($_POST['code']) ? filter_input(INPUT_POST, securisation('code'), FILTER_SANITIZE_SPECIAL_CHARS) : "";
    $table = isset($_POST['table']) ? securisation($_POST['table']) : null;
    $column = isset($_POST['column']) ? securisation($_POST['column']) : null;
    $value = isset($_POST['value']) ? securisation($_POST['value']) : null;
    $permission = new Permission();

    // if(!($permission->getUser_per($code))) :
    //     messageServer('error', 'Le permission n\'existe pas pour l\'utilisateur!');
    // endif;

    $column = $column . ' = ?';
    $params = 'code_user = ?';

    $data = [!$value, $code];

    if ($permission->sauvegarderActuelle($code_user, $table, $type, $gerer_pharma, $gerer_vendeur)) :
        messageServer('success', 'permission pour ce '.$table.' moifié avec succès!');
    endif;

    messageServer('error', 'Problème lors de l\'update permission '.$table);

} catch (\Exception $th) {
    messageServer('error', $th->getMessage());
}