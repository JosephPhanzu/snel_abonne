<?php

    
    // $css = '/assets/css/styles.css';
    $title = "Détails Inventaire | WILLPHARMA";
    
    ob_start();
?>
    <!-- Inventory Tab -->
    <div id="inventory" class="tab-content">
        <div class="mb-6 flex justify-between">
            <div>
                <h2 class="text-2xl font-bold text-blue-200">Gestion de l'Inventaire</h2>
                <p class="text-gray-100">Suivi en temps réel de vos stocks pharmaceutiques</p>
            </div>
            
        </div>

        <!-- Inventory Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-200">Date Inventaire</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1" id="date-inventaire"></p>
                    </div>
                    <div class="bg-blue-500 rounded-full p-1">
                        CDF
                    </div>
                </div>
            </div>

            <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-200">Status</p>
                        <p class="text-2xl font-bold text-green-600 mt-1" id="statut-inventaire">0</p>
                    </div>
                    <div class="bg-green-500 rounded-full p-2">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>

            <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-200">Nombre de produits avec Erreur</p>
                        <p class="text-2xl font-bold text-amber-600 mt-1" id="nbr-erreurs">0</p>
                    </div>
                    <div class="bg-amber-500 rounded-full p-2">
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
                        Produits inventoriés
                    </h3>
                    
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-black/40 backdrop-blur-sm">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Stock réel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Stock Système</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Différence</th>
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

    </div>
    <input type="hidden" value="<?= $_GET['ph'] ?>" class="code_pharma">
    <input type="hidden" value="<?= $_GET['ci'] ?>" class="code_inventaire">
    <script>
        $(document).ready(function(){
            // var currentPageP = 1,
            // limitP = 10;
            let code_pharma = $('.code_pharma').val(),
                code_inventaire = $('.code_inventaire').val();

            $(document).on('click', '#creatxls', async function(e){
                e.preventDefault();
                const res = await axios.get('/get_produit', {params : {code_pharmacie : code_pharma, code_inventaire : code_inventaire}})
                const data = res.data.rappot;

                const sheet = XLSX.utils.json_to_sheet(data);
                const book = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(book, sheet, 'WillPharma');
                XLSX.writeFile(book, 'Rapport_willpharma.xlsx');
            })

            // La recuperation de données de fçcons universelle
            const getbi = async (pageP, statut) => {

                const res = await axios.get('/get_detail_invent', {params : {code_pharmacie : code_pharma, code_inventaire : code_inventaire}})

                console.log(res.data.message);
                if (res.data.status === 'success') {
                    
                    if (Array.isArray(res.data.data)){
                        var donnees = Object.values(res.data.data);
                        // let session = res.data.session;

                        $('#date-inventaire').html(donnees[0]['date_debut'] ?? '-' + ' - ' + donnees[0]['date_fin'] ?? '-');
                        $('#statut-inventaire').text(donnees[0]['statut'] ?? '-');
                        $('#nbr-erreurs').text(donnees[0]['nombre_produits_inv'] ?? '-');                 
                        
                        tableDatabi(donnees);

                    }else{
                        alert("Erreur : Données reçues non valides." + res.data.message + ' ' + res.data.page);
                    }
                }
                else{
                    $('#render-data').html('<div class="alert alert-warning opacity-75 rounded-lg">'+res.data.message+'</div>');
                }
            }

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
                                    ${donne.qte_boite_actuelle ?? '-'} Boit.
                                    | 
                                    ${donne.quantite_actuelle ?? '-'} Plaq.
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.qte_boite_system ?? '-'} Boit. | ${donne.quantite_systeme ?? '-'} Plaq. </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.difference_qte_boite ?? '-'} | ${donne.difference_qte ?? '-'}</td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                `;
                                if (donne.difference_qte < 0 || donne.difference_qte_boite < 0) {
                                    contenuP += `
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-yellow-800 rounded-sm">Manquant</span>
                                    `;
                                }else if (donne.difference_qte > 0 || donne.difference_qte_boite > 0) {
                                    contenuP += `
                                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-green-800 rounded-sm">Excès</span>
                                    `
                                }else if (donne.difference_qte == 0 || donne.difference_qte_boite == 0) {
                                    contenuP += `
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-sm">Correct</span>
                                    `;
                                }else{
                                    contenuP += `
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-sm">Correct</span>
                                    `;
                                }
                                contenuP += ` 
                            </td>
                            
                        </tr>
                    `;
                
                });
                $('#render-data').html(contenuP)
                $('#render-data').hide().fadeIn("slow");
            }            

            getbi();

        })
    </script>

<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../templete_app/main_templete.php';