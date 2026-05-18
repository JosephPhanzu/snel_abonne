<?php

use App\GenFunc;

try {
    
    $table = isset( $_POST["table"] ) ? $_POST["table"] : "";
    $code = isset( $_POST["code"] ) ? $_POST["code"] : "";

    $genFunt_inst = new GenFunc();
    if (empty($genFunt_inst->findOne($code, $table))) :
        messageServer('error', 'L\'élément choisi n\'existe pas dans la table');
    endif;
    
    if ($genFunt_inst->deleteOne($code, $table)) :
        messageServer('success', 'L\'élément supprimé avec succèss');
    endif;
    messageServer('error','Problème lors de la suppression');

} catch (\Throwable $th) {
    messageServer('error', $th->getMessage());
}