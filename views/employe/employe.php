<?php

$title = "Employés | Welcome";
 
ob_start();
?>
    <h2 class="text-xl md:text-2xl font-semibold mb-6 flex items-center gap-2">
      <i class="fa-solid fa-user-plus text-blue-600"></i>
      Formulaire d'enregistrement Employé
    </h2>
    <input type="hidden" value="<?= $session->getRole() ?>" id="role-session" name="">

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-6">

      <div class="bg-white p-4 rounded shadow flex items-center justify-between">
        <div>
          <h3 class="text-gray-500 text-sm">Employés</h3>
          <p class="text-xl md:text-2xl font-bold" id="tot-employe">00</p>
        </div>
        <i class="fa-solid fa-users text-yellow-600 text-xl md:text-2xl"></i>
      </div>

      <div class="bg-white p-4 rounded shadow flex items-center justify-between">
        <div>
          <h3 class="text-gray-500 text-sm">Homme</h3>
          <p class="text-xl md:text-2xl font-bold" id="tot-homme">00</p>
        </div>
        <i class="fa-solid fa-mars text-blue-600 text-xl md:text-2xl"></i>
      </div>

      <div class="bg-white p-4 rounded shadow flex items-center justify-between">
        <div>
          <h3 class="text-gray-500 text-sm">Femme</h3>
          <p class="text-xl md:text-2xl font-bold" id="tot-femme">00</p>
        </div>
        <i class="fa-solid fa-venus text-pink-600 text-xl md:text-2xl"></i>
      </div>

      <div class="bg-white p-4 rounded shadow flex items-center justify-between">
        <div>
          <h3 class="text-gray-500 text-sm">Actif</h3>
          <p class="text-xl md:text-2xl font-bold" id="tot-actif"></p>
        </div>
        <i class="fa-solid fa-user-check text-green-600 text-xl md:text-2xl"></i>
      </div>

      <div class="bg-white p-4 rounded shadow flex items-center justify-between">
        <div>
          <h3 class="text-gray-500 text-sm">Non actif</h3>
          <p class="text-xl md:text-2xl font-bold" id="tot-inactif"></p>
        </div>
        <i class="fa-solid fa-user-xmark text-red-600 text-xl md:text-2xl"></i>
      </div>
    </div>

    <?php if ($session->hasRole('Employeur')) : ?>
    <!-- Form Card -->
    <div class="w-full min-w-3xl bg-white p-4 md:p-6 rounded-2xl shadow-lg">
      <form id="add-employe" class="space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Nom</label>
            <div class="relative">
              <i class="fa-solid fa-user absolute left-3 top-4 text-gray-400"></i>
              <input type="text" class="pl-10 mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Nom" name="nom">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Prénom</label>
            <div class="relative">
              <i class="fa-solid fa-user absolute left-3 top-4 text-gray-400"></i>
              <input type="text" class="pl-10 mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Prénom" name="prenom">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Salaire</label>
            <div class="relative">
              <i class="fa-solid fa-money-bill-wave absolute left-3 top-4 text-gray-400"></i>
              <input type="number" id="salaire" class="pl-10 mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Salaire" name="salaire">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Cotisation</label>
            <div class="relative">
              <i class="fa-solid fa-money-bill-wave absolute left-3 top-4 text-gray-400"></i>
              <input id="cotisation" type="number" class="pl-10 mt-1 w-full bg-gray-100 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" readonly placeholder="Cotisation" name="cotisation">
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Téléphone</label>
            <div class="relative">
              <i class="fa-solid fa-phone absolute left-3 top-4 text-gray-400"></i>
              <input id="cotisation" type="tel" class="pl-10 mt-1 w-full p-2 border rounded-lg" placeholder="Téléphone" name="tel">
            </div>
          </div>

          <div>
          <label class="block text-sm font-medium text-gray-700">Date embauche</label>
          <div class="relative">
            <i class="fa-solid fa-calendar-alt absolute left-3 top-4 text-gray-400"></i>
            <input type="date" class="pl-10 mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" name="date_embauche">
          </div>
        </div>
        </div>

        
        <div>
          <label class="block text-sm font-medium text-gray-700">Matricule</label>
          <div class="relative">
            <i class="fa-solid fa-id-badge absolute left-3 top-4 text-gray-400"></i>
            <input type="text" class="pl-10 mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Matricule" name="matricule">
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Sexe</label>
          <div class="relative">
            <i class="fa-solid fa-toggle-on absolute left-3 top-3 text-gray-400"></i>
            <select class="pl-10 mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" name="sexe">
              <option value="Homme">Homme</option>
              <option value="Femme">Femme</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Valider le paiement</label>
          <div class="relative">
            <!-- <i class="fa-solid fa-toggle-on absolute left-3 top-3 text-gray-400"></i> -->
            <div class="flex space-x-6 mt-2">
              <div class="flex items-center">
                <input type="radio" name="valid_payment" id="oui" value="true" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                <label for="oui" class="ml-2 block text-sm text-gray-900">Oui</label>
              </div>
              <div class="flex items-center">
                <input type="radio" name="valid_payment" id="non" value="false" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                <label for="non" class="ml-2 block text-sm text-gray-900">Non</label>
              </div>
            </div>
          </div>
        </div>

        <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-2xl p-6 mb-6 d-none" id="payment-info">
          <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-credit-card text-blue-600"></i>
            Paiement bancaire
          </h2>

          <!-- Nom du titulaire -->
          <div class="mb-4 relative">
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Nom du titulaire
            </label>
            <i class="fa-solid fa-user absolute left-3 top-9 text-gray-400"></i>
            <input type="text" name="nom_tit_carte"
              class="w-full border rounded-lg pl-10 pr-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="Ex: Elva D'or Kupulu" >
          </div>

          <!-- Numéro de carte -->
          <div class="mb-4 relative">
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Numéro de carte
            </label>
            <i class="fa-solid fa-credit-card absolute left-3 top-9 text-gray-400"></i>
            <input type="text" name="numero_paiement"
              class="w-full border rounded-lg pl-10 pr-3 py-2 tracking-widest focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="1234 5678 9012 3456" maxlength="19" pattern="^[0-9]{4} [0-9]{4} [0-9]{4} [0-9]{4}$">
          </div>

          <!-- Expiration + CVV -->
          <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="relative">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Expiration
              </label>
              <i class="fa-solid fa-calendar-days absolute left-3 top-9 text-gray-400"></i>
              <input type="text" name="expiry"
                class="w-full border rounded-lg pl-10 pr-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="MM/YY">
            </div>

            <div class="relative">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                CVV
              </label>
              <i class="fa-solid fa-lock absolute left-3 top-9 text-gray-400"></i>
              <input type="password" name="cvv"
                class="w-full border rounded-lg pl-10 pr-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="123" maxlength="3">
            </div>
          </div>

        </div>


        <input type="hidden" name="code_employeur" value="<?= $session->getUserCode() ?>">
        <div class="flex flex-col sm:flex-row justify-between gap-3">
          <div class="flex flex-between p-3">
            <button type="reset" class="w-full sm:w-auto px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 flex items-center justify-center gap-2 mx-2">
              <i class="fa-solid fa-rotate-left"></i> Annuler
            </button>
            <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center justify-center gap-2 mx-2">
              <i class="fa-solid fa-save"></i> Enregistrer
            </button>
          </div>
          <div id="info" class="d-none" role="alert" aria-live="polite"></div>
        </div>

      </form>
    </div>
    <?php endif; ?>
    <!-- Table -->
    <div class="bg-white p-6 rounded shadow my-3">
      <h3 class="text-xl font-semibold mb-4">Liste des Employés</h3>
      <table class="w-full table-auto">
        <thead>
          <tr class="bg-gray-200 text-left">
            <th class="p-2">Nom</th>
            <th class="p-2">Prénom</th>
            <th class="p-2">Sexe</th>
            <th class="p-2">Salaire</th>
            <th class="p-2">Cotisation</th>
            <th class="p-2">Statut Cot.</th>
            <th class="p-2">Statut</th>
            <?php if ($session->getRole() === 'Employeur') : ?>
            <th class="p-2">Actions</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody id="render-data">
          <!-- Les données des Employeurs seront insérées ici dynamiquement -->
        </tbody>
      </table>
      <div id="pagination" class="pagination flex justify-center"></div>
    </div>

    <form class="absolute top-0 left-0 right-0 bottom-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4 d-none" id="pop-up-paiment">
      <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-2xl p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
          <i class="fa-solid fa-credit-card text-blue-600"></i>
          Paiement bancaire
        </h2>

      <!-- Nom du titulaire -->
      <div class="mb-4 relative">
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Nom du titulaire
        </label>
        <i class="fa-solid fa-user absolute left-3 top-9 text-gray-400"></i>
        <input type="text" name="nom_tit_carte"
          class="w-full border rounded-lg pl-10 pr-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
          placeholder="Ex: Elva D'or Kupulu" >
      </div>

      <!-- Numéro de carte -->
      <div class="mb-4 relative">
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Numéro de carte
        </label>
        <i class="fa-solid fa-credit-card absolute left-3 top-9 text-gray-400"></i>
        <input type="text" name="numero_paiement"
          class="w-full border rounded-lg pl-10 pr-3 py-2 tracking-widest focus:ring-2 focus:ring-blue-500 outline-none"
          placeholder="1234 5678 9012 3456" maxlength="19" pattern="^[0-9]{4} [0-9]{4} [0-9]{4} [0-9]{4}$">
      </div>

      <!-- Expiration + CVV -->
      <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="relative">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Expiration
          </label>
          <i class="fa-solid fa-calendar-days absolute left-3 top-9 text-gray-400"></i>
          <input type="text" name="expiry"
            class="w-full border rounded-lg pl-10 pr-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="MM/YY">
        </div>

        <div class="relative">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            CVV
          </label>
          <i class="fa-solid fa-lock absolute left-3 top-9 text-gray-400"></i>
          <input type="password" name="cvv"
            class="w-full border rounded-lg pl-10 pr-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="123" maxlength="3">
        </div>
      </div>

      <div id="info1" class="d-none" role="alert" aria-live="polite"></div>

      <input type="hidden" name="code_employeur" value="<?= $session->getUserCode() ?>">
      <input type="hidden" name="code_employe" id="code-employe">
      <div class="flex flex-col sm:flex-row justify-around gap-3">
          <button id="close-pop-up" class="w-full sm:w-auto px-4 py-2 bg-red-400 text-white rounded-lg hover:bg-red-500 flex items-center justify-center gap-2 mx-2">
            <i class="fa-solid fa-rotate-left"></i> Annuler
          </button>
          <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center justify-center gap-2 mx-2">
            <i class="fa-solid fa-credit-card"></i> Payer
          </button>
      </div>

    </div>

    <?php if ($session->getRole() === 'Admin') : ?>
      <input type="hidden" name="code_employeur" id="code_employeur" value="<?= isset($_GET['e']) ? filter_input(INPUT_GET, 'e', FILTER_SANITIZE_SPECIAL_CHARS) : '' ?>">
    <?php endif; ?>
    
<script>

  $(document).ready(function(){

      $('#salaire').on('input', function(){
          const salaire = parseFloat($(this).val());
          if (!isNaN(salaire)) {
              const cotisation = (salaire * 0.15).toFixed(2);
              $('#cotisation').val(cotisation);
          } else {
              $('#cotisation').val('');
          }
      });
      
      $('input[name="valid_payment"]').on('change', function(){
          if ($(this).val() === 'true') {
              $('#payment-info').removeClass('d-none').hide().fadeIn("slow");
          } else {
              $('#payment-info').addClass('d-none').hide().fadeOut("slow");
          }
      });

      $(document).on('submit', '#add-employe', async function(e){
          e.preventDefault();
          const formData = new FormData(this);
          
          const res = await axios.post('/add_employe', formData)
          console.log(res.data.message);
          $('#info').removeClass('d-none success error');
          if (res.data.status === "success") {
              $('#info').addClass('success').hide().fadeIn("slow");
              // $('#info').removeClass('error').addClass('success').hide().fadeIn("slow");
              setTimeout(() => {
                  location.reload();
              }, 2000);

          }else{
              $('#info').addClass('error').hide().fadeIn("slow");
              // $('#info').removeClass('success').addClass('error').hide().fadeIn("slow");
          }
          $('#info').html(res.data.message);
          
      });

      $(document).on('submit', '#pop-up-paiment', async function(e){
          e.preventDefault();
          const formData = new FormData(this);
          const res = await axios.post('/add_paiement', formData)
          console.log(res.data.message);
          $('#info1').removeClass('d-none success error');
          if (res.data.status === "success") {
              $('#info1').addClass('success').hide().fadeIn("slow");
              // $('#info1').removeClass('error').addClass('success').hide().fadeIn("slow");
              setTimeout(() => {
                  location.reload();
              }, 2000);

          }else{
              $('#info1').addClass('error').hide().fadeIn("slow");
              // $('#info1').removeClass('success').addClass('error').hide().fadeIn("slow");
          }
          $('#info1').html(res.data.message);
          
      });

      var currentPageP = 1,
          limitP = 10;
      let code_employeur = $('#code_employeur').val() || null,
          role_session = $('#role-session').val() || null;

      const getEmployeur = async (pageP) => {

          const res = await axios.get('/get_employe', {params : {limit : limitP, page : pageP, code_employeur: code_employeur}})

          console.log(res.data.message)
          if (res.data.status === 'success') {
              
              if (Array.isArray(res.data.data)){
                  var donnees = Object.values(res.data.data);
                  let all = Object.values(res.data.total);
                  let totalPages = Math.ceil(all.length / limitP);
                  // let session = res.data.session;
                  
                  let actif = all.filter(employeur => employeur.statut === 'actif'),
                      homme = all.filter(employeur => employeur.sexe === 'Homme'),
                      femme = all.filter(employeur => employeur.sexe === 'Femme'),
                      inactif = all.filter(employeur => employeur.statut === 'inactif');
                  
                  $('#tot-employe').text(all.length);
                  $('#tot-actif').text(actif.length);
                  $('#tot-femme').text(femme.length);
                  $('#tot-homme').text(homme.length);
                  $('#tot-inactif').text(inactif.length);
                  
                  tableData(donnees);

                  renderPagination(totalPages, pageP);

              }else{
                  alert("Erreur : Données reçues non valides." + res.data.message + ' ' + res.data.page);
              }
          }
          else{
              $('#render-data').html('<div class="alert alert-warning opacity-75 rounded-lg">'+res.data.message+'</div>');
          }
      }


      function tableData(donnees) {
          // let i = 0;
          let contenuP = "";
          donnees.map(donne => {
              contenuP += `
                  <tr class="hover:bg-black/20">
                      <td class="p-2">${donne.nom}</td>
                      <td class="p-2">${donne.prenom}</td>
                      <td class="p-2">${donne.sexe}</td>
                      <td class="p-2">${donne.salaire}</td>
                      <td class="p-2">${donne.montant}</td>
                      <td class="p-2">
                          <span class="px-2 py-1 rounded-full text-sm font-medium ${donne.statut_cotisation === 'Payé' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'}">${donne.statut_cotisation}</span>
                      </td>
                      <td class="p-2">
                          <span class="px-2 py-1 rounded-full text-sm font-medium ${donne.statut === 'actif' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'}">${donne.statut}</span>
                      </td>
                      ${role_session === 'Employeur' ? `
                      <td class="p-2">
                          <button class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600"><i class="fa-solid fa-pen-to-square"></i> Modifier</button>
                          <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"><i class="fa-solid fa-trash"></i> Supprimer</button>

                          ${donne.statut_cotisation !== 'Payé' ? `<button data-code="${donne.code}" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600" id="open-payment"><i class="fa-solid fa-money-bill"></i> Payer</button>` : ""}
                      </td>
                          
                      </td>
                      ` : ""}
                  </tr>
              `;
          })
          $('#render-data').html(contenuP)
          $('#render-data').hide().fadeIn("slow");
      }

      $(document).on('click', '#open-payment', function(){
          $('#pop-up-paiment').removeClass('d-none').hide().fadeIn("slow");
          const codeEmploye = $(this).data('code');
          $('#code-employe').val(codeEmploye);
      });

      $(document).on('click', '#close-pop-up', function(){
          $('#pop-up-paiment').addClass('d-none').hide().fadeOut("slow");
          $('#code-employe').val('');
      });

      // Au clique de bouton de la pagination
      $(document).on('click', '.page-link', function(){
          const page = $(this).data('page');
          getEmployeur(page)
      });

      // Au clique de bouton de la pagination
      $(document).on('click', '.select-bi', function(){
          const page = $(this).data('page');
          getEmployeur(page);
      });

      getEmployeur(currentPageP);
  });
</script>
<?php
$script = "/assets/js/pagination.js";
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
