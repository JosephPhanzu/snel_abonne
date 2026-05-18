<?php

$title = "Convention | WillPharma";
use App\Token_csrf;
use App\Convention;

$code_conv  = filter_input(INPUT_GET, 'c', FILTER_SANITIZE_SPECIAL_CHARS);
$code_pharma  = filter_input(INPUT_GET, 'ph', FILTER_SANITIZE_SPECIAL_CHARS);

$conv_info = (new Convention())->getByCode($code_conv);

$role = $session->getRole();
$token_csrf = (new Token_csrf())->gererateTokenCsrf();

ob_start();
?>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-200 flex items-center">
            <i data-lucide="trending-up" class="h-8 w-8 text-blue-100 mr-3"></i>
            Suivi des Ventes pour la Convention <?= htmlspecialchars($conv_info['nom']) ?>
        </h1>
        <p class="text-blue-100 mt-2">Analysez vos performances commerciales</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 mt-3">
        <div class="bg-black/40 border-blue-500/30 rounded-lg border shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-200" id="titre-vente-jour">Ventes aujourd'hui</p>
                    <p class="text-2xl font-bold text-blue-100" id="nbr-vente-jour">00</p>
                </div>
                <i data-lucide="shopping-cart" class="h-8 w-8 text-blue-600"></i>
            </div>
            <div class="mt-2">
                <!-- <span class="text-sm text-green-600">+15% vs hier</span> -->
            </div>
        </div>
        <div class="bg-black/40 border-blue-500/30 rounded-lg border shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-200" id="titre-total-vente">CA du jour</p>
                    <p class="text-2xl font-bold text-blue-100" id="total-vendu">0,00 Fc</p>
                </div>
                <!-- <i data-lucide="euro" class="h-8 w-8 text-green-600"></i> -->
                <span class="h-8 w-8 text-green-600 text-2xl">CDF</span>
            </div>
            <div class="mt-2">
                <!-- <span class="text-sm text-green-600">+8% vs hier</span> -->
            </div>
        </div>
        <div class="bg-black/40 border-blue-500/30 rounded-lg border shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-200">Membres</p>
                    <p class="text-2xl font-bold text-blue-100" id="nbr-client">00</p>
                </div>
                <i data-lucide="users" class="h-8 w-8 text-orange-600"></i>
            </div>
            <div class="mt-2">
                <!-- <span class="text-sm text-green-600">+12% vs hier</span> -->
            </div>
        </div>
    </div>
    <input type="hidden" value="<?= filter_input(INPUT_GET, 'ph', FILTER_SANITIZE_SPECIAL_CHARS) ?>" id="code_pharma">

    <input type="hidden" value="<?= filter_input(INPUT_GET, 'c', FILTER_SANITIZE_SPECIAL_CHARS) ?>" id="code_conv">
    <input type="hidden" name="csrf" value="<?= $token_csrf ?>" id="token">

    <div class="bg-black/40 border-blue-500/30 rounded-lg border shadow-sm mb-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="number" id="search" placeholder="Rechercher un membre par son numéro..." class="bg-black/40 border-blue-500/30 text-blue-100 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                
            </div>
        </div>
    </div>

    <!-- Recent Sales -->
    <div class="bg-black/40 border-blue-500/30 rounded-lg border shadow-sm mb-4">
        <div class="p-6 border-b">
            <div class="flex flex-col md:flex-row justify-start md:justify-between items-center">
                <h3 class="text-lg font-semibold text-blue-200 text-start">Ventes récentes</h3>
                <div class="col-5 col-md-8 flex flex-row">
                    <input type="date" id="date-choisi" class="bg-white/10 border-blue-900/30 text-blue-100 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <input type="month" id="mois-choisi" class="bg-white/10 border-blue-900/30 text-blue-100 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mx-2">
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full table-hover">
                <thead class="bg-black/30 border-blue-500/30">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Heure</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Vendeur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="render-vente" style="overflow: auto;">
                    <!-- Table vente bdd here -->
                </tbody>
            </table>
            <div id="pagination" class="pagination flex justify-center"></div>
        </div>
    </div>

    <!-- CDN pour html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <!-- html2pdf lib -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script>
        $(document).ready(function(e){

            let code_pharma = $('#code_pharma').val(),
                code_conv = $('#code_conv').val();
            
            var currentPage = 1,
            limitP = 8;

            // La recuperation de données de fçcons universelle
            const getVente = async (pageP, r) => {
                const res = await axios.get('/get_facture_conv', {params : {limit : limitP, page : pageP, code_pharmacie : code_pharma, code_conv : code_conv, jour : jour, mois : mois, r : r}});
                console.log(res.data.message)
                if (res.data.status === 'success') {
                    
                    if (Array.isArray(res.data.data)){
                        var donnees = Object.values(res.data.data);
                        let all = Object.values(res.data.total);

                        let total = 0;
                        let totalPages = Math.ceil(total / limitP);

                        let totalVendu = 0;
                        let nbr_client = 0;
                        // alert(total)
                        if (r !== undefined) {
                            donnees.map(vente => {
                                totalVendu += vente.total;
                            });
                            nbr_client = donnees.filter(vente => vente.code_pharmacie === code_pharma).length;
                            total = donnees.length;
                        }else{
                            all.map(vente => {
                                totalVendu += vente.total;
                            });
                            nbr_client = all.filter(vente => vente.code_pharmacie === code_pharma).length;
                            total = all.length;
                        }
                        

                         
                        $('#nbr-client').text(nbr_client);
                        let num_fac = donnees[0].id;
                        $('#num-facture').text(num_fac +1);
                        $('#nbr-vente-jour').text(total );

                        $('#total-vendu').text( totalVendu.toFixed(2) + ' Fc');
                        tableVente(donnees);
                        renderPagination(totalPages, pageP);

                    }else{
                        alert("Erreur : Données reçues non valides." + res.data.message);
                    }
                }
                else{
                    $('#render-vente').html('<div class="alert alert-warning opacity-75 rounded-lg">'+res.data.message+'</div>');
                    $('#nbr-vente-jour').text('0');
                    $('#total-vendu').text('0,00 Fc');
                    $('#plus-vendu').html('');
                }
            }

            function tableVente(donnees) {
                let i = 0;
                let contenuP = "";
                donnees.map(donne => {
                    var temps = donne.temps;
                    temps *= 1000;
                    const date = new Date(temps);
                    
        
                    contenuP += `
                        <tr class="hover:bg-black/20">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${date.toLocaleString("fr-FR", {timeStyle: "short"})}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.nom_client}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">${donne.total} Fc</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.nom_vendeur}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="/facture?ph=${code_pharma}&fc=${donne.code_facture}" class="text-blue-600 hover:text-blue-900">Détails</a>
                            </td>
                        </tr>
                    `;
                    
                });
                
                $('#render-vente').html(contenuP);
                
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
                getVente(page)
            })

            $(document).on('click', '.voir-vente', function(e){
                e.preventDefault();
                type_vente = $(this).attr('id');
                getVente(currentPage, type_vente);
            });

            let jour = '', mois = '';
            $('#date-choisi').on('change', function(){
                jour = $(this).val();
                $('#titre-vente-jour').text('Vente du '+jour);
                $('#titre-total-vente').text('CA du '+jour);
                getVente(currentPage);
            });

            $('#mois-choisi').on('change', function(){
                jour = null;
                mois = $(this).val();
                $('#titre-vente-jour').text('CA du Mois '+mois);
                $('#titre-total-vente').text("CA du Mois "+mois);
                getVente(currentPage);
            })

            // Recherche produit pour vendre
            $(document).on('keyup', '#search', async function(e){
                e.preventDefault();
                let r = $(this).val();
                if (r.length > 0) {
                    getVente(currentPage, r)
                }else{
                    getVente();
                }
            });

            getVente(currentPage);

        })
    </script>
<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
