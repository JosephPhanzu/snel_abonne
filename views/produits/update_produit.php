<?php

use App\Pharmacie;
use App\Produit;
use App\Token_csrf;
$token_csrf = Token_csrf::gererateTokenCsrf();
$pharmacie = (new Pharmacie())->findOne($_GET['ph']);
$produit = (new Produit())->getByCode($_GET['pr']);

$role = $session->getRole();

if (!$session->isProprioConnected() || $role !== 'proprietaire') {
    $session->logout();
    header('Location: /login');
    exit;
}

$title = "Modifier produit | Welcome";

ob_start();
?>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-200 flex items-center">
            <i data-lucide="settings" class="h-8 w-8 text-blue-100 mr-3"></i>
            Modification
        </h1>
        <p class="text-blue-100 mt-2">Modification du produit <?= $produit['nom'] ?></p>
    </div>

    <div class="space-y-8">
        <!-- Profile Settings -->
        <div class="bg-black/40 border-blue-500/30 rounded-lg rounded-lg border shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold text-blue-200 flex items-center">
                    <i data-lucide="user" class="h-5 w-5 mr-2"></i>
                    Marge de bénéfice pour ce produit
                </h2>
            </div>
            <div class="p-6 position-relative">
                <div class="position-absolute border-blue-500/30 rounded-lg shadow-sm p-3 d-none col-md-4" id="info"></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Marge de bénéfice</label>
                        <input type="text" placeholder="Marge de bénéfice" id="marge" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                <div class="mt-6">
                    <button id="update-marge" class="update-marge bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                        Appliquer
                    </button>
                    <button id="update-marge-null" class="update-marge bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium">
                        Utiliser la marge global
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-8">

        <?php if ($session->isProprioConnected()) : ?>
        <!-- Pharmacy Settings -->
        <div class="bg-black/40 border-blue-500/30 rounded-lg rounded-lg border shadow-sm" id="information-pharma">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold text-blue-200 flex items-center">
                    <i data-lucide="building-2" class="h-5 w-5 mr-2"></i>
                    Mettre à jour la quantité
                </h2>
            </div>
            <div class="p-6 position-relative">
                <form id="modifier-quantite" class="form grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="position-absolute border-blue-500/30 rounded-lg shadow-sm p-3 d-none col-md-4" id="infoQ"></div>
                    <input type="hidden" name="csrf" value="<?= $token_csrf ?>">
                    <input type="hidden" name="code_pharmacie" id="code_pharma" value="<?= $_GET['ph'] ?>">
                    <input type="hidden" name="code_prouit" id="code_produit" value="<?= $_GET['pr'] ?>">

                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-blue-100 mb-2">Prix d'achat</label>
                        <input type="number" min="0" max="9999999" name="prix_achat" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="col-md-12">
                        <label class="text-blue-200 py-2">En boite?</label>
                        <div class="form-check">
                            <input type="radio" required name="contien_boite" id="oui" class="form-check-input radio">
                            <label for="oui" class="form-check-label text-blue-100">Oui</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" required name="contien_boite" id="non" class="form-check-input radio">
                            <label for="non" class="form-check-label text-blue-100">Non</label>
                        </div>
                    </div>

                    <div id="contenuSiBoite">
                    
                    </div>

                    <div class="mt-1 md:col-span-2">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium w-100">
                            Sauvegarder les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="space-y-8">

        <?php if ($session->isProprioConnected()) : ?>
        <!-- Pharmacy Settings -->
        <div class="bg-black/40 border-blue-500/30 rounded-lg rounded-lg border shadow-sm" id="information-pharma">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold text-blue-200 flex items-center">
                    <i data-lucide="building-2" class="h-5 w-5 mr-2"></i>
                    Informations produit
                </h2>
            </div>
            <div class="p-6 position-relative">
                <form id="modifier-produit" class="form grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="position-absolute border-blue-500/30 rounded-lg shadow-sm p-3 d-none col-md-4" id="infoM"></div>
                    <input type="hidden" name="csrf" value="<?= $token_csrf ?>">
                    <input type="hidden" name="code_pharmacie" id="code_pharma" value="<?= $_GET['ph'] ?>">
                    <input type="hidden" name="code_prouit" id="code_produit" value="<?= $_GET['pr'] ?>">

                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Nom du produit</label>
                        <input type="text" value="<?= $produit['nom'] ?>" name="nom" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Catégorie</label>
                        <input type="text" value="<?= $produit['categorie'] ?>" name="categorie" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Nom scientifique</label>
                        <input type="text" name="nom_scientifique" value="<?= $produit['nom_scientifique'] ?>" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Fournisseur</label>
                        <input type="text" name="fournisseur" value="<?= $produit['fournisseur'] ?>" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Description</label>
                        <textarea  name="description" value="" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($produit['description'], ENT_QUOTES | ENT_HTML5, 'UTF-8') ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Stock minimal</label>
                        <input type="text" value="<?= $produit['stock_min'] ?>" id="stock-min" name="stock_min" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Date de peremption</label>
                        <input type="date" value="<?= $produit['date_peremption'] ?>" name="date_peremption" class="bg-white/10 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Date de peremption</label>
                        <input type="date" name="date_peremption" value="" class="bg-white/10 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div> -->

                    <div class="mt-1 md:col-span-2">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium w-100">
                            Sauvegarder les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <input type="hidden" id="qte_boite" value="<?= $produit['qte_boite'] ?>"><input type="hidden" id="qte_par_boite" value="<?= $produit['qte_par_boite'] ?>"><input type="hidden" id="quantite" value="<?= $produit['quantite'] ?>">
    <script>
        $(document).ready(function(e){
            
            const code_pharma = $('#code_pharma').val(), code_produit = $('#code_produit').val();

            $(document).on('click', '.update-marge', async function(e){
                e.preventDefault();
                let marge;
                if ($(this).attr('id') === "update-marge") {
                    marge = $('#marge').val();
                }else if($(this).attr('id') === "update-marge-null"){
                    marge = null;
                }
                 
                const formData = new FormData();
                formData.append('marge', marge);
                formData.append('code_produit', code_produit);

                const res = await axios.post('/update_marge', formData);
                console.log(res.data.message);
                $('#info').removeClass('d-none');
                if(res.data.status === "success"){
                    $('#info').removeClass('error').addClass('success').hide().fadeIn("slow");
                    $('#marge').val('');
                }else{
                    $('#info').removeClass('success').addClass('error').hide().fadeIn("slow");
                }
                setTimeout(() => {
                    $('#info').addClass('d-none');
                }, 2000);
                $('#info').html(res.data.message);
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
                        <input type="number" value="" name="qte_boite" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Nombre de plaquette par boite</label>
                        <input type="number" value="" name="qte_par_boite" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                `;
              }else if ($(this).attr('id') === "non") {
                
                contenu = `
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Stock(Nombre d'élément)</label>
                        <input type="number" value="" name="stock" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                `;
              }

              $('#contenuSiBoite').html(contenu);

            })

            $(document).on('submit', '.form', async function(e){
                e.preventDefault();
                let uri = '', info = "";
                if ($(this).attr('id') === "modifier-produit") {
                    uri = '/update_produit';
                    info = 'infoM';
                }else if ($(this).attr('id') === "modifier-quantite"){
                    uri = '/update_qteProduit';
                    info = 'infoQ';
                }

                let formData = new FormData(this);
                formData.append('code_produit', code_produit);

                const res = await axios.post(uri, formData)
                console.log(res.data.message);
                $('#'+info+'').removeClass('d-none');
                if (res.data.status === "success") {
                    $('#'+info+'').removeClass('error').addClass('success').hide().fadeIn("slow");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);

                }else{
                    $('#'+info+'').removeClass('success').addClass('error').hide().fadeIn("slow");
                }
                $('#'+info+'').html(res.data.message);


            })


        })
    </script>
<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
