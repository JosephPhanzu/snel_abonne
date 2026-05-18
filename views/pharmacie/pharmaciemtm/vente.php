<?php

use App\Token_csrf;
use App\Trajet;

$token_csrf = Token_csrf::gererateTokenCsrf();

$trajet = (new Trajet())->getJoinTrajet($_GET['a']);
$trajet = count($trajet) > 0 ? $trajet : [];

$title = "Gestion Bus | Bitrans";
ob_start();

?>

    <div class="col-xl-12 row justify-content-around py-5 px-2 bg-white">

        <form class="col-12 col-lg-4 px-2 py-3" data-title="vente" id="add-agencemtm">
            <div class="h5">Ajout un Vente</div>

            <input type="hidden" value="<?= $token_csrf ?>" name="csrf">
            <input type="hidden" name="code_agence" id="code_agence" value="<?= $_GET['a'] ?>">

            <div class="form-group my-3">
                <input type="text" autocomplete="off" name="nom" id="nom" placeholder="Nom" class="form-control shadow-none rounded-3 border " require>
            </div>
            <div class="form-group my-3">
                <input type="text" autocomplete="off" name="prenom" id="prenom" placeholder="Prenom" class="form-control shadow-none rounded-3 border " require>
            </div>
            <div class="form-group my-3">
                <input type="tel" autocomplete="off" name="telephone" id="telephone" placeholder="Téléphone" maxlength="10" class="form-control shadow-none rounded-3 border " require>
            </div>
            
            <div class="form-group my-3">
                <select name="code_trajet" id="code_trajet" class="form-select form-control " require>
                    <option value="">Séléctionné un trajet</option>
                    
                    <?php 
                    foreach ($trajet as $item) :
                        ?>
                    <option value="<?= $item['code_trajet'] ?>"><?= $item['depart'] .'-'. $item['arrive'] ?></option>
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
            <div class="my-2 alert d-none alert-dismissible rounded-3" role="alert" id="info-vente">
                <span id="info-text"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </form>

        <div class="col-12 col-lg-7 border rounded-3 px-2 py-3">
            <div class="h5">Liste de Ventes</div>
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>N°</th>
                        <th>Nom client</th>
                        <th>Tél client</th>
                        <th>Depart</th>
                        <th>Arrivéé</th>
                        <th>Prix</th>
                        <th>Date & Heure</th>
                    </thead>
                    <tbody class="tbody" id="table-vente">

                    </tbody>
                </table>
            </div>
        </div>

    </div>
<script src="/assets/js/scripts.js"></script>
<?php

$content = ob_get_clean();
require_once __DIR__ . '/../../templete_app/main_templete.php';     