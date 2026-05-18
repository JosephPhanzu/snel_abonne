<?php

use App\Token_csrf;
$token_csrf = Token_csrf::gererateTokenCsrf();
$role = $session->getRole();
$title = "Produits | Welcome";

ob_start();
?>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-200 flex items-center">
            <i data-lucide="package" class="h-8 w-8 text-blue-100 mr-3"></i>
            Gestion des produits
        </h1>
        <p class="text-blue-100 mt-2">Gérez votre inventaire et suivez vos stocks</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-black/40 border-blue-500/30 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-200">Total produits</p>
                    <p class="text-2xl font-bold text-blue-100" id="nbr-produits">00</p>
                </div>
                <i data-lucide="package" class="h-8 w-8 text-blue-600"></i>
            </div>
        </div>
        <div class="bg-black/40 border-blue-500/30 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-200">En stock</p>
                    <p class="text-2xl font-bold text-blue-100" id="nbr-stock">00</p>
                </div>
                <i data-lucide="check-circle" class="h-8 w-8 text-green-600"></i>
            </div>
        </div>
        <div class="bg-black/40 border-blue-500/30 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-200">Stock faible</p>
                    <p class="text-2xl font-bold text-blue-100" id="nbr-faible">00</p>
                </div>
                <i data-lucide="alert-triangle" class="h-8 w-8 text-orange-600"></i>
            </div>
        </div>
        <div class="bg-black/40 border-blue-500/30 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-200">Ruptures</p>
                    <p class="text-2xl font-bold text-blue-100" id="nbr-rupture">00</p>
                </div>
                <i data-lucide="x-circle" class="h-8 w-8 text-red-600"></i>
            </div>
        </div>
    </div>

    
    <!-- Search and Filter -->
    <div class="bg-black/40 border-blue-500/30 rounded-lg shadow-sm p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" id="search" placeholder="Rechercher un produit..." class="bg-black/40 border-blue-500/30 text-blue-200 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <select id="filter" class="bg-black/40 border-blue-500/30 text-blue-200 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>Tous les statuts</option>
                <option value="stock">En stock</option>
                <option value="faible">Stock faible</option>
                <option value="rupture">Rupture</option>
            </select>
            <?php  ?>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium" data-bs-toggle="modal" data-bs-target="#ajout-produit">
                <i data-lucide="plus" class="h-4 w-4 mr-2 inline"></i>
                Ajouter produit
            </button>
            <?php  ?>
        </div>
    </div>
    <?php ?>
    <!-- Modal add Produits -->
    <div class="modal fade" id="ajout-produit">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-black/80 border-blue-500/30 rounded border-2 border-primary">
                <div class="modal-header">
                    <p class="modal-title text-blue-200">Ajout Produit</p>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body rounded-bottom">
                    <div class="position-absolute border-blue-500/30 rounded-lg shadow-sm p-3 d-none col-md-4" id="info">
    
                    </div>
                    <form id="add-produit">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <input type="hidden" name="csrf" value="<?= $token_csrf ?>">
                            <input type="hidden" name="code_pharmacie" class="code_pharma" value="<?= $_GET['ph'] ?>">
                            <div>
                                <label class="block text-sm font-medium text-blue-100 mb-2">Nom du produit</label>
                                <input type="text" name="nom" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-blue-100 mb-2">Forme</label>
                                <input type="text" name="categorie" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-blue-100 mb-2">Prix d'achat</label>
                                <input type="number" name="prix_achat" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            
                            
                        </div>

                        <div class="col-md-12">
                            <label class="text-blue-200 py-2">Contient-il de boite</label>
                            <div class="form-check">
                                <input type="radio" name="contien_boite" id="oui" class="form-check-input radio">
                                <label for="oui" class="form-check-label text-blue-100">Oui</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="contien_boite" id="non" class="form-check-input radio">
                                <label for="non" class="form-check-label text-blue-100">Non</label>
                            </div>
                        </div>

                        <div id="contenuSiBoite">

                        </div>

                        <div>
                            <label class="block text-sm font-medium text-blue-100 mb-2">Stock minimal</label>
                            <input type="text" disabled id="stock-min" name="stock_min" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-blue-100 mb-2">Famille</label>
                            <input type="text" name="nom_scientifique" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                      
                      	<div>
                            <label class="block text-sm font-medium text-blue-100 mb-2">N° de lot</label>
                            <input type="text" name="num_lot" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                      	
                        <div>
                            <label class="block text-sm font-medium text-blue-100 mb-2">Fournisseur</label>
                            <input type="text" name="fournisseur" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-blue-100 mb-2">Description</label>
                            <textarea  name="description" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-blue-100 mb-2">Date de peremption</label>
                            <input type="date" name="date_peremption" class="bg-white/10 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="grid grid-cols-1 mt-6 text-center">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                                Ajouter le produit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php ?>

    <!-- Products Table -->
    <div class="bg-black/40 border-blue-500/30 rounded-lg shadow-sm">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-blue-200">Inventaire des produits</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-black/30 border-blue-500/30">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Produit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Forme</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Nbre Boites</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Stock par boite</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Stock plaquette</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Prix d'achat unitaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Expiration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="render-data">
                    <!-- Data from bdd here -->
                </tbody>
            </table>
            <div id="pagination" class="pagination flex justify-center"></div>
            <button id="creatxls" class="text-blue-100 text-xl p-2 rounded bg-blue-600 m-3">💱 rapport</button>
        </div>
    </div>
    <input type="hidden" value="<?= $role ?>" id="role" />
    <input type="hidden" name="code_pharmacie" class="code_pharma" value="<?= $_GET['ph'] ?>">
    <script>
        $(document).ready(function(){

            let role = $('#role').val();

            var currentPageP = 1,
            limitP = 10;
            let code_pharma = $('.code_pharma').val();

            $(document).on('click', '#creatxls', async function(e){
                e.preventDefault();
                const res = await axios.get('/get_produit', {params : {limit : 1100, page : 1, code_pharmacie : code_pharma}})
                const data = res.data.rappot;

                const sheet = XLSX.utils.json_to_sheet(data);
                const book = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(book, sheet, 'WillPharma');
                XLSX.writeFile(book, 'Rapport_willpharma.xlsx');
            })

            // La recuperation de données de fçcons universelle
            const getbi = async (pageP, statut) => {

                const res = await axios.get('/get_produit', {params : {limit : limitP, page : pageP, code_pharmacie : code_pharma}})

                console.log(res.data.message)
                if (res.data.status === 'success') {
                    
                    if (Array.isArray(res.data.data)){
                        var donnees = Object.values(res.data.data);
                        let all = Object.values(res.data.total);
                        let totalPages = Math.ceil(all.length / limitP);
                        // let session = res.data.session;

                        let rupture = all.filter(produit => produit.quantite == 0),
                            faible = all.filter(produit => produit.quantite > 0 && produit.quantite <= produit.stock_min),
                            en_stock = all.filter(produit => produit.quantite > produit.stock_min);

                        $('#nbr-produits').text(all.length);
                        $('#nbr-stock').text(en_stock.length);
                        $('#nbr-faible').text(faible.length);
                        $('#nbr-rupture').text(rupture.length);
                      
                      	if (statut === 'rupture') {
                        	donnees = rupture;
                        }else if (statut === 'stock') {
                        	donnees = en_stock;
                        }else if (statut === 'faible') {
                        	donnees = faible;
                        }
                        
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
                                <div class="text-sm font-medium text-blue-200">${donne.nom}</div>
                                <div class="text-sm text-gray-500">${donne.nom_scientifique}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.categorie}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.qte_boite ?? '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.qte_par_boite ?? '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.quantite}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${ donne.qte_boite === null ? donne.prix_achat_unitaire.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : 
                            (donne.prix_achat_unitaire * donne.qte_par_boite).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
   
                            Fc</td>
                            <td class="px-6 py-4 whitespace-nowrap">`;
                            if (donne.quantite > 0 && donne.quantite <= donne.stock_min) {
                                contenuP += `
                                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Stock faible</span>
                                `;
                            }else if (donne.quantite > donne.stock_min) {
                                contenuP += `
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">En stock</span>
                                `
                            }else if (donne.quantite == 0) {
                                contenuP += `
                                    <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Rupture</span>
                                `;
                            }else{
                                contenuP += `
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full"></span>
                                `;
                            }
                            contenuP += ` 
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${donne.date_peremption}</td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-medium">

                                ${ role === 'proprietaire' ? `
                                <a href="/modifier_produit?ph=${code_pharma}&pr=${donne.code}" title="Modifier" class="text-blue-600 hover:text-blue-900" id="modier">
                                    <i class="fa-solid fa-pen-to-square h-8 w-8 text-blue-600"></i>
                                </a>
                                
                                <button class="text-red-600 hover:text-red-900" id="supprimer" title="Supprimer ${donne.code}" data-code="${donne.code}"><i class="fa-solid fa-trash h-9 w-9 text-red-600"></i></button> ` 
                                : '' }
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

            $(document).on('click', '.a-modifier', async function(e){
                e.preventDefault();
                $(this).removeClass('d-none');
                const colonne = $(this).attr('id');
            });


            // Rechercher un produit           
            $(document).on('keyup', '#search', async function(e){
                e.preventDefault();
                let r = $(this).val();
                if (r.length > 0) {
                    const res =  await axios.get('/recherche_produit', {params : {r : r, code_pharmacie : code_pharma}});
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


            // Supprimer un produit
            $(document).on('click', '#supprimer', async function(e){
                e.preventDefault();

                if (confirm('Voulez-vous vraiment supprimer cet employé')) {
                    let code = $(this).data('code');
                    let formData = new FormData();
                    formData.append('code', code);
                    formData.append('table', 'produits');
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


            let mode_paiement = $('.radio');
            let contenu = "";
            mode_paiement.on('change', function(e){
              e.preventDefault();
              $('#stock-min').prop('disabled', false);
              if ($(this).attr('id') === "oui") {
                
                contenu = `
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Stock(Nombre de boite)</label>
                        <input type="number" name="qte_boite" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Nombre de plaquette par boite</label>
                        <input type="number" name="qte_par_boite" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                `;
              }else if ($(this).attr('id') === "non") {
                
                contenu = `
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Stock(Nombre d'élément)</label>
                        <input type="number" name="stock" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                `;
              }

              $('#contenuSiBoite').html(contenu);

            })


            // Add produit
            $(document).on('submit', '#add-produit', async function(e){
                e.preventDefault();
                const formData = new FormData(this);
                
                const res = await axios.post('/add_produit', formData)
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
        })
    </script>
<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
