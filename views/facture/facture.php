<?php

use App\Facture;
use App\Facture_conv;
$title = "Facture | Welcome";

$facture = new Facture();
$facture_conv_inst = new Facture_conv();

$role = $session->getRole();
$code_pharmacie = filter_input(INPUT_GET, 'ph', FILTER_SANITIZE_SPECIAL_CHARS);
$code_facture = filter_input(INPUT_GET, 'fc', FILTER_SANITIZE_SPECIAL_CHARS);

$factureDetails = $facture->getArticleFactureByPharma($code_pharmacie, $code_facture);

if (empty($factureDetails)) :
    $factureDetails = $facture_conv_inst->getArticleFactureByPharma( $code_facture);
endif;

ob_start();
?>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-200 flex items-center">
            <i data-lucide="file-text" class="h-8 w-8 text-blue-100 mr-3"></i>
            Gestion des factures
        </h1>
        <p class="text-blue-100 mt-2">Consultez et gérez toutes vos factures</p>
    </div>

    <!-- Onglets -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button onclick="showTab('factures')" id="tab-factures" class="tab-button border-b-2 border-blue-500 py-2 px-1 text-sm font-medium text-blue-600">
                    Factures
                </button>
                <button onclick="showTab('equipe')" id="tab-equipe" class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Ventes par équipe
                </button>
                <button onclick="showTab('produits')" id="tab-produits" class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Produits vendus
                </button>
            </nav>
        </div>
    </div>

    <!-- Contenu des onglets -->
    
    <!-- Onglet Factures -->
    <div id="content-factures" class="tab-content">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Liste des factures -->
            <div class="lg:col-span-2">
                <div class="bg-black/40 border-blue-500/30 rounded-lg rounded-lg border shadow-sm">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Factures (3)</h3>
                        <p class="text-sm text-blue-100">Liste de toutes les factures émises</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b bg-black/30 border-blue-500/30">
                                <tr>
                                    <th class="text-left p-4 font-medium text-blue-100">N° Facture</th>
                                    <th class="text-left p-4 font-medium text-blue-100">Date</th>
                                    <th class="text-left p-4 font-medium text-blue-100">Client</th>
                                    <th class="text-left p-4 font-medium text-blue-100">Montant</th>
                                    <th class="text-left p-4 font-medium text-blue-100">Statut</th>
                                    <th class="text-left p-4 font-medium text-blue-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="render-data">
                                <!-- Data here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Détail de la facture -->
             <input type="hidden" id="code-pharma" value="<?= $_GET['ph'] ?>"><input type="hidden" id="code-facture" value="<?= $_GET['fc'] ?>">
            <div>
                <div class="bg-white rounded-lg border shadow-sm p-3">
                    <div id="invoice-detail">
                        <div class="my-3">
                            <h3 class="font-medium text-gray-900">Détail de la facture</h3>
                            <p class="text-gray-600">FAC-2024-<span id="num-fac"></span> </p>
                            <hr>
                        </div>
                        <div class="mt-2">
                        <h4 class="font-medium text-gray-900">Client</h4>
                        <p class="text-gray-600" id="nom-client">Nom Client</p>
                        </div>
                        
                        <div class="mt-2">
                        <h4 class="font-medium text-gray-900">Date</h4>
                        <p class="text-gray-600" id="date-heure">
                            00/00/0000 00:00
                        </p>
                        </div>

                        <div class="mt-2">
                        <h4 class="font-medium text-gray-900 mb-2">Articles</h4>
                        <div class="space-y-2">
                            <!-- Article here -->
                          	<?php foreach($factureDetails as $item) : ?>
                          		<div class="flex justify-between text-sm">
                                    <span class="border-1 p-1"><?=$item['nom_produit'] ?> <span class="ms-3"><?= $item['prix'] .' Fc X ' .$item['quantite'] ?> </span></span>
                                    <span class="border-1 p-1"><?=$item['prix'] * $item['quantite'] ?> Fc</span>
                                </div>
                          	<?php endforeach; ?>
                        </div>
                        </div>

                        <div class="pt-2 border-t mt-2">
                        <div class="flex justify-between font-medium">
                            <span>Total</span>
                            <span class="text-green-600" id="total">0,00 Fc</span>
                        </div>
                        </div>
                    </div>

                    <div class="pt-4 mt-2">
                      <Button 
                        class="w-full bg-blue-600 hover:bg-blue-700 rounded-full p-2 text-white" id="facturer"
                      >
                        <i class="fa-solid fa-download"></i>
                        Télécharger PDF
                      </Button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CDN pour html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <!-- html2pdf lib -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script>
        $(document).ready(function(){
            
            let code_pharma = $('#code-pharma').val(), code_facture = $('#code-facture').val();

            const getFacture = async () => {
                const res = await axios.get('/get_facture', {params : {code_pharmacie : code_pharma, code_facture : code_facture}});
                console.log(res.data.message)
                if (res.data.status === 'success') {
                    
                    if (Array.isArray(res.data.data)){
                        var donnees = Object.values(res.data.data);
                        console.log(donnees);

                        var temps = donnees[0].temps;
                        temps *= 1000;
                        const date = new Date(temps);
                        let contenu = `
                            <tr class="border-b hover:bg-black/20">
                                <td class="p-4 font-medium text-blue-100">FAC-2024-${donnees[0].id}</td>
                                <td class="p-4">
                                    <div class="flex items-center text-blue-100">
                                        <i data-lucide="calendar" class="h-4 w-4 mr-2 text-blue-200"></i>
                                        ${date.toLocaleString("fr-FR", {dateStyle: "short"})}
                                    </div>
                                </td>
                                <td class="p-4 text-blue-100">${donnees[0].nom_client}</td>
                                <td class="p-4 font-medium text-green-600">${donnees[0].total} FC</td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">Payée</span>
                                </td>
                                <td class="p-4">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-200 hover:text-blue-100">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </button>
                                        <button class="text-blue-200 hover:text-blue-100">
                                            <i data-lucide="download" class="h-4 w-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                        $('#article').empty();
                        donnees.map((produit) =>{
                            $('#article').append(`
                                <div class="flex justify-between text-sm">
                                    <span>${produit.nom_produit} <span class="ms-3">${produit.prix} Fc X ${produit.quantite}</span></span>
                                    <span>${produit.prix * produit.quantite} Fc</span>
                                </div>
                            `);
                        });

                        
                        $('#total').text(donnees[0].total+" Fc");
                        $('#nom-client').text(donnees[0].nom_client);
                        $('#num-fac').text(donnees[0].id);
                        $('#date-heure').text(date.toLocaleString("fr-FR", {dateStyle: "short"})+' '+date.toLocaleString("fr-FR", {timeStyle: "short"}))

                        $('#render-data').html(contenu);
                        $('#render-data').hide().fadeIn("slow");

                    }else{
                        alert("Erreur : Données reçues non valides." + res.data.message + ' ' + res.data.page);
                    }
                }
                else{
                    $('#render-vente').html('<div class="alert alert-warning rounded-0">'+res.data.message+'</div>');
                    $('#nbr-vente-jour').text('0');
                    $('#total-vendu').text('0,00 Fc');
                }
            }
            getFacture();
            // const interval = setInterval(() => {
            // getFacture();
            // }, 1000);
            // return () => clearInterval(interval);

            $(document).on('click', '#facturer', function(e){
                e.preventDefault();
                const element = document.getElementById('invoice-detail');
            
                // Obtenir la hauteur du contenu
                const contentHeight = element.offsetHeight;
                // Définir un format personnalisé en fonction de la hauteur du contenu
                const pageHeight = Math.ceil(contentHeight / 2.83465); // Convertir px en mm (A4 -> 297mm / 72 dpi)

                // Configurer le format du PDF
                const options = {
                    margin: 5,  // Marge autour du contenu
                    filename: $('#nom').text() + Date.now() + '.pdf', 
                    image: { type: 'jpeg', quality: 0.98 }, 
                    html2canvas: { scale: 2 }, 
                    jsPDF: { 
                        unit: 'mm',  // (mm, cm, in, pt)
                        format: 'a6' /*[100, pageHeight]*/,  // Format de page, peut être 'a4'/[100, 150], 'letter',
                        orientation: 'portrait',  // (portrait ou landscape)
                        compress : true,
                        autoPrint : true
                    }
                };

                // Générer le PDF avec les options
                html2pdf().from(element).set(options).save();
            })
        })
    </script>
<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
