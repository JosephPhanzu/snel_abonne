<?php

    use App\Marge;
    use App\Taux;
    use App\Produit;

    $marge = (new Marge())->getByCode($_GET['ph']);
    $taux = (new Taux())->getTauxByCode($_GET['ph']);
    $produit = (new Produit())->getByPharma($_GET['ph']);

	$taux_actuelle = $taux['taux'] ?? 2800;

    $somme_achat = $quantite_unite = $somme_vente = 0;
    foreach ($produit as $prod) :
        $somme_achat += $prod['prix_achat'];
        $quantite_unite += $prod['quantite'];
    endforeach;

	$somme_vente = $somme_achat * (1 + ($marge['marge'] / 100));

    
    $css = '/assets/css/styles.css';
    $title = "Comptabilité | WILLPHARMA";
    
    ob_start();
?>
    <div class="container mx-auto p-4 space-y-6">
        <input type="hidden" value="<?= $_GET['ph'] ?>" id="code_pharma">
        <!-- Header -->
        <div class="flex justify-between items-center bg-primary text-primary-foreground p-6 rounded-lg shadow-lg mb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <i data-lucide="pill" class="h-8 w-8"></i>
                    <h1 class="font-bold text-3xl">Comptanbilité WillPharma</h1>
                </div>
                <div class="flex items-center gap-2 text-primary-foreground/80">
                    <i data-lucide="calculator" class="h-5 w-5"></i>
                    <p class="text-lg">Module de Comptabilité & Inventaire</p>
                </div>
            </div>
            <div class="div">
                <div class="nav-tabs">
                    
                    <button data-page="inventaire" class="btn-compa nav-tab bg-gray-700/50 text-blue-100 p-2 hover:bg-gray-700/70">
                        <i class="fas fa-boxes"></i>
                        Inventaire
                    </button>
                    <button data-page="rapport" class="btn-compa nav-tab bg-gray-700/50 text-blue-100 p-2 hover:bg-gray-700/70">
                        <i class="fas fa-chart-bar"></i>
                        Rapports Produits
                    </button>
                </div>
            </div>
        </div>

        
        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Margin Manager -->
            <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 text-card-foreground rounded-lg shadow-sm position-relative">
                <div class="position-absolute border-blue-500/30 rounded-lg shadow-sm p-3 d-none col-md-4" id="info-marge"></div>
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-2xl font-semibold leading-none tracking-tight flex items-center gap-2 text-blue-200">
                        <i data-lucide="percent" class="h-5 w-5 text-warning"></i>
                        Gestion de la Marge
                    </h3>
                    <p class="text-sm text-gray-100">
                        Configurez la marge bénéficiaire globale pour tous les produits
                    </p>
                </div>
                <div class="p-6 pt-0 space-y-4">
                    <div class="space-y-2">
                        <label for="margin" class="text-sm text-gray-100 font-medium leading-none">Marge globale (%)<span class="text-red-600 text-xl">*</span></label>
                        <div class="flex gap-2">
                            <input
                                id="marge"
                                type="number"
                                placeholder="Ex: 30"
                                min="0"
                                max="500"
                                step="0.1"
                                class="bg-black/40 border-blue-500/30 text-blue-200 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <button id="btn-marge" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-warning text-warning-foreground hover:bg-warning/90 h-10 px-4 py-2">
                                <i data-lucide="save" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-3 bg-accent rounded-md">
                            <p class="text-sm font-medium text-accent-foreground">Marge actuelle</p>
                            <p class="text-lg font-bold text-accent-foreground" id="current-margin"><?= $marge['marge'] ?? '' ?>%</p>
                        </div>
                        
                        <div class="p-3 bg-muted rounded-md">
                            <p class="text-sm font-medium text-muted-foreground">Exemple de prix</p>
                            <p class="text-lg font-bold text-muted-foreground" id="price-example">100$ → <?= 100 * (1 + ($marge['marge'] / 100)) ?? '' ?>$</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Currency Converter -->
            <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 text-card-foreground rounded-lg shadow-sm position-relative">
                <div class="position-absolute border-blue-500/30 rounded-lg shadow-sm p-3 d-none col-md-4" id="info-taux"></div>
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-2xl text-blue-200 font-semibold leading-none tracking-tight flex items-center gap-2">
                        <i data-lucide="dollar-sign" class="h-5 w-5 text-warning"></i>
                        Taux de Change USD/CDF
                    </h3>
                    <p class="text-sm text-gray-100">
                        Configurez le taux de change pour les conversions automatiques
                    </p>
                </div>
                <div class="p-6 pt-0 space-y-4">
                    <div class="space-y-2">
                        <label for="rate" class="text-sm text-gray-100 font-medium leading-none">Taux de change (1 USD = ? CDF)<span class="text-red-600 text-xl">*</span></label>
                        <div class="flex gap-2">
                            <input
                                id="taux"
                                type="number"
                                placeholder="Ex: 2800"
                                min="1"
                                step="0.01"
                                class="bg-black/40 border-blue-500/30 text-blue-200 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <button id="btn-taux" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-warning text-warning-foreground hover:bg-warning/90 h-10 px-4 py-2">
                                <i data-lucide="save" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-3 bg-accent rounded-md">
                            <p class="text-sm font-medium text-accent-foreground">Taux actuel</p>
                            <p class="text-lg font-bold text-accent-foreground" id="current-rate">1 USD = <?= $taux_actuelle != null || $taux_actuelle != "" ? number_format($taux_actuelle, 2, ',', ' ') : '' ?> CDF</p>
                        </div>
                        
                        <div class="p-3 bg-muted rounded-md">
                            <p class="text-sm font-medium text-muted-foreground">Exemple de conversion</p>
                            <p class="text-lg font-bold text-muted-foreground" id="conversion-example">100 USD = <?= $taux_actuelle != null || $taux_actuelle != "" ? number_format(100 * $taux_actuelle, 2, ',', ' ') : '' ?> CDF</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      
      
      	<!-- Stock Value Display -->
        <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 text-card-foreground rounded-lg shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="text-2xl text-blue-200 font-semibold leading-none tracking-tight flex items-center gap-2">
                    <i data-lucide="trending-up" class="h-5 w-5 text-primary"></i>
                    Valeur du Stock
                </h3>
                <p class="text-sm text-gray-100">
                    Vue d'ensemble de la valeur totale de votre inventaire
                </p>
            </div>
            <div class="p-6 pt-0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-4 bg-muted rounded-lg">
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="package" class="h-4 w-4 text-muted-foreground"></i>
                            <span class="text-sm font-medium text-muted-foreground">Total Produits</span>
                        </div>
                        <p class="text-2xl font-bold"><?= COUNT($produit) ?? '00' ?></p>
                        <p class="text-sm text-muted-foreground"><?= $quantite_unite ?? "" ?> unités</p>
                    </div>
                    
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="shopping-cart" class="h-4 w-4 text-blue-600"></i>
                            <span class="text-sm font-medium text-blue-600">Valeur d'Achat</span>
                        </div>
                        <p class="text-2xl font-bold text-blue-600" id="purchase-usd">$<?= $taux_actuelle !== null ? number_format($somme_achat / $taux_actuelle, 2, ',', ' ') : '' ?></p>
                        <p class="text-sm text-blue-500" id="purchase-cdf"><?= number_format($somme_achat, 2, ',', ' ') ?> CDF</p>
                    </div>
                    
                    <div class="p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="tag" class="h-4 w-4 text-green-600"></i>
                            <span class="text-sm font-medium text-green-600">Valeur de Vente</span>
                        </div>
                        <p class="text-2xl font-bold text-green-600" id="sale-usd">$<?=  $taux_actuelle !== null ? number_format($somme_vente / $taux_actuelle, 2, ',', ' ') : '' ?></p>
                        <p class="text-sm text-green-500" id="sale-cdf"><?= number_format($somme_vente, 2, ',', ' ') ?> CDF</p>
                        
                    </div>
                    
                    <div class="p-4 bg-yellow-50 rounded-lg">
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="trending-up" class="h-4 w-4 text-yellow-600"></i>
                            <span class="text-sm font-medium text-yellow-600">Bénéfice Potentiel</span>
                        </div>
                        <p class="text-2xl font-bold text-yellow-600" id="profit-usd">$<?= number_format(($somme_vente / $taux_actuelle) - ($somme_achat / $taux_actuelle), 2, ',', ' ') ?></p>
                        <p class="text-sm text-yellow-500" id="profit-cdf"><?= number_format($somme_vente - $somme_achat, 2, ',', ' ') ?> CDF</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>

        $(document).ready(function(){

            
            let code_pharma = $('#code_pharma').val();

            $(document).on('click', '.btn-compa', function(){
                let page = $(this).data('page')+'?ph='+code_pharma;

                location.href = page;
                
            });

            // Add Marge
            $(document).on('click', '#btn-marge', async function(e){
                e.preventDefault();
                const marge = $('#marge').val();
                const formData = new FormData();
                formData.append('marge', marge);
                formData.append('code_pharmacie', code_pharma);
                formData.append('csrf', $('#token').val());
                try {
                    const res = await axios.post('/add_marge', formData);
                    console.log(res.data.message);
                    $('#info-marge').removeClass('d-none');
                    if(res.data.status === "success"){
                        $('#info-marge').removeClass('error').addClass('success').hide().fadeIn("slow");
                        $('#marge').val('');
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }else{
                        $('#info-marge').removeClass('success').addClass('error').hide().fadeIn("slow");
                    }
                    setTimeout(() => {
                        $('#info-marge').addClass('d-none');
                    }, 2000);
                    $('#info-marge').html(res.data.message);
                } catch (error) {
                    
                }
            })

            // Add Taux
            $(document).on('click', '#btn-taux', async function(e){
                e.preventDefault();
                const taux = $('#taux').val();
                const formData = new FormData();
                formData.append('taux', taux);
                formData.append('code_pharmacie', code_pharma);
                formData.append('csrf', $('#token').val());
                try {
                    const res = await axios.post('/add_taux', formData);
                    console.log(res.data.message);
                    $('#info-taux').removeClass('d-none');
                    if(res.data.status === "success"){
                        $('#info-taux').removeClass('error').addClass('success').hide().fadeIn("slow");
                        $('#taux').val('');
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }else{
                        $('#info-taux').removeClass('success').addClass('error').hide().fadeIn("slow");
                    }
                    setTimeout(() => {
                        $('#info-taux').addClass('d-none');
                    }, 2000);
                    $('#info-taux').html(res.data.message);
                } catch (error) {
                    
                }
            })
        })


    </script>

<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../templete_app/main_templete.php';