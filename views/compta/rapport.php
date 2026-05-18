    <?php

        $title = "Rapport";
        // $js = '/assets/js/app.js';
        // $css = '/assets/css/styles.css';

        ob_start();
    ?>

    <!-- Reports Tab -->
    <div id="reports" class="tab-content">
        <div class="mb-6 flex justify-between">
            <div>
                <h2 class="text-2xl font-bold text-blue-200">Rapports sur les Produits</h2>
                <p class="text-gray-100">Analyse détaillée des performances de vos produits pharmaceutiques</p>
            </div>
            <div>
                <button onclick="location.href='/inventaire?ph=<?= $_GET['ph'] ?>'" class="rounded-lg bg-blue-700 p-2 text-blue-50 border-2 hover:border-white hover:bg-blue-500"><i class="fas fa-boxes"></i> Inventaire</button>
            </div>
        </div>

        <!-- Reports Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-200">Chiffre d'Affaires Total</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1" id="totalRevenue">0.00 €</p>
                    </div>
                    <div class="bg-blue-500 rounded-full p-3">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>

            <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-200">Bénéfices Totaux</p>
                        <p class="text-2xl font-bold text-green-600 mt-1" id="totalReportProfit">0.00 €</p>
                    </div>
                    <div class="bg-green-500 rounded-full p-3">
                        <i class="fas fa-bullseye"></i>
                    </div>
                </div>
            </div>

            <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-200">Produits Peu Performants</p>
                        <p class="text-2xl font-bold text-amber-600 mt-1" id="lowPerformingCount">0</p>
                        <p class="text-sm text-gray-300 mt-1" id="totalReportProducts">sur 0 produits</p>
                    </div>
                    <div class="bg-amber-500 rounded-full p-3">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports Table -->
        <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-black/40 backdrop-blur-sm">
                <h3 class="text-lg font-semibold text-blue-200 flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Rapport Détaillé des Produits
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-black/40 backdrop-blur-sm">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer " onclick="sortReports('productName')">
                                Produit 
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer " onclick="sortReports('category')">
                                Catégorie 
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer " onclick="sortReports('totalPurchases')">
                                Stock Init. 
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer " onclick="sortReports('totalPurchases')">
                                Entrées 
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer " onclick="sortReports('totalSales')">
                                Sorties 
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer " onclick="sortReports('totalSales')">
                                Stock Final 
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer " onclick="sortReports('totalProfit')">
                                Bénéfices 
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="render-data">
                        <!-- Will be populated by JavaScript -->
                    </tbody>
                </table>
                <div id="pagination" class="pagination flex justify-center"></div>
                <button id="creatxls" class="text-blue-100 text-xl p-2 rounded bg-blue-600 m-3">💱 rapport</button>
            </div>
        </div>
    </div>

    <input type="hidden" value="<?= $_GET['ph'] ?>" class="code_pharma">
    <script>
        $(document).ready(function(){
            // var currentPageP = 1,
            // limitP = 10;
            let code_pharma = $('.code_pharma').val(),
                code_inventaire = $('.code_inventaire').val();

            $(document).on('click', '#creatxls', async function(e){
                e.preventDefault();
                const res = await axios.get('/get_rapport', {params : {code_pharmacie : code_pharma, code_inventaire : code_inventaire}})
                const data = res.data.rappot;

                const sheet = XLSX.utils.json_to_sheet(data);
                const book = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(book, sheet, 'WillPharma');
                XLSX.writeFile(book, 'Rapport_willpharma.xlsx');
            })

            // La recuperation de données de fçcons universelle
            const getbi = async (pageP, statut) => {

                const res = await axios.get('/get_rapport', {params : {code_pharmacie : code_pharma}})

                console.log(res.data.message);
                if (res.data.status === 'success') {
                    
                    if (Array.isArray(res.data.data)){
                        var donnees = Object.values(res.data.data);

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
                    qte_ini = donne.quantite - donne.total_entrees + donne.total_sorties;
                    contenuP += `
                        <tr class="hover:bg-black/20">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-blue-200">${donne.nom}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-blue-200">${donne.categorie}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">
                                <div>
                                    ${donne.stock_initial ?? '-'} Boit.
                                    | 
                                    ${qte_ini ?? '-'} Plaq.
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.qte_boite_system ?? '-'} Boit. | ${(donne.quantite - donne.total_entrees + donne.total_sorties) ?? '-'} Plaq. </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.difference_qte_boite ?? '-'} | ${donne.total_sorties ?? '-'}</td>

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