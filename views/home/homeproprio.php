<?php
use App\Token_csrf;
$token_csrf = Token_csrf::gererateTokenCsrf();
$title = "Tableau de bord - Propriétaire";

 ob_start();
?>

                
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-200 flex items-center">
            <i data-lucide="layout-dashboard" class="h-8 w-8 text-blue-600 mr-3"></i>
            Tableau de bord
        </h1>
        <div class="grid grid-cols-1 md:grid-cols-2">
            <p class="text-blue-100 mt-2">Vue d'ensemble de vos pharmacies</p>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium" data-bs-toggle="modal" data-bs-target="#ajout-pharma">
                <i data-lucide="plus" class="h-4 w-4 mr-2 inline"></i>
                Ajouter une pharmacie
            </button>
        </div>
    </div>

    <div class="modal fade" id="ajout-pharma">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-black/80 border-blue-500/30 rounded border-2 border-primary">
                <div class="modal-header">
                    <p class="modal-title text-blue-200">Ajout Pharmacie</p>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body rounded-bottom">
                    <div class="position-absolute border-blue-500/30 rounded-lg shadow-sm p-3 d-none col-md-4" id="info"></div>
                    <form id="add-pharma">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <input type="hidden" name="csrf" value="<?= $token_csrf ?>">
                            <div>
                                <label class="block text-sm font-medium text-blue-100 mb-2">Nom complet</label>
                                <input type="text" name="nom" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-blue-100 mb-2">Adresse</label>
                                <input type="text" name="adresse" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-blue-100 mb-2">Description</label>
                                <input type="text" name="description" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-blue-100 mb-2">Type de pharmacie</label>
                                <select name="type" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="depot">Dépot</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-6 text-center">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                                Ajouter Pharmacie
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
        <div id="render-data" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
    </div>
    <script>
        lucide.createIcons();
        
        $(document).ready(function(){
        
            var currentPageP = 1,
            limitP = 10;
            // La recuperation de données de fçcons universelle
            const getbi = async (pageP) => {

                const res = await axios.get('get_pharmacie', {params : {limitP : limitP, page : pageP}})

                console.log(res.data.message)
                if (res.data.status == 'success') {
                    
                    if (Array.isArray(res.data.data)){
                        var donnees = Object.values(res.data.data);
                        let total = parseInt(res.data.total);
                        let totalPages = Math.ceil(total / limitP);
                        // let session = res.data.session;
                        
                        tableDatabi(donnees);

                        renderPaginationbi(totalPages, pageP);

                    }else{
                        alert("Erreur : Données reçues non valides." + res.data.message + ' ' + res.data.page);
                    }
                }
                else{
                    $('#render-data').html('<div class="alert rounded-lg opacity-50 alert-warning">'+res.data.message+'</div>');
                }
            }

            function tableDatabi(donnees) {
                let i = 1;
                let contenuP = "";
                donnees.forEach(donne => {
                    contenuP += `
                        <div class="gap-6 mb-8 bg-black/40 border-blue-500/30 rounded-lg border border-primary shadow-sm p-6">
                            <h2 class="text-white fs-4 mb-3"><i class="fa-solid fa-house-medical-circle-check text-blue-500 me-2"></i>${donne.nom_pharmacie}</h2>
                            <p><span class="fw-bold text-blue-100">Nom</span> : <span class="text-blue-200">${donne.nom_pharmacie}</span></p>
                            <p><span class="fw-bold text-blue-100">Adress</span> : <span class="text-blue-200">${donne.adresse}</span></p>
                            <p><span class="fw-bold text-blue-100">Type de pharmacie</span> : <span class="text-blue-200">${donne.type_pharmacie}</span></p>
                            <div class="grid grid-cols-1 my-2 text-center">
                                <button ${!donne.peut_connecter ? 'disabled' : ''} onclick="location.href='/dashboard?ph=${donne.code_pharmacie}'" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded-md font-medium">Voir plus</button>
                            </div>
                            <hr>
                            <div class="mt-3">
                                <button ${!donne.peut_connecter ? 'disabled' : ''} onclick="location.href='/settings?ph=${donne.code_pharmacie}#information-pharma'" class="bg-white border-blue-500/30 text-blue-200 mx-1 px-2 py-2 rounded-lg hover:text-blue-600 border">
                                    <i class="fa-solid fa-file-pen me-1"></i>
                                    Modifier
                                </button>
                                <button ${!donne.peut_connecter ? 'disabled' : ''} class="bg-white border-red-500/30 text-red-300 mx-1 px-2 py-1 rounded-lg hover:text-red-600 border" id="supprimer" data-code="${donne.code_pharmacie}">
                                    <i class="fa-solid fa-trash me-1"></i>
                                    Supprimer
                                </button>
                                ${!donne.peut_connecter ? `
                                    <span class="text-xl text-red-400 font-bold">Pharmacie bloqué! </span>
                                ` : ''}
                            </div>
                            
                        </div>
                    `;
                })
                $('#render-data').html(contenuP)
                $('#render-data').hide().fadeIn("slow");
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
            
            $(document).on('submit', '#add-pharma', async function(e){
                e.preventDefault();
                const formData = new FormData(this);
                
                const res = await axios.post('/add_pharmacie', formData)
                console.log(res.data.message);
                $('#info').removeClass('d-none');
                if (res.data.status === "success") {
                
                    $('#info').removeClass('error').addClass('success').hide().fadeIn("slow");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);

                }else{
                    $('#info').removeClass('success').addClass('error').hide().fadeIn("slow");
                }
                $('#info').html(res.data.message);
                
            })

            $(document).on('click', '#supprimer', async function(e){
                e.preventDefault();

                if (confirm('Voulez-vous vraiment supprimer cet employé')) {
                    let code = $(this).data('code');
                    let formData = new FormData();
                    formData.append('code', code);
                    formData.append('table', 'pharmacie');
                    const res = await axios.post('/delete', formData);
                    console.log(res.data.message);
                    if (res.data.status === 'success') {
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }else{
                        alert(res.data.message);
                    }
                }

            })

        })
    
    </script>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../templete_app/admin_templete.php';