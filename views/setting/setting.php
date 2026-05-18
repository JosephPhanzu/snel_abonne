<?php

use App\Pharmacie;
use App\Proprietaire;
use App\Employe;
$role = $session->getRole();
$code_pharmacie = isset($_GET['ph']) ? filter_input(INPUT_GET, 'ph', FILTER_SANITIZE_SPECIAL_CHARS) : null;
$pharmacie = (new Pharmacie())->findOne($code_pharmacie) ?? null;

$user_info = $session->getRole() === 'proprietaire' ? (new Proprietaire())::getByCode($session->getUserCode()) : (new Employe())::getByCode($session->getUserCode());

$title = "Settings | Welcome";

ob_start();
?>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-200 flex items-center">
            <i data-lucide="settings" class="h-8 w-8 text-blue-100 mr-3"></i>
            Paramètres
        </h1>
        <p class="text-blue-100 mt-2">Configurez votre application WillPharma</p>
    </div>

    <div class="space-y-8">
        <!-- Profile Settings -->
        <div class="bg-black/40 border-blue-500/30 rounded-lg rounded-lg border shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold text-blue-200 flex items-center">
                    <i data-lucide="user" class="h-5 w-5 mr-2"></i>
                    Profil utilisateur
                </h2>
            </div>
            <form id="update-profile" class="p-6 position-relative">
                <div class="position-absolute border-blue-500/30 rounded-lg shadow-sm p-3 d-none col-md-4" id="info-user"></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 position-relative">
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Nom</label>
                        <input type="text" name="nom" value="<?= $user_info['nom'] ?? '' ?>" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <?php if ($role === 'proprietaire') : ?>
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Prénom</label>
                        <input type="text" name="prenom" value="<?= $user_info['prenom'] ?? '' ?>" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <?php endif; ?>
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Email</label>
                        <input type="email" name="email" value="<?= $user_info['email'] ?? '' ?>" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Téléphone</label>
                        <input type="tel" name="telephone" value="<?= $user_info['telephone'] ?? '' ?>" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <?php if ($role === 'proprietaire') : ?>
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Adresse</label>
                        <input type="text" name="adresse" value="<?= $user_info['adresse'] ?? '' ?>" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <?php endif; ?>
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Rôle</label>
                        <select class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option><?= $role ?? '' ?></option>
                        </select>
                    </div>
                </div>
                <div class="mt-6">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                        Sauvegarder les modifications
                    </button>
                </div>
            </form>
        </div>


        <?php if ($role === 'proprietaire' && $pharmacie) : ?>
        <!-- Pharmacy Settings -->
        <div class="bg-black/40 border-blue-500/30 rounded-lg rounded-lg border shadow-sm" id="information-pharma">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold text-blue-200 flex items-center">
                    <i data-lucide="building-2" class="h-5 w-5 mr-2"></i>
                    Informations pharmacie
                </h2>
            </div>
            <div class="p-6">
                <form id="update-pharmacie" class="grid grid-cols-1 md:grid-cols-2 gap-6 position-relative">
                    <div class="position-absolute border-blue-500/30 rounded-lg shadow-sm p-3 d-none col-md-4" id="info"></div>
                    <input type="hidden" name="code_pharmacie" value="<?= $pharmacie['code'] ?>">
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Nom de la pharmacie</label>
                        <input type="text" name="nom" value="<?= $pharmacie['nom'] ?>" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Adresse</label>
                        <input type="text" name="adresse" value="<?= $pharmacie['adresse'] ?>" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-blue-100 mb-2">Type de pharmacie</label>
                        <select name="type" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="depot"><?= $pharmacie['type_pharmacie'] ?></option>
                            <option value="<?= $pharmacie['type_pharmacie'] === 'depot' ? 'autre' : 'depot' ?>"><?= $pharmacie['type_pharmacie'] === 'depot' ? 'Autre' : 'Dépôt' ?></option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-blue-100 mb-2">Déscription</label>
                        <textarea type="text" name="description" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= $pharmacie['description'] ?></textarea>
                    </div>
                    <div class="mt-1">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                            Sauvegarder les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- Security Settings -->
        <div class="bg-black/40 border-blue-500/30 rounded-lg rounded-lg border shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold text-blue-200 flex items-center">
                    <i data-lucide="shield" class="h-5 w-5 mr-2"></i>
                    Sécurité
                </h2>
            </div>
            <form id="update-security" class="p-6 position-relative">
                <div class="position-absolute border-blue-500/30 rounded-lg shadow-sm p-3 d-none col-md-4" id="info-mdp"></div>
                <div>
                    <label class="block text-sm font-medium text-blue-100 mb-2">Ancien mot de passe</label>
                    <input type="password" name="anc_mdp" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 position-relative">
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Nouveau mot de passe</label>
                        <input type="password" name="nouveau_mdp" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-2">Confirmer le mot de passe</label>
                        <input type="password" name="confirme_mdp" class="bg-black/40 text-blue-200 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mt-6">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                        Changer le mot de passe
                    </button>
                </div>
            </form>
            <div class="flex items-center justify-between p-6">
                <div>
                    <div class="font-medium text-blue-200">Authentification à deux facteurs</div>
                    <div class="text-sm text-gray-500">Ajouter une couche de sécurité supplémentaire</div>
                </div>
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium text-sm">
                    Activer
                </button>
            </div>
            
        </div>

    </div>
    <script>
        $(document).ready(function(){

            // Update Info pharmacie
            $(document).on('submit', '#update-pharmacie', async function(e){
                e.preventDefault();
                const formData = new FormData(this);
                
                const res = await axios.post('/update_pharmacie', formData)
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

            // Update Info user
            $(document).on('submit', '#update-profile', async function(e){
                e.preventDefault();
                const formData = new FormData(this);
                
                const res = await axios.post('/update_user', formData)
                console.log(res.data.message);
                $('#info-user').removeClass('d-none');
                if (res.data.status === "success") {
                    $('#info-user').removeClass('error').addClass('success').hide().fadeIn("slow");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);

                }else{
                    $('#info-user').removeClass('success').addClass('error').hide().fadeIn("slow");
                }
                $('#info-user').html(res.data.message);

            })

            $(document).on('submit', '#update-security', async function(e){
                e.preventDefault();
                const formData = new FormData(this);
                
                const res = await axios.post('/update_password', formData)
                console.log(res.data.message);
                $('#info-mdp').removeClass('d-none');
                if (res.data.status === "success") {
                    $('#info-mdp').removeClass('error').addClass('success').hide().fadeIn("slow");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);

                }else{
                    $('#info-mdp').removeClass('success').addClass('error').hide().fadeIn("slow");
                }
                $('#info-mdp').html(res.data.message);

            })
        })
    </script>
<?php
$content = ob_get_clean();
require __DIR__ . ($role === 'proprietaire' ? "/../templete_app/admin_templete.php" : "/../templete_app/main_templete.php");
