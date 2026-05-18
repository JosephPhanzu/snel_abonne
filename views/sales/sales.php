<?php

$title = "Convention | WillPharma";
use App\Token_csrf;

$role = $session->getRole();
$token_csrf = (new Token_csrf())->gererateTokenCsrf();

ob_start();
?>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-200 flex items-center">
            <i data-lucide="trending-up" class="h-8 w-8 text-blue-100 mr-3"></i>
            Suivi des ventes
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
                    <p class="text-sm font-medium text-blue-200">Clients</p>
                    <p class="text-2xl font-bold text-blue-100" id="nbr-client">00</p>
                </div>
                <i data-lucide="users" class="h-8 w-8 text-orange-600"></i>
            </div>
            <div class="mt-2">
                <!-- <span class="text-sm text-green-600">+12% vs hier</span> -->
            </div>
        </div>
    </div>
    <input type="hidden" value="<?= $_GET['ph'] ?>" id="code_pharma">
    <input type="hidden" name="csrf" value="<?= $token_csrf ?>" id="token">
    <?php if ($session->isUserConnected()) : ?>
    <!-- Search and Filter -->
    <div class="bg-black/40 border-blue-500/30 rounded-lg border shadow-sm p-2 mb-8 position-relative">
        <h3 class="text-blue-100 text-xl text-center">Séléctionné le type de vente à effectuer</h3>
        <div class="flex flex-col md:flex-row gap-3 items-center justify-around">
            <label for="vente-ord" class="gap-4 bg-blue-600 hover:bg-blue-700 p-2 rounded-md my-3 text-blue-100 text-xl hover:cursor-pointer vente vente-ord">
                vente ordinaire
            </label>
            <input type="radio" name="type_vente" id="vente-ord" class="hidden">

            <label for="vente-conv" class="gap-4 bg-green-600 hover:bg-green-700 p-2 rounded-md my-3 text-blue-100 text-xl hover:cursor-pointer vente vente-conv">
                vente convention
            </label>
            <input type="radio" name="type_vente" id="vente-conv" class="hidden">
        </div>
        <input type="hidden" name="type_selectionne" id="type-selectionned" value="">
    </div>


    <div class="bg-black/40 border-blue-500/30 rounded-lg border shadow-sm p-6 mb-8 position-relative hidden" id="zone-vente">
        <div class="position-absolute border-blue-500/30 rounded-lg shadow-sm p-3 d-none col-md-4" id="info"></div>

        <div class="flex justify-center flex-col md:flex-row gap-4 my-2" id="liste-conv">
            <div class="flex justify-between text-black text-sm">
                <select name="code_conv" class="bg-white/10 border-blue-900/30 text-blue-100 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-md" id="contenu-conv">
                    
                </select>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" id="search" placeholder="Rechercher un produit..." class="bg-black/40 border-blue-500/30 text-blue-100 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                
            </div>
        </div>

        <div class="bg-black/40 border-blue-500/30 rounded-lg border shadow-sm mt-2">
            <div class="grid grid-cols-2 p-6 border-b">
                <h3 class="text-lg font-semibold text-blue-200">Liste des produits</h3>
                <div class="flex flex-row justify-end panier-count">
                    <i class="fa-solid fa-cart-plus fs-4 text-indigo-500"></i>
                    <span class="badge bg-red-500 rounde-circle pt-2">0</span>
                    <div class="ms-2">
                        <i class="fa-solid fa-trash fs-4 text-red-500 cursor-pointer" title="Vider le panier" id="vider-panier"></i>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-black/30 border-blue-500/30">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Produit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Stock Boite</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Stock Plaquette</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Expiration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200" id="render-data">
                                <!-- Data from bdd here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Détail de la facture -->
                 
                <div class="m-2">
                    <div id="invoice-detail" class="bg-white rounded-lg border shadow-sm p-3">
                        
                        <div id="facture-zone">
                            <div class="my-3">
                                <h3 class="font-medium text-gray-600">Détail de la facture</h3>
                                <p class="text-gray-600">FAC-2024-<span id="num-facture">001</span></p>
                                <hr>
                            </div>
                            <div class="mt-2">
                            <h4 class="font-medium text-gray-600">Client</h4>
                            <p class="text-gray-600"><input type="text" id="nom" autocomplete="off" placeholder="Nom client ici" class="border-none outline-none" /></p>
                            </div>
                            
                            <div class="mt-2">
                            <h4 class="font-medium text-gray-600">Date / Heure</h4>
                            <p class="text-gray-600" id="date">
                                <?= date('d/m/Y H:i') ?>
                            </p>
                            </div>

                            <div class="mt-2">
                            <h4 class="font-medium text-black mb-2">Articles</h4>
                            <div class="space-y-2 text-black" id="render-article">
                                <!-- Article séléctioné ici -->
                            </div>
                            </div>

                            <div class="pt-2 border-t my-2">
                                <div class="flex justify-between font-medium">
                                    <span class="text-black">Total</span>
                                    <span class="text-green-600" id="totalPrix">0,00 Fc</span>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-gray-600"><input type="tel" id="tel" autocomplete="off" maxlength="10" placeholder="Numéro de téléphone" class="border-none outline-none" /></p>
                        <div class="pt-4 mt-2">
                        <Button 
                            class="w-full bg-blue-600 hover:bg-blue-700 rounded-full p-2 text-white" id="btn-vente"
                        >
                            Vente / <i class="fa-solid fa-download"></i>
                            PDF
                        </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php endif; ?>

    <div class="bg-black/40 border-blue-500/30 rounded-lg border shadow-sm p-2 mb-8 position-relative">
        
        <div class="flex flex-col md:flex-row gap-3 items-center justify-around">
            <h3 class="text-blue-100 text-xl">Séléctionné les type de facture que vous souhaitez voir</h3>
            <button id="vente-ord" class="gap-4 bg-blue-600 hover:bg-blue-700 p-2 rounded-md my-3 text-blue-100 text-xl hover:cursor-pointer voir-vente">
                Ordinaire
            </button>

            <button id="vente-conv" class="gap-4 bg-green-600 hover:bg-green-700 p-2 rounded-md my-3 text-blue-100 text-xl hover:cursor-pointer voir-vente">
                Convention
            </button>
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

    <?php if ($session->isProprioConnected()) : ?>
    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Sales Chart -->
        <div class="bg-black/40 border-blue-500/30 rounded-lg border shadow-sm p-6">
            <h3 class="text-lg font-semibold text-blue-200 mb-4">Évolution des ventes (7 jours)</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded">
                <p class="text-gray-500">Graphique des ventes</p>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-black/40 border-blue-500/30 rounded-lg border shadow-sm p-6">
            <h3 class="text-lg font-semibold text-blue-200 mb-4">Produits les plus vendus</h3>
            <div class="space-y-4" id="plus-vendu">
                <!-- Top produit here -->
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- CDN pour html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <!-- html2pdf lib -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script>
        $(document).ready(function(e){

            let code_pharma = $('#code_pharma').val();

            const getConvention = async() => {

                const res = await axios.get('/get_convention', {params : {code_pharmacie : code_pharma}});
                    
                if (res.data.status === 'success') {

                    console.log(res.data.message);
                    
                    var convention = Object.values(res.data.data);
                    var contenu = '';
                    if (Array.isArray(convention)) {

                        convention.forEach(conv => {
                            contenu += `
                                <option class="w-full" value="${conv.code}">${conv.nom}</option>
                            `;
                        });
                        $('#contenu-conv').html(contenu);

                    }else{
                        alert('Le resultat retourné n\'est pas un tableau');
                    }
                }else{
                    console.log(res.data.message);
                    // $('tbody').html('<div class="alert alert-warning col-12 my-2">Le Panier est vide</div>')
                }
            }

            // Gestion des types de vente
            $(document).on('click', '.vente', function(e){
                e.preventDefault();
                // $('#zone-vente-convention').addClass('hidden');
                $('#zone-vente').removeClass('hidden');
                if ($(this).hasClass('vente-ord')) {
                    $('#liste-conv').addClass('hidden');
                } else if ($(this).hasClass('vente-conv')) {
                    $('#liste-conv').removeClass('hidden');
                    getConvention();
                }
                $('#type-selectionned').val($(this).text().trim());
            });

            // Recherche produit pour vendre
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
                        $('#render-data').html('<div class="alert alert-warning rounded-0">'+res.data.message+'</div>')
                    }
                }else{
                    $('#render-data').html('');
                }
            });
            // Tableau de produits
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.qte_boite ?? '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.quantite}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${donne.date_peremption}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <p>
                                    <button class="text-blue-600 hover:text-blue-900 mr-1 ajout-panier" title="Ajouter boite" data-code="${donne.code}" data-id="boite" data-action="ajout-panier" data-autre="boite"><i class="fa-solid fa-plus"></i><i class="fa-solid fa-box-open"></i></button>

                                    <button class="text-blue-600 hover:text-blue-900 mr-1 ajout-panier" title="Ajouter plaquette" data-code="${donne.code}" data-id="plaquette" data-action="ajout-panier" data-autre="plaquette"><i class="fa-solid fa-plus"></i><i class="fa-solid fa-pills"></i></button>

                                    <button class="text-blue-600 hover:text-blue-900 mr-1 ajout-panier" title="Ajouter démi plaquette" data-code="${donne.code}" data-id="plaquette" data-autre="demi_plaquette" data-action="ajout-panier"><i class="fa-solid fa-plus"></i><i class="fa-solid fa-circle-half-stroke"></i></button>
                                </p>
                                
                                <p>
                                    <button class="text-red-600 hover:text-red-900 mr-1" title="Supprimer boite" data-code="${donne.code}" id="supprimer"><i class="fa-solid fa-trash"></i><i class="fa-solid fa-box-open"></i></button>
                                    <button class="text-red-600 hover:text-red-900 mr-1" title="Supprimer plaquette" data-code="${donne.code}" id="supprimer"><i class="fa-solid fa-trash"></i><i class="fa-solid fa-pills"></i></button>
                                </p>
                                
                            </td>
                        </tr>
                    `;
                    i++;
                })
                $('#render-data').html(contenuP);
                $('#render-data').hide().fadeIn("slow");
            }


            function mettreAJourPanierBadge(totalProduits) {
                $('.panier-count .badge').html(totalProduits);
            }

            setInterval(mettreAJourPanierBadge, 500);

            // Ajout au panier
            $(document).on('click', '.ajout-panier', async function (e) {
                e.preventDefault();

                const code_produit = $(this).data('code'),
                      type = $(this).data('id'),
                      demi = $(this).data('autre');
                
                let etat_demi = demi === "demi_plaquette" ?  1 : 0;
                console.log(etat_demi);
                const formData = new FormData();
                formData.append('code_produit', code_produit);
                formData.append('quantite', 1);
                formData.append('type_qte', type)
                formData.append('code_pharmacie', code_pharma);
                formData.append('demi_plaquette', etat_demi)

                const res = await axios.post('/ajout_panier', formData);

                if (res.data.status === "success") {
                    mettreAJourPanierBadge(res.data.totalProduits);
                }
                console.log(res.data.message);
                    
            });

            // Mettre à jour la quantité de produit
            $(document).on('click', '#update', async function(e){

                var code_produit = $(this).data('code');
                var quantite = $('input[name="quantite"][data-code="'+ code_produit +'"]').val();
                var data = {code_produit, quantite};

                const res = await axios.post('/mettre_a_jourPanier', data);
    
                if (response.data.status === 'success') {
                    getPanier();
                }
                console.log(response.data.message);
            })

            // Supprimer un produit du panier
            $(document).on('click', '#soustraire-panier', async function(e){
                e.preventDefault();
                var code_produit = $(this).data('code');

                const res = await axios.post('/soustraire_produit', {code_produit : code_produit});
                    
                if (res.data.status === 'success') {
                    getPanier();
                }
                console.log(res.data.message);
            });

            // Supprimer un produit du panier
            $(document).on('click', '#supprimer', async function(e){
                e.preventDefault();
                var code_produit = $(this).data('code');

                const res = await axios.post('/retirer_produit', {code_produit : code_produit});
                if (res.data.status === 'success') {
                    getPanier();
                }
                console.log(res.data.message);
            });

            // Vider tous les produits du panier
            $(document).on('click', '#vider-panier', async function(e){
                e.preventDefault();

                const res = await axios.get('/vider_panier');
                console.log(res.data.message);
                if (res.data.status === 'success') {
                    window.location.reload();
                }
            });

            const getPanier = async() => {

                const res = await axios.get('/get_panier');
                    
                if (res.data.status === 'success') {

                    console.log(res.data.message);
                    var totalPrix = res.data.total;
                    $('#totalPrix').text(totalPrix+'Fc');
                    mettreAJourPanierBadge(res.data.totalProduits);
                    
                    var produits = Object.values(res.data.data);
                    var contenu = '';
                    if (Array.isArray(produits)) {

                        produits.forEach(produit => {
                            contenu += `
                                <div class="flex justify-between text-black text-sm">
                                    <span><span>Type : ${produit.type_qte}</span><br /> ${produit.nom} <span class="ms-3">${produit.type_qte === "boite" ? Math.round((produit.prix_unitaire * produit.qte_par_boite) * 100) / 100  : produit.prix_unitaire} Fc X ${produit.type_qte === "boite" ? (produit.quantite / produit.qte_par_boite).toFixed(2) : produit.quantite}</span></span>
                                    <span>`+ (produit.prix_unitaire * produit.quantite).toFixed(2) + `Fc</span>
                                </div>
                            `;
                        });
                        $('#render-article').html(contenu);   
                    }else{
                        alert('Le resultat retourné n\'est pas un tableau');
                    }
                }else{
                    console.log(res.data.message);
                    // $('tbody').html('<div class="alert alert-warning col-12 my-2">Le Panier est vide</div>')
                }
            }
            getPanier();
            setInterval(getPanier, 500);


            $(document).on('click', '#btn-vente', async function(e){
                e.preventDefault();

                let type_vente = $('#type-selectionned').val();
                let code_conv = "";
                if (type_vente === "vente convention") {
                    code_conv = $('#contenu-conv').val();
                }
                
                let nom = $('#nom').val(), tel = $('#tel').val();
                let formData = new FormData();
                formData.append('nom', nom);
                formData.append('tel', tel);
                formData.append('type_vente', type_vente);
                formData.append('code_pharmacie', code_pharma);
                formData.append('code_conv', code_conv);

                const res = await axios.post('/add_vente', formData);
                console.log(res.data.message);
                $('#info').removeClass('d-none');
                $('#info').html(res.data.message);
                if (res.data.status === 'success') {
                    
                    const element = document.getElementById('facture-zone');
            
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

                    $('#render-article').html('');
                    $('#nom').val('');
                    $('#tel').val('');
                    mettreAJourPanierBadge(0);
                    $('#totalPrix').text('0,00Fc');

                    $('#info').removeClass('error').addClass('success').hide().fadeIn("slow");

                    getVente();

                }else{
                    $('#info').removeClass('success').addClass('error').hide().fadeIn("slow");
                }
                
                setTimeout(() => {
                    $('#info').addClass('d-none');
                }, 5000);
            })
            
            var currentPage = 1,
            limitP = 8;

            // La recuperation de données de fçcons universelle
            const getVente = async (pageP, type_vente) => {
                const res = await axios.get('/get_vente', {params : {limit : limitP, page : pageP, code_pharmacie : code_pharma, jour : jour, mois : mois, type_vente: type_vente}});
                console.log(res.data.message)
                if (res.data.status === 'success') {
                    
                    if (Array.isArray(res.data.data)){
                        var donnees = Object.values(res.data.data);
                        let all = Object.values(res.data.total);
                        let plusVendu = Object.values(res.data.plusVendu);

                        let total = all.length;
                        let totalPages = Math.ceil(total / limitP);

                        let produitsMap = {};
                        // Étape 1 et 2 : regrouper et compter
                        let totalVendu = 0;
                        plusVendu.forEach(vente => {
                            
                            const nom = vente.nom_produit;
                            const qte = parseInt(vente.quantite) || 1;
                            const prix = parseInt(vente.prix);

                            if (produitsMap[nom]) {
                                produitsMap[nom] += qte;
                            } else {
                                produitsMap[nom] = qte;
                            }
                        });

                        // Étape 3 : trier
                        let topProduits = Object.entries(produitsMap)
                            .sort((a, b) => b[1] - a[1]) // du plus vendu au moins vendu
                            .slice(0, 4); // Étape 4 : top 4
                        let plusVentduText = '';
                        topProduits.forEach(([produit, total, prix]) => {
                            plusVentduText +=`
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium text-blue-200">${produit}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-green-600">${total} unités</div>
                                    </div>
                                </div>
                            `;
                            console.log(`${produit} : ${total} unités`);
                        });
                        $('#plus-vendu').html(plusVentduText);

                        // alert(total)
                        all.map(vente => {
                            totalVendu += vente.total;
                        });

                        let nbr_client = all.filter(vente => vente.code_pharmacie === code_pharma).length;
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

            let type_vente = '';

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
                getVente(currentPage, type_vente);
            });

            $('#mois-choisi').on('change', function(){
                jour = null;
                mois = $(this).val();
                $('#titre-vente-jour').text('CA du Mois '+mois);
                $('#titre-total-vente').text("CA du Mois "+mois);
                getVente(currentPage, type_vente);
            })

            // getVente(currentPage);

        })
    </script>
<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
