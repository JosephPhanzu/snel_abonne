<?php

use App\Token_csrf;
use App\Bus;
use App\Agence;
use App\Trajet;

$token_csrf = Token_csrf::gererateTokenCsrf();

$title = "Agence management | bi-Trans";

$exist_agence = (new Agence())->findOne($_GET['a']);

if (empty($exist_agence)) :
    $_SESSION = [];
    session_destroy();
    header("location: /");
endif;

$bus = (new Bus())->getJoinBus($_GET['a']);
$bus = count($bus) > 0 ? $bus : [];

$trajet = (new Trajet())->getJoinTrajet($_GET['a']);
$trajet = count($trajet) > 0 ? $trajet : [];

ob_start();
?>
<div class="icon-boxes position-relative" data-aos="fade-up" data-aos-delay="200">
    <div class="container position-relative">
        <div class="row justify-content-center gy-4 mt-4">

            <div class="col-xl-12 row justify-content-around py-5 border border-success rounded-3 px-2 bg-white">
                <h2>Agence Management pour <span class="fw-bold text-success"><?= $exist_agence['nom'] ?></span></h2>

                <div class="row justify-content-center">
                    <div class="col-10">
                        <img src="<?= $exist_agence['img'] ?>" alt="Profil agence" title="Profil agence" class="img-fluid">
                    </div>
                </div>
                
                <div class="alert alert-primary my-3">
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quasi quisquam ut quis. Eligendi reprehenderit, at aliquid rem ducimus architecto, ab quaerat fugiat aperiam fugit provident saepe tenetur qui voluptatibus rerum?
                    Dicta officiis vitae ipsa inventore iusto optio facere minus, magni culpa nemo assumenda facilis sed enim ratione tempora aut nulla! Ipsa nulla, blanditiis similique voluptate cupiditate animi odio libero repellendus!
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quos nostrum blanditiis molestiae odio atque perspiciatis itaque sint dolorem consectetur perferendis similique reprehenderit, commodi autem eius provident? Nemo minima sapiente dolore.
                </div>

                <!-- Gestion gérant -->
                <?php if ($_SESSION['bitrans']['role'] === "proprietaire") : 
                        require_once __DIR__ . '/agencemtm/gerant.php';
                      endif; 
                
                // <!-- Gestion Bus -->
                      require_once __DIR__ . '/agencemtm/bus.php';
                
                // <!-- Gestion Trajets -->
                      require_once __DIR__ . '/agencemtm/trajet.php';

                    // Gestion Vente
                      require_once __DIR__ . '/agencemtm/vente.php';

                ?>

        </div>
    </div>
</div>

<script src="/assets/js/scripts.js"></script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../templete_app/main_templete.php';