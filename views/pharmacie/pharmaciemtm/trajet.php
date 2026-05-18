<?php

use App\Token_csrf;
use App\Bus;

$token_csrf = Token_csrf::gererateTokenCsrf();

$bus = (new Bus())->getJoinBus($_GET['a']);
$bus = count($bus) > 0 ? $bus : [];

$title = "Gestion Bus | Bitrans";
ob_start();

?>

    <div class="col-xl-12 row justify-content-around py-5 px-2 bg-white">

        <form class="col-12 col-lg-4 px-2 py-3" data-title="trajet" id="add-agencemtm">
            <div class="h5">Ajout un trajet</div>

            <input type="hidden" value="<?= $token_csrf ?>" name="csrf">
            <input type="hidden" name="code_agence" id="code_agence" value="<?= $_GET['a'] ?>">
            <div class="form-group my-3">
                <input type="text" autocomplete="off" name="depart" id="depart" placeholder="Départ" class="form-control shadow-none rounded-3 border " require>
            </div>
            <div class="form-group my-3">
                <input type="text" autocomplete="off" name="arrive" id="arrive" placeholder="Arrivé" class="form-control shadow-none rounded-3 border " require>
            </div>
            <div class="form-group my-3">
                <input type="number" autocomplete="off" name="prix" id="prix" placeholder="Prix" class="form-control shadow-none rounded-3 border " require>
            </div>
            <div class="form-group my-3">
                <input type="date" autocomplete="off" name="date" id="date" placeholder="Date" class="form-control shadow-none rounded-3 border " require>
            </div>
            <div class="form-group my-3">
                <input type="time" autocomplete="off" name="time" id="time" placeholder="time" class="form-control shadow-none rounded-3 border " require>
            </div>

            <div class="form-group my-3">
                <select name="code_bus" id="code_bus" class="form-select form-control " require>
                    <option value="">Séléctionné un bus</option>
                    
                    <?php 
                    foreach ($bus as $item) :
                        ?>
                    <option value="<?= $item['code_bus'] ?>">N° :<?= $item['numero'].' '.$item['nbr_place'] ?> Place</option>
                        <?php
                    endforeach;
                    ?>

                </select>
            </div>
        
            <div class="btn-block my-3 text-white">
                <button class="col-12 btn btn-primary rounded-3 shadow-none" id="btn-save">
                    + Ajouter
                </button>
            </div>
            <div class="my-2 alert d-none alert-dismissible rounded-3" role="alert" id="info-trajet">
                <span id="info-text"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </form>

        <div class="col-12 col-lg-7 border rounded-3 px-2 py-3">
            <div class="h5">Liste de trajets</div>
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>N°</th>
                        <th>Départ</th>
                        <th>Arrivéé</th>
                        <th>Prix</th>
                        <th>Date & Heure</th>
                        <th>N° Bus</th>
                    </thead>
                    <tbody class="tbody" id="table-trajet">

                    </tbody>
                </table>
            </div>
        </div>

    </div>
                
<script src="/assets/js/scripts.js"></script>
<?php

$content = ob_get_clean();
require_once __DIR__ . '/../../templete_app/main_templete.php';