<?php
use App\Token_csrf;
$token_csrf = Token_csrf::gererateTokenCsrf();
$title = "Gestion des Partenaires - Admin";

 ob_start();
?>

                
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-200 flex items-center">
            <i data-lucide="layout-dashboard" class="h-8 w-8 text-blue-600 mr-3"></i>
            Tableau de bord
        </h1>
        <div class="grid grid-cols-1 md:grid-cols-2">
            <p class="text-blue-100 mt-2">Vue d'ensemble de vos partenaire</p>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium" data-bs-toggle="modal" data-bs-target="#ajout-pharma">
                <i data-lucide="plus" class="h-4 w-4 mr-2 inline"></i>
                Ajouter un partenaire
            </button>
        </div>
    </div>

    <div class="modal fade" id="ajout-pharma">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-black/80 border-blue-500/30 rounded border-2 border-primary">
                <div class="modal-header">
                    <p class="modal-title text-blue-200">Ajout Partenaire</p>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body rounded-bottom">
                    <div class="position-absolute border-blue-500/30 rounded-lg shadow-sm p-3 d-none col-md-4" id="info"></div>
                    <form id="form-register">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <input type="hidden" name="csrf" value="<?= $token_csrf ?>">
                            <div>
                                <label class="block text-sm font-medium text-blue-100 mb-2">Nom</label>
                                <input type="text" name="nom" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-blue-100 mb-2">Prénom</label>
                                <input type="text" name="prenom" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-blue-100 mb-2">Email</label>
                                <input type="email" name="email" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-blue-100 mb-2">Téléphone</label>
                                <input type="tel" name="tel" maxlength="10" placeholder="0897474747" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-blue-100 mb-2">Adresse</label>
                                <input type="text" name="adresse" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-blue-100 mb-2">Mot de passe</label>
                                <input type="password" name="password1" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-blue-100 mb-2">Confirmation du mot de passe</label>
                                <input type="password" name="password" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="mt-6 text-center">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                                Ajouter Partenaire
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-black/40 border-blue-500/30 rounded-lg shadow-sm">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-blue-200">Liste de  Partenaire</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-black/30 border-blue-500/30">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Nom & Prénom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Adresse</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Téléphone</th>
                        <!-- <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Statut</th> -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="render-data">
                    <!-- Data from bdd here -->
                </tbody>
            </table>
            <div id="pagination" class="pagination flex justify-center"></div>
        </div>
    </div>
    <style>
        .menulong{
            margin-left: 250px;
            transition: .3s ease-in-out;
        }
        .main-full {
            display: block !important;
            transition: .3s ease-in-out;
        }
    </style>
    <script>
        lucide.createIcons();


        
        $(document).ready(function(){
        
            var currentPageP = 1,
            limitP = 10;

            // La recuperation de données de fçcons universelle
            const getbi = async (pageP, statut) => {

                const res = await axios.get('/get_partenaire', {params : {limit : limitP, page : pageP}})

                console.log(res.data.message)
                if (res.data.status === 'success') {
                    
                    if (Array.isArray(res.data.data)){
                        var donnees = Object.values(res.data.data);
                        let all = Object.values(res.data.total);
                        let totalPages = Math.ceil(all.length / limitP);
                        // let session = res.data.session;

                        // let rupture = all.filter(produit => produit.quantite == 0),
                        //     faible = all.filter(produit => produit.quantite > 0 && produit.quantite <= produit.stock_min),
                        //     en_stock = all.filter(produit => produit.quantite > produit.stock_min);
                        
                        tableDatabi(donnees);

                        renderPagination(totalPages, pageP);

                    }else{
                        alert("Erreur : Données reçues non valides." + res.data.message + ' ' + res.data.page);
                    }
                }
                else{
                    $('#render-data').html('<div class="alert alert-warning opacity-75 rounded-lg">'+res.data.message+'</div>');
                }
            }

            let filter = '';
            $(document).on('change', '#filter', async function(e){
            	filter = $(this).val();
              	getbi(currentPageP, filter);
            });

            function tableDatabi(donnees) {
                let i = 0;
                let contenuP = "";
                donnees.map(donne => {
                    contenuP += `
                        <tr class="hover:bg-black/20">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-blue-200">${donne.nom} ${donne.prenom}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.email ?? ''}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.adresse ?? ''}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.telephone ?? ''}</td> 

                            <td class="px-6 py-3 whitespace-nowrap text-sm font-medium">

                                <button id="peut_connecter" data-value="${donne.peut_connecter}" data-code="${donne.code}" class="etat-connecter text-blue-600 hover:text-blue-900 mr-4" title="${donne.peut_connecter ? 'Débloqué' : 'Bloqué'}"><i class="${donne.peut_connecter ? 'fa-solid fa-lock-open' : 'fa-solid fa-lock'} text-xl"></i></button>

                                <a href="/info_partenaire?pr=${donne.code}" title="Modifier" class="text-blue-600 hover:text-blue-900" id="modier">
                                    <i class="fa-solid fa-pen-to-square text-blue-600 text-xl"></i>
                                </a>
                                
                                <button class="text-red-600 hover:text-red-900" id="supprimer" title="Supprimer ${donne.code}" data-code="${donne.code}"><i class="fa-solid fa-trash text-red-600 text-xl"></i></button>
                            </td>
                        </tr>
                    `;
                    i++;
                })
                $('#render-data').html(contenuP)
                $('#render-data').hide().fadeIn("slow");
            }

            function renderPagination(totalPages, currentPage) {
                let paginationText = '';

                // Bouton "Précédent"
                if (currentPage > 1) {
                    paginationText += '<button class="page-link mx-1 my-2 btn btn-sm" data-page="'+(currentPage-1)+'">«</button>';
                }

                // Toujours afficher la première page
                if (currentPage > 3) {
                    paginationText += '<button class="page-link mx-1 my-2 btn btn-sm" data-page="1">1</button>';
                    if (currentPage > 4) {
                        paginationText += '<span class="mx-1">...</span>';
                    }
                }

                // Afficher les pages autour de la page courante
                for (let i = Math.max(1, currentPage-2); i <= Math.min(totalPages, currentPage+2); i++) {
                    if (i === currentPage) {
                        paginationText += '<button class="page-link mx-1 my-2 btn btn-sm active" data-page="'+i+'">'+i+'</button>';
                    } else {
                        paginationText += '<button class="page-link mx-1 my-2 btn btn-sm" data-page="'+i+'">'+i+'</button>';
                    }
                }

                // Toujours afficher la dernière page
                if (currentPage < totalPages - 2) {
                    if (currentPage < totalPages - 3) {
                        paginationText += '<span class="mx-1">...</span>';
                    }
                    paginationText += '<button class="page-link mx-1 my-2 btn btn-sm" data-page="'+totalPages+'">'+totalPages+'</button>';
                }

                // Bouton "Suivant"
                if (currentPage < totalPages) {
                    paginationText += '<button class="page-link mx-1 my-2 btn btn-sm" data-page="'+(currentPage+1)+'">»</button>';
                }

                $('#pagination').html(paginationText);
            }


            // Au clique de bouton de la pagination
            $(document).on('click', '.page-link', function(){
                const page = $(this).data('page');
                getbi(page)
            });

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
                formData.append('table', 'proprietaire');
                formData.append('column', column);
                formData.append('value', value);
                const res = await axios.post('/toggle_permission', formData);
                console.log(res.data.message);
                if (res.data.status === 'success') {
                    setTimeout(() => {
                        // location.reload();
                        getbi(currentPageP);
                    }, 500);
                }else{
                    alert(res.data.message);
                }

            })


            // Rechercher un produit           
            $(document).on('keyup', '#search', async function(e){
                e.preventDefault();
                let r = $(this).val();
                if (r.length > 0) {
                    const res =  await axios.get('/recherche_partenaire', {params : {r : r, code_pharmacie : code_pharma}});
                    console.log(res.data.message);
                    if (res.data.status === 'success') {
                        if (Array.isArray(res.data.data)){
                            var donnees = Object.values(res.data.data);
                            
                            tableDatabi(donnees);

                        }else{
                            alert("Erreur : Données reçues non valides." + res.data.message);
                        }
                    }else{
                        $('#render-data').html('<div class="alert alert-warning opacity-75 rounded-lg">'+res.data.message+'</div>')
                    }
                }else{
                    getbi();
                }
            })
            
            $(document).on('submit', '#form-register', async function(e) {
                e.preventDefault();
                formData = new FormData(this);

                const res = await axios.post('/add_proprietaire', formData);
                console.log(res.data.message);
                $('#info').removeClass('d-none');
                if (res.data.status === 'success') {
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

                if (confirm('Voulez-vous vraiment supprimer cet partenaire?')) {
                    let code = $(this).data('code');
                    let formData = new FormData();
                    formData.append('code', code);
                    formData.append('table', 'proprietaire');
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