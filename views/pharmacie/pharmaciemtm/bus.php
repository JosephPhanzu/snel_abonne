<?php

use App\Token_csrf;
use App\Agence;

$token_csrf = Token_csrf::gererateTokenCsrf();
// $exist_agence = (new Agence())->findOne($_GET['a']);

$title = "Gestion Bus | Bitrans";
ob_start();

?>
<div class="col-xl-12 row justify-content-around py-5 px-2 bg-white">

    <form class="col-12 col-lg-4 px-2 py-3" data-title="bus" id="add-agencemtm">
        <div class="h5">Ajout un Bus</div>

        <input type="hidden" value="<?= $token_csrf ?>" name="csrf">
        <input type="hidden" name="code_agence" id="code_agence" value="<?= $_GET['a'] ?>">
        <div class="form-group my-3">
            <input type="text" autocomplete="off" name="numero" id="numero" placeholder="Numéro bus" class="form-control shadow-none rounded-3 border " require>
        </div>

        <div class="form-group my-3">
            <input type="number" autocomplete="off" name="nbr_place" id="nbr_place" placeholder="Nombre de place" class="form-control shadow-none rounded-3 border " require>
        </div>

        <div class="form-group my-3">
            <input type="file" id="img" name="img" class="form-control shadow-none  text-secondary" placeholder="Votre nom" require>
        </div>
    
        <div class="btn-block my-3 text-white">
            <button class="col-12 btn btn-primary rounded-3 shadow-none" id="btn-save">
                + Ajouter
            </button>
        </div>
        <div class="my-2 alert d-none alert-dismissible rounded-3" role="alert" id="info-bus">
            <span id="info-text"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </form>

    <div class="col-12 col-lg-7 border rounded-3 px-2 py-3">
        <div class="h5">Liste de gérant</div>
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead>
                    <th>N°</th>
                    <th>Numéro</th>
                    <th>Nbr de place</th>
                    <th>Actions</th>
                </thead>
                <tbody class="tbody" id="table-bus">

                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="/assets/js/scripts.js"></script>

<?php

$content = ob_get_clean();
require_once __DIR__ . '/../../templete_app/main_templete.php';               