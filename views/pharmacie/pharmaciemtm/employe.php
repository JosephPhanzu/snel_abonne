<?php

use App\Token_csrf;
$token_csrf = Token_csrf::gererateTokenCsrf();

if ($_SESSION['bi-pharma']['role'] !== 'proprietaire') :
    session_unset();
    session_destroy();
    header('location: /login');
    exit;
endif;

$title = "Gestion employé | bi-pharma";
ob_start();

?>

    <div class="col-xl-12 row justify-content-around py-5 px-2 bg-white">

        <form class="col-12 col-lg-4 px-2 py-3" data-title="employe" id="add-pharmaciemtm">
            <div class="h5">Ajout un employé</div>

            <input type="hidden" value="<?= $token_csrf ?>" name="csrf">
            <input type="hidden" name="code_pharmacie" id="code_pharmacie" value="<?= $_GET['a'] ?>">
            <div class="form-group my-3">
                <input type="text" autocomplete="off" name="nom" id="nom" placeholder="Nom" class="form-control shadow-none rounded-3 border " require>
            </div>
            <div class="form-group my-3">
                <input type="text" autocomplete="off" name="prenom" id="prenom" placeholder="Prénom" class="form-control shadow-none rounded-3 border " require>
            </div>
            <div class="form-group my-3">
                <input type="tel" maxlength="10" autocomplete="off" name="telephone" id="telephone" placeholder="Téléphone" class="form-control shadow-none rounded-3 border " require>
            </div>
            
            <div class="form-group my-3">
                <input type="password" autocomplete="off" name="mdp" id="mdp" placeholder="Mot de passe" class="form-control shadow-none rounded-3 border " require>
            </div>
            
            <input type="hidden" name="registerType" value="gerant">
            <div class="btn-block my-3 text-white">
                <button class="col-12 btn btn-primary rounded-3 shadow-none" id="btn-save">
                    + Ajouter
                </button>
            </div>
            <div class="my-2 alert d-none alert-dismissible rounded-3" role="alert" id="info">
                <span id="info-text"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </form>

        <div class="col-12 col-lg-7 border rounded-3 px-2 py-3">
            <div class="h5">Liste de employe</div>
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>N°</th>
                        <th>Nom</th>
                        <th>prénom</th>
                        <th>Tél</th>
                        <th>Actions</th>
                    </thead>
                    <tbody class="tbody" id="table-first">

                    </tbody>
                </table>
            </div>
        </div>

    </div>
<script src="/assets/js/scripts.js"></script>

<?php

$content = ob_get_clean();
require_once __DIR__ . '/../../templete_app/main_templete.php';     