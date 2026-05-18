<?php

use App\Permission;
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
    
    if ($permission->updatePermission($column, $params, $data)) :
        if ($table === 'proprietaire') :
            $pharmacie = new Pharmacie();
            // testDebug('ici');
            if (empty($pharma_info = $pharmacie->getAllJoinProprio($code))) :
                messageServer('error', 'Aucune pharmacie associée à ce propriétaire!');
            endif;
    
            foreach ($pharma_info as $pharma) :
                $permission->updatePermission($column, $params, [!$value, $pharma['code_pharmacie']]);
            endforeach;
        endif;
        messageServer('success', 'permission pour ce '.$table.' moifié avec succès!');
    endif;

    messageServer('error', 'Problème lors de l\'update permission '.$table);

} catch (\Exception $th) {
    messageServer('error', $th->getMessage());
}