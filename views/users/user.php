<?php

use App\Token_csrf;
$token_csrf = Token_csrf::gererateTokenCsrf();

$title = "Gérant | bi-trans";
$role = $_SESSION['bitrans']['role'];

if ($role !== "admin" || $role !== "proprietaire") :
    $_SESSION = [];
    session_destroy();
    header("location: /");
endif;
// $urlScript = '/assets/js/app.js';

ob_start();
?>
<div class="icon-boxes position-relative" data-aos="fade-up" data-aos-delay="200">
    <div class="container position-relative">
        <div class="row justify-content-center gy-4 mt-4">

            <div class="col-xl-12 row justify-content-around py-5 px-2 bg-white">

                <form class="col-12 col-lg-4 px-2 py-3" id="add-gerant">
                    <div class="h5">Ajout un gérant</div>

                    <input type="hidden" value="<?= $token_csrf ?>" name="csrf">
                    <div class="form-group my-3">
                        <input type="text" autocomplete="off" name="nom" id="nom" placeholder="Nom" class="form-control shadow-none rounded-3 border border-success">
                    </div>
                    <div class="form-group my-3">
                        <input type="text" autocomplete="off" name="prenom" id="prenom" placeholder="Prénom" class="form-control shadow-none rounded-3 border border-success">
                    </div>
                    <div class="form-group my-3">
                        <input type="tel" maxlength="10" autocomplete="off" name="telephone" id="telephone" placeholder="Téléphone" class="form-control shadow-none rounded-3 border border-success">
                    </div>
                    
                    <div class="form-group my-3">
                        <input type="password" autocomplete="off" name="mdp" id="mdp" placeholder="Mot de passe" class="form-control shadow-none rounded-3 border border-success">
                    </div>
                    
                    <input type="hidden" name="registerType" id="type" value="gerant">
                    <div class="btn-block my-3 text-white">
                        <button class="col-12 btn btn-success rounded-3 shadow-none" id="btn-save">
                            + Ajouter
                        </button>
                    </div>
                    <div class="my-2 alert d-none alert-dismissible rounded-3" role="alert" id="info">
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
                                <th>Nom</th>
                                <th>prénom</th>
                                <th>Tél</th>
                                <th>Actions</th>
                            </thead>
                            <tbody id="table-first">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function(){

        var currentPageP = 1,
        limitP = 10;
        // La recuperation de données de fçcons universelle
        function getData(pageP) {
            
            axios.get('/get_gerant', {params : {limit : limitP, page : pageP}})
            .then(response => {
                
                console.log(response.data.data);
                if (response.data.status === 'success') {
                    
                    if (Array.isArray(response.data.data)){
                        var donnees = Object.values(response.data.data);
                        let total = parseInt(response.data.total);
                        let totalPages = Math.ceil(total / limitP);
                        // let session = response.data.session;
                        
                        tableDatabi(donnees);

                        renderPaginationbi(totalPages, pageP);

                    }else{
                        alert("Erreur : Données reçues non valides." + response.data.message + ' ' + response.data.total);
                    }
                }
                else{
                    $('tbody').html('<div class="alert alert-warning rounded-3">'+response.data.message+'</div>');
                }
            })
            .catch(error => {
                alert('Erreur lors de la recupération de données'+ error.message);
            })
        }

        function tableDatabi(donnees) {
            let i = 1;
            let contenuP = "";
            donnees.forEach(donne => {
                contenuP += `
                    <tr>
                        <td>${i++}</td>
                        <td>${donne.nom}</td>
                        <td>${donne.prenom}</td>
                        <td>${donne.telephone}</td>
                        <td style="min-width: 100px">
                            <a type="button" class="btn btn-primary btn-sm mx-1 rounded-3" id="select-bi"  data-code="${donne.code}" title="Ajouter">Séléctionner</a>
                        </td>
                    </tr>
                `;
            })
            $('#table-first').html(contenuP)
            $('#table-first').hide().fadeIn("slow");
        }

        // La création de la pagination
        function renderPaginationbi(totalPages, currentPageP) {
            let paginationText = '';
            for (let i = 1; i<=totalPages; i++) {
                paginationText += '<button class="page-link select-bi mx-1 my-2" data-page="'+i+'">'+i+'</button>';
            }
            $('#pagination-bi').html(paginationText);
            $('#pagination-bi button[data-page="'+ currentPageP +'"]').addClass('active');
        }

        // Au clique de bouton de la pagination
        $(document).on('click', '.select-bi', function(){
            const page = $(this).data('page');
            getData(page);
        })

        getData(currentPageP);


        $(document).on('submit', '#add-gerant', function(e){
            e.preventDefault();
            const formData = new FormData(this);
            const type = $('#type').val();
            alert(type);
            axios.post('/add_user', formData)
            .then(response => {
                console.log(response.data.message);
                if (response.data.success === "success") {
                    $('#info').removeClass('d-none alert-danger').addClass('alert-success').hide().fadeIn("slow");
                }else{
                    $('#info').removeClass('d-none alert-success').addClass('alert-danger').hide().fadeIn("slow");
                }
                $('#info').html(response.data.message);
            })
            .catch(error => {
                alert("Erreur côté serveur "+error);
            })
        })
    })
</script>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../templete_app/main_templete.php';
