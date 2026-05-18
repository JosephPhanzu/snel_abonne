<?php

use App\Employe;
use App\Token_csrf;
$token_csrf = Token_csrf::gererateTokenCsrf();
$employe_inst = new Employe();
$code_user = isset($_GET['ph']) ? htmlspecialchars(filter_input(INPUT_GET, 'ph', FILTER_SANITIZE_SPECIAL_CHARS)) : "";

$info_employes = $employe_inst->getByCode($code_user);

$title = "Team | Welcome";

ob_start();
?>
    <div class="">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white flex items-center">
                <i data-lucide="users" class="h-8 w-8 text-white-600 mr-3"></i>
                Gestion des équipes
            </h1>
            <p class="text-gray-400 mt-2">Gérez les attributions de rôles et permissions</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-200">Total employés</p>
                        <p class="text-2xl font-bold text-blue-100" id="nbr-employes">00</p>
                    </div>
                    <i data-lucide="users" class="h-8 w-8 text-blue-600"></i>
                </div>
            </div>
            <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-200">Pharmaciens</p>
                        <p class="text-2xl font-bold text-blue-100" id="nbr-pharmacien">00</p>
                    </div>
                    <i data-lucide="user-check" class="h-8 w-8 text-green-600"></i>
                </div>
            </div>
            <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-200">Caissier</p>
                        <p class="text-2xl font-bold text-blue-100" id="nbr-caissier">00</p>
                    </div>
                    <i data-lucide="user" class="h-8 w-8 text-purple-600"></i>
                </div>
            </div>
            <div class="bg-black/40 backdrop-blur-sm border-blue-500/30 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-200">Présents</p>
                        <p class="text-2xl font-bold text-gray-100">00</p>
                    </div>
                    <i data-lucide="clock" class="h-8 w-8 text-orange-600"></i>
                </div>
            </div>
        </div>

        <!-- Team List -->
        <div class="bg-black/40 border-blue-500/30 rounded-lg shadow-sm">
            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-blue-200">Liste des employés</h3>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium" data-bs-toggle="modal" data-bs-target="#ajout-employe">
                        <i data-lucide="plus" class="h-4 w-4 mr-2 inline"></i>
                        Ajouter un employé
                    </button>
                </div>
            </div>

            <!-- Modal add employé -->
            <div class="modal fade" id="ajout-employe">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content bg-black/80 backdrop-blur-sm border-blue-500/30 rounded border border-primary">
                        <div class="modal-header">
                            <p class="modal-title text-blue-200">Ajout Employé</p>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body rounded-bottom">
                            <div class="position-absolute border-blue-500/30 rounded-lg shadow-sm p-3 d-none col-md-4" id="info">
                            </div>
                            <form id="add-employe">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <input type="hidden" name="csrf" value="<?= $token_csrf ?>">
                                    <input type="hidden" name="code_pharmacie" id="code_pharma" value="<?= $_GET['ph'] ?>">
                                    <div>
                                        <label class="block text-sm font-medium text-blue-100 mb-2">Nom complet</label>
                                        <input type="text" name="nom" class="bg-black/40 text-blue-100 border-blue-500/30 w-full px-3 py-2 border border-blue-200 rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-blue-100 mb-2">Email</label>
                                        <input type="email" name="email" class="bg-black/40 text-blue-100 border-blue-500/30 w-full px-3 py-2 border border-blue-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-blue-100 mb-2">Téléphone</label>
                                        <input type="tel" name="tel" class="bg-black/40 text-blue-100 border-blue-500/30 w-full px-3 py-2 border border-blue-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-blue-100 mb-2">Poste</label>
                                        <select name="poste" class="bg-black/40 text-blue-100 border-blue-500/30 w-full px-3 py-2 border border-blue-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="pharmacien">Pharmacien</option>
                                            <option value="caissier">Caissier</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-blue-100 mb-2">Mot de passe de session</label>
                                    <input type="password" name="mdp" class="bg-black/40 text-blue-100 border-blue-500/30 w-full px-3 py-2 border border-blue-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="mt-6 text-center">
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                                        Ajouter employé
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau employés -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-black/30 backdrop-blur-sm border-blue-500/30">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Poste</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Tél</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-blue-100 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="render-data">
                        <!-- Data from bdd here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){


            var currentPageP = 1,
            limitP = 10;
            let code_pharma = $('#code_pharma').val();

            // La recuperation de données de fçcons universelle
            const getbi = async (pageP) => {

                const res = await axios.get('/get_employe', {params : {limitP : limitP, page : pageP, code_pharmacie : code_pharma}})

                console.log(res.data.message)
                if (res.data.status == 'success') {
                    
                    if (Array.isArray(res.data.data)){
                        var donnees = Object.values(res.data.data);
                        let total = parseInt(res.data.total);
                        let totalPages = Math.ceil(total / limitP);
                        // let session = res.data.session;
                        let nbr_caisser = donnees.filter(emp => emp.role === "caissier").length,
                            nbr_pharmacien = donnees.filter(emp => emp.role === "pharmacien").length;

                        $('#nbr-employes').text(donnees.length);
                        $('#nbr-caissier').text(nbr_caisser);
                        $('#nbr-pharmacien').text(nbr_pharmacien);
                        
                        tableDatabi(donnees);

                        renderPaginationbi(totalPages, pageP);

                    }else{
                        alert("Erreur : Données reçues non valides." + res.data.message + ' ' + res.data.page);
                    }
                }
                else{
                    $('#render-data').html('<div class="alert alert-warning opacity-75 rounded-lg">'+res.data.message+'</div>');
                }
            }

            // Tableaux de couleur
            let bgColorTable = ['bg-blue-100', 'bg-green-100', 'bg-purple-100', 'bg-red-100', 'bg-yellow-100', 'bg-pink-100'], textColorTable = ['text-blue-600', 'text-green-600', 'bg-purple-600', 'text-red-600', 'text-yellow-600', 'text-pink-600'];

            function tableDatabi(donnees) {
                let i = 0;
                let contenuP = "";
                donnees.forEach(donne => {
                    contenuP += `
                        <tr class="hover:bg-black/20">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 `+ bgColorTable[i] +` rounded-full flex items-center justify-center">
                                        <span class="`+ textColorTable[i] +` font-medium">`+ donne.nom.charAt(0) +`</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-blue-200">${donne.nom}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-200">${donne.role}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-100">${donne.email}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">${donne.telephone}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-blue-600 hover:text-blue-900 mr-4">Modifier</button>
                                <button class="text-red-600 hover:text-red-900" id="supprimer" title="Supprimer ${donne.code}" data-code="${donne.code}">Supprimer</button>
                            </td>
                        </tr>
                    `;
                    i++;
                    if (i > bgColorTable.length) {
                        i = 0;
                    }
                })
                $('#render-data').html(contenuP)
                $('#render-data').hide().fadeIn("slow");
            }

            // La création de la pagination
            function renderPaginationbi(totalPages, currentPageP) {
                let paginationText = '';
                for (let i = 1; i<=totalPages; i++) {
                    paginationText += '<button class="page-link select-bi mx-1 my-2" data-page="'+i+'">'+i+'</button>';
                }
                $('#pagination-bi').html(paginationText);
                $('#pagination-bi button[data-page="'+ currentPageP +'"]').addClass('active');
            }

            // Au clique de bouton de la pagination
            $(document).on('click', '.select-bi', function(){
                const page = $(this).data('page');
                getbi(page);
            })

            getbi(currentPageP);

            $(document).on('click', '#supprimer', async function(e){
                e.preventDefault();

                if (confirm('Voulez-vous vraiment supprimer cet employé')) {
                    let code = $(this).data('code');
                    let formData = new FormData();
                    formData.append('code', code);
                    formData.append('table', 'employe');
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

            $(document).on('submit', '#add-employe', async function(e){
                e.preventDefault();
                const formData = new FormData(this);
                $('#info').removeClass('d-none');
                const res = await axios.post('/add_employe', formData)
                console.log(res.data.message);
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
