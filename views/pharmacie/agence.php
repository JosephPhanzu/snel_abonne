<?php

use App\Token_csrf;
$token_csrf = Token_csrf::gererateTokenCsrf();

$title = "Agence | bi-Trans";
// $urlScript = '/assets/js/app.js';

ob_start();
?>
<div class="icon-boxes position-relative" data-aos="fade-up" data-aos-delay="200">
    <div class="container position-relative">
        <div class="row justify-content-center gy-4 mt-4">

            <div class="col-xl-12 row justify-content-around py-5 border border-success rounded-3 px-2 bg-white">
                <?php if ($_SESSION['bitrans']['role'] == "proprietaire") : ?>
                <form class="col-12 col-lg-4 px-2 py-3" id="add-agence" enctype="multipart/form-data">
                    <h3 class="text-secondary fw-bolder">Ajouter une agence</h3>
                    <input type="hidden" value="<?= $token_csrf ?>" name="csrf">

                    <div class="form-group my-3">
                        <input type="text" name="nom" placeholder="Nom Agence" id="nom" class="form-control shadow-none border-success text-secondary">
                    </div>

                    <div class="form-group my-3">
                        <input type="text" name="adresse" placeholder="Adresse agence" id="adresse" class="form-control shadow-none border-success text-secondary">
                    </div>

                    <div class="form-group my-3">
                        <textarea name="description" id="description" placeholder="Veillez décrire votre agence" class="form-control shadow-none border-success text-secondary"></textarea>
                    </div>

                    <div class="form-group my-3">
                        <input type="file" id="img" name="img" class="form-control shadow-none border-success text-secondary" placeholder="Votre nom" >
                    </div>
                    <div class="form-group my-3">
                        <button class="btn btn-success border-0 col-12 rounded-3" id="btn-save">
                            <span class="text-btn">+ Ajouter</span>
                            <span class="spinner-grow text-info d-none" id="spinner"></span>
                        </button>
                    </div>

                    <div class="my-2 alert d-none alert-dismissible rounded-3" role="alert" id="info">
                        <span id="info-text"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                </form>
                <?php endif; ?>
                <div class="col-12 col-lg-7 border border-success rounded-3 px-2 py-3">
                    <h3 class="text-secondary fw-bolder">Vos agenses</h3>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Nom Agence</th>
                                    <th>Nom proprietaire</th>
                                    <th>Adresse agence</th>
                                    <th>Action</th>
                                </tr>
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
        function getbi(pageP) {
            axios.get('get_agence', {params : {limitP : limitP, page : pageP}})
            .then(response => {
                console.log(response.data.message)
                if (response.data.status == 'success') {
                    
                    if (Array.isArray(response.data.data)){
                        var donnees = Object.values(response.data.data);
                        let total = parseInt(response.data.total);
                        let totalPages = Math.ceil(total / limitP);
                        // let session = response.data.session;
                        
                        tableDatabi(donnees);

                        renderPaginationbi(totalPages, pageP);

                    }else{
                        alert("Erreur : Données reçues non valides." + response.data.message + ' ' + response.data.page);
                    }
                }
                else{
                    $('tbody').html('<div class="alert alert-warning rounded-0">'+response.data.message+'</div>');
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
                        <td>${donne.nom_agence}</td>
                        <td>${donne.nom_proprio}</td>
                        <td>${donne.adresse}</td>
                        <td style="min-width: 100px">
                            <a type="button" href="/agencemgmt?a=${donne.code_agence}" class="btn btn-primary btn-sm mx-1 rounded-3" id="select-bi" title="Ajouter">Manager</a>
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
            getbi(page);
        })

        getbi(currentPageP);


        
        $(document).on('submit', '#add-agence', function(e){
            e.preventDefault();
            const formData = new FormData(this);

            axios.post('/add_agence', formData)
            .then(response => {
                if (response.data.status === "success") {
            
                    $('#info').removeClass('d-none alert-danger').addClass('alert-success').hide().fadeIn("slow");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);

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