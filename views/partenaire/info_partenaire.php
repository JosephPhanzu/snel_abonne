<?php
use App\Token_csrf;
$token_csrf = Token_csrf::gererateTokenCsrf();
$title = "Tableau de bord - Propriétaire";

$code_proprietaire = filter_input(INPUT_GET, 'pr', FILTER_SANITIZE_SPECIAL_CHARS);

 ob_start();
?>

                
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-200 flex items-center">
            <i data-lucide="layout-dashboard" class="h-8 w-8 text-blue-600 mr-3"></i>
            Gestion des pharmacies du partenaire
        </h1>
        
    </div>

    <!-- Stats Cards -->
    <div id="render-data" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
    </div>
    <input type="hidden" name="code_proprietaire" id="code_proprietaire" value="<?= $code_proprietaire ?>">
    <script>
        lucide.createIcons();
        
        $(document).ready(function(){
        
            var currentPageP = 1,
            limitP = 10;

            let code_proprietaire = $('#code_proprietaire').val();
            // La recuperation de données de fçcons universelle
            const getbi = async (pageP) => {

                const res = await axios.get('get_pharmacie', {params : {limitP : limitP, page : pageP, code_proprietaire : code_proprietaire}});

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
                            <p><span class="fw-bold text-blue-100">Nombre d'employés</span> : <span class="text-blue-200">${donne.nb_employes}</span></p>
                            <p><span class="fw-bold text-blue-100">Quantité de produit</span> : <span class="text-blue-200">${donne.nb_produits}</span></p>
                            <p><span class="fw-bold text-blue-100">Chiffre d'affaires</span> : <span class="text-blue-200">${donne.chiffre_affaire} Fc</span></p>
                            
                            <hr>
                            <div class="mt-3">
                                <button id="peut_connecter" data-value="${donne.peut_connecter}" data-code="${donne.code_pharmacie}" class="etat-connecter bg-white border-red-500/30 ${donne.peut_connecter ? 'text-green-600 hover:text-green-300' : 'text-red-600 hover:text-red-300'} mx-1 px-2 py-1 rounded-lg border w-50" id="supprimer" title="${donne.peut_connecter ? 'Débloqué' : 'Bloqué'}">
                                    <i class="${donne.peut_connecter ? 'fa-solid fa-lock-open' : 'fa-solid fa-lock'} text-xl"></i>
                                    ${donne.peut_connecter ? 'Débloqué' : 'Bloqué'}
                                </button>
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

            $(document).on('click', '.etat-connecter', async function(e){
                e.preventDefault();

                let code = $(this).data('code');
                let column = $(this).attr('id');
                let value = $(this).data('value');
                let formData = new FormData();
                formData.append('code', code);
                formData.append('table', 'pharmacie');
                formData.append('column', column);
                formData.append('value', value);
                const res = await axios.post('/toggle_permission', formData);
                console.log(res.data.message);
                if (res.data.status === 'success') {
                    setTimeout(() => {
                        // location.reload();
                        getbi(currentPageP);
                    }, 200);
                }else{
                    alert(res.data.message);
                }

            })
            
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