<?php

    
    // $css = '/assets/css/styles.css';
    $title = "Inventaire | WILLPHARMA";
    
    ob_start();
?>
    <!-- Inventory Tab -->
    <div id="inventory" class="tab-content">
        <div class="mb-6 flex justify-between">
            <div>
                <h2 class="text-2xl font-bold text-blue-200">Gestion de l'Inventaire</h2>
                <p class="text-gray-100">Suivi en temps réel de vos stocks pharmaceutiques</p>
            </div>
            <div>
                <button onclick="location.href='/rapport?ph=<?= $_GET['ph'] ?>'" class="rounded-lg bg-blue-700 p-2 text-blue-50 hover:bg-blue-500 border-2 hover:border-white"> <i class="fas fa-file-alt"></i> Rapport</button>
            </div>
        </div>

        <!-- Inventory Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-200">Valeur Total Stock</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1" id="totalStockValue">0.00 FC</p>
                    </div>
                    <div class="bg-blue-500 rounded-full p-1">
                        CDF
                    </div>
                </div>
            </div>

            <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-200">Produits Totaux</p>
                        <p class="text-2xl font-bold text-green-600 mt-1" id="nbr-produits">0</p>
                    </div>
                    <div class="bg-green-500 rounded-full p-2">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>

            <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-200">Stocks Faibles</p>
                        <p class="text-2xl font-bold text-amber-600 mt-1" id="nbr-faible">0</p>
                    </div>
                    <div class="bg-amber-500 rounded-full p-2">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>

            <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-200">Stocks Critiques</p>
                        <p class="text-2xl font-bold text-red-600 mt-1" id="nbr-rupture">0</p>
                    </div>
                    <div class="bg-red-500 rounded-full p-2">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b bg-black/40 backdrop-blur-sm border-blue-500/30">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-blue-200 flex items-center">
                        <i class="fas fa-boxes mr-2"></i>
                        Inventaire des Produits
                    </h3>
                    <div id="demarrageInvt" class="flex items-center space-x-4">
                        <!-- btn will be load here -->
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-black/40 backdrop-blur-sm">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Stock Actuel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Stock Système</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Différence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Valeur Totale</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" data-id="inventoryTableBody" id="render-data">
                        <!-- Will be populated by JavaScript -->
                    </tbody>
                </table>
                <div id="pagination" class="pagination flex justify-center"></div>
                <!-- <button id="creatxls" class="text-blue-100 text-xl p-2 rounded bg-blue-600 m-3">💱 rapport</button> -->
            </div>
        </div>

        <!-- Listes inventaires -->
         <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg overflow-hidden mt-6">
            <div class="px-6 py-4 border-b bg-black/40 backdrop-blur-sm border-blue-500/30">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-blue-200 flex items-center">
                        <i class="fas fa-boxes mr-2"></i>
                        Listes des Inventaires
                    </h3>
                    <div id="demarrageInvt" class="flex items-center space-x-4">
                        <!-- btn will be load here -->
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-black/40 backdrop-blur-sm">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Erreurs</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Ecart Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" data-id="inventoryTable" id="render-data-liste">
                        <!-- Will be populated by JavaScript -->
                    </tbody>
                </table>
                <div id="pagination-liste" class="pagination flex justify-center"></div>
                <!-- <button id="creatxls" class="text-blue-100 text-xl p-2 rounded bg-blue-600 m-3">💱 rapport</button> -->
            </div>
        </div>

    </div>
    <input type="hidden" value="<?= $_GET['ph'] ?>" class="code_pharma">
    <script>
        $(document).ready(function(){
            var currentPageP = 1,
            limitP = 10;
            let code_pharma = $('.code_pharma').val();

            $(document).on('click', '#get-rapport', async function(e){
                e.preventDefault();
                const res = await axios.get('/get_rapport_inv', {params : {code_pharmacie : code_pharma}})
                const data = res.data.data;

                const sheet = XLSX.utils.json_to_sheet(data);
                const book = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(book, sheet, 'WillPharma');
                XLSX.writeFile(book, 'Rapport_willpharma.xlsx');
            })

            // La recuperation de données de fçcons universelle
            const getbi = async (pageP, statut) => {

                const res = await axios.get('/get_produit_invent', {params : {limit : limitP, page : pageP, code_pharmacie : code_pharma}})

                console.log(res.data.message);
                console.log(res.data.testProd);
                if (res.data.status === 'success') {
                    
                    if (Array.isArray(res.data.data)){
                        var donnees = Object.values(res.data.data);
                        let all = Object.values(res.data.total);
                        let totalPages = Math.ceil(all.length / limitP);
                        // let session = res.data.session;

                        let rupture = all.filter(produit => produit.quantite === 0),
                            faible = all.filter(produit => produit.quantite > 0 && produit.quantite <= produit.stock_min),
                            en_stock = all.filter(produit => produit.quantite > produit.stock_min);

                        $('#nbr-produits').text(all.length);
                        $('#nbr-stock').text(en_stock.length);
                        $('#nbr-faible').text(faible.length);
                        $('#nbr-rupture').text(rupture.length);
                        let total = 0;
                        all.map((donne) => {
                            total += donne.prix_achat;
                        })
                        $('#totalStockValue').text(total.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + 'FC');
                      	if (statut === 'rupture') {
                        	donnees = rupture;
                        }else if (statut === 'stock') {
                        	donnees = en_stock;
                        }else if (statut === 'faible') {
                        	donnees = faible;
                        }

                        const dernierInventaire = res.data.dernierInventaire;
                        const statut_inv = dernierInventaire['statut'];
                        
                        $('#demarrageInvt').html(`
                            <button id="Demmarer" ${statut_inv === 'en_cours' ? 'disabled' : ''} title="Démarrer l'inventaire" class="demmarage-arret bg-blue-600 hover:bg-blue-700 hover:border-1 hover:border-white rounded-lg p-2 text-blue-50"><i class="${statut_inv === 'en_cours' ? 'fas fa-pause' : 'fas fa-play'}"></i> ${statut_inv === 'en_cours' ? 'En cours...' : 'Démarrer'}</button>
                            
                            <button id="get-rapport" ${statut_inv !== 'en_cours' ? 'disabled' : ''} title="Terminer l'inventaire" class="bg-indigo-600 hover:bg-indigo-700 border-2 hover:border-white rounded-lg p-2 text-yellow-50"><i class="fas fa-save"></i> Enregistrer les rapport</button>

                            <button id="Terminer" ${statut_inv !== 'en_cours' ? 'disabled' : ''} title="Terminer l'inventaire" class="demmarage-arret bg-yellow-600 hover:bg-yellow-700 border-2 hover:border-white rounded-lg p-2 text-yellow-50"><i class="${statut_inv === 'en_cours' ? 'fas fa-stop' : 'fas fa-stop'}"></i> Terminer</button>
                        `);
                        
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
                let i = 0, total = 0;
                let contenuP = "";
                donnees.map((donne) => {
                    contenuP += `
                        <tr class="hover:bg-black/20">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-blue-200">${donne.nom}</div>
                                <div class="text-sm text-gray-500">${donne.nom_scientifique}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">
                                <div>
                                    ${donne.qte_boite_actuelle ?? '-'} <button title="Quantité boite" class="text-gray-400 hover:text-gray-600 edit-qte-inventaire" id="edit-boite" data-code="${donne.code}" data-qte="${donne.qte_boite}">
                                        <i class="fas fa-edit w-4 h-4"></i>
                                    </button>
                                    | 
                                    ${donne.quantite_actuelle ?? '-'} <button title="Quantité plaquette" class="text-gray-400 hover:text-gray-600 edit-qte-inventaire" id="edit-qte" data-code="${donne.code}" data-qte="${donne.quantite}">
                                        <i class="fas fa-edit w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.qte_boite ?? '-'} Boit. | ${donne.quantite ?? '-'} Plaq. </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.difference_qte_boite ?? '-'} | ${donne.difference_qte ?? '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">
                                ${ donne.quantite != 0 ? donne.prix_achat.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '0,00'}Fc
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">`;
                            if (donne.difference_qte < 0 || donne.difference_qte_boite < 0) {
                                contenuP += `
                                    <span class="px-2 py-1 text-xs font-medium bg-red-100 text-yellow-800 rounded-full">Excès</span>
                                `;
                            }else if (donne.difference_qte > 0 || donne.difference_qte_boite > 0) {
                                contenuP += `
                                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-green-800 rounded-full">Manquant</span>
                                `
                            }else if (donne.difference_qte == 0 || donne.difference_qte == null && donne.difference_qte_boite == 0 || donne.difference_qte_boite == null) {
                                contenuP += `
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-red-800 rounded-full">Correct</span>
                                `;
                            }else{
                                contenuP += `
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full"></span>
                                `;
                            }
                            contenuP += ` 
                            </td>
                            
                        </tr>
                    `;
                    i++;
                });
                $('#render-data').html(contenuP)
                $('#render-data').hide().fadeIn("slow");
            }

            // Fonction de mise à jour du stock
            const updateStock = async (code_produit, quantite, type) => {
                let formData = new FormData();
                formData.append('code_produit', code_produit);
                formData.append('quantite', quantite);
                formData.append('code_pharmacie', code_pharma);
                formData.append('type', type);
                const res = await axios.post('/update_qte_actuelle', formData);
                console.log(res.data.message);
                if (res.data.status === 'success') {
                    setTimeout(() => {
                        getbi(currentPageP, filter);
                    }, 500);
                }else{
                    alert(res.data.message);
                }
            }

            // Pop up pour la modification de la quantité dans l'inventaire
            $(document).on('click', '.edit-qte-inventaire', function(e) {
                e.preventDefault();
                const quantiteActuelle = $(this).data('qte'), code_produit = $(this).data('code');
                const type = $(this).attr('id');
                const message = type === 'edit-boite' ? 'Nouveau stock en boite pour ce produit:' : 'Nouveau stock pour ce produit:'
                const newStock = prompt(message, quantiteActuelle);
                if (newStock !== null && !isNaN(newStock) && newStock >= 0) {
                    updateStock(code_produit, parseFloat(newStock), type);
                }
            })

            // Demmarer ou arreter un inventaire
            $(document).on('click', '.demmarage-arret', async function(e) {
                e.preventDefault();
                if (!confirm('Êtes-vous sûr de vouloir effectuer cette action?')) {
                    return;
                }
                let formData = new FormData();
                let action = $(this).attr('id');
                formData.append('code_pharmacie', code_pharma);
                formData.append('action', action);
                const res = await axios.post('/demmarer_arreter_inv', formData);
                console.log(res.data.message);
                if (res.data.status === 'success') {

                    setTimeout(() => {
                        getbi(currentPageP, filter);
                    }, 500);
                }else{
                    alert(res.data.message);
                }
            })
            

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


            const getListe = async (pageP, statut) => {

                const res = await axios.get('/get_liste_invent', {params : {limit : limitP, page : pageP, code_pharmacie : code_pharma}})

                console.log(res.data.message);
                if (res.data.status === 'success') {
                    
                    if (Array.isArray(res.data.data)){
                        var donnees = Object.values(res.data.data);
                        let all = Object.values(res.data.total);
                        let totalPages = Math.ceil(all.length / limitP);
                        // let session = res.data.session;
                        
                        tableDataListe(donnees);

                        renderPaginationListe(totalPages, pageP);

                    }else{
                        alert("Erreur : Données reçues non valides." + res.data.message + ' ' + res.data.page);
                    }
                }
                else{
                    $('#render-data-liste').html('<div class="alert alert-warning opacity-75 rounded-lg">'+res.data.message+'</div>');
                }
            }


            function tableDataListe(donnees) {
                let i = 0, total = 0;
                let contenuP = "";
                donnees.map((donne) => {
                    contenuP += `
                        <tr class="hover:bg-black/20">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-blue-200">${donne.date_debut ?? '-' } - ${donne.date_fin ?? '-' }</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">
                                <div>
                                    ${donne.nombre_erreurs ?? ''}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.total_ecart_qte_boite ?? '-'} Boit. | ${donne.total_ecart_qte ?? '-'} Plaq. </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.statut ?? ''} </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button onclick="window.location.href='/detail_inventaire?ci=${donne.code}&ph=${donne.code_pharmacie}'" title="Voir les détails" class="text-gray-400 hover:text-gray-600 select-bi" data-code="${donne.code}">
                                    <i class="fas fa-eye w-4 h-4"></i> Détails
                                </button>
                            </td>
                            
                        </tr>
                    `;
                });
                $('#render-data-liste').html(contenuP)
                $('#render-data-liste').hide().fadeIn("slow");
            }

            function renderPaginationListe(totalPages, currentPage) {
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

                $('#pagination-liste').html(paginationText);
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

            getListe(currentPageP);

        })
    </script>

<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../templete_app/main_templete.php';