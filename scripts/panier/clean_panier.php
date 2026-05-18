<?php

try {
    if (isset($_SESSION['panier'])) {
        // testDebug('Keep on');
        $_SESSION['panier'] = [];
        messageServer('success','Panier vidé avec succès', $_SESSION['panier']);
    } else {
        messageServer('error','Le panier est vide');
    }
} catch (\Throwable $th) {
    messageServer('error', $th->getMessage());
}
