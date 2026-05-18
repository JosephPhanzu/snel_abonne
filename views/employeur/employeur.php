<?php

$title = "Employeur | Welcome";


ob_start();
?>
    <h2 class="text-xl md:text-2xl font-semibold mb-6 flex items-center gap-2">
      <i class="fa-solid fa-user-plus text-blue-600"></i>
      Formulaire d'enregistrement Employeur
    </h2>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-6">
      <div class="bg-white p-4 rounded shadow flex items-center justify-between">
        <div>
          <h3 class="text-gray-500 text-sm">Employeurs</h3>
          <p class="text-xl md:text-2xl font-bold" id="tot-employeur"></p>
        </div>
        <i class="fa-solid fa-building text-blue-600 text-xl md:text-2xl"></i>
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

    <!-- Form Card -->
    <div class="w-full min-w-3xl bg-white p-4 md:p-6 rounded-2xl shadow-lg">
      <form id="add-employeur" class="space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Nom</label>
            <div class="relative">
              <i class="fa-solid fa-user absolute left-3 top-4 text-gray-400"></i>
              <input type="text" class="pl-10 mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Nom" name="nom">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Adresse</label>
            <div class="relative">
              <i class="fa-solid fa-home absolute left-3 top-4 text-gray-400"></i>
              <input type="text" class="pl-10 mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Adresse" name="adresse">
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <div class="relative">
              <i class="fa-solid fa-envelope absolute left-3 top-4 text-gray-400"></i>
              <input id="salary" type="email" class="pl-10 mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Email" name="email">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Téléphone</label>
            <div class="relative">
              <i class="fa-solid fa-phone absolute left-3 top-4 text-gray-400"></i>
              <input id="cotisation" type="tel" class="pl-10 mt-1 w-full p-2 border rounded-lg" placeholder="Téléphone" name="tel">
            </div>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
          <div class="relative">
            <i class="fa-solid fa-lock absolute left-3 top-4 text-gray-400"></i>
            <input type="password" class="pl-10 mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Mot de passe" name="mdp1">
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
          <div class="relative">
            <i class="fa-solid fa-lock absolute left-3 top-4 text-gray-400"></i>
            <input type="password" class="pl-10 mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Confirmer le mot de passe" name="mdp2">
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Statut</label>
          <div class="relative">
            <i class="fa-solid fa-toggle-on absolute left-3 top-3 text-gray-400"></i>
            <select class="pl-10 mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" name="statut">
              <option>Actif</option>
              <option>Inactif</option>
            </select>
          </div>
        </div>

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
    <!-- Table -->
    <div class="bg-white p-6 rounded shadow my-3">
      <h3 class="text-xl font-semibold mb-4">Liste des Employés</h3>
      <table class="w-full table-auto">
        <thead>
          <tr class="bg-gray-200 text-left">
            <th class="p-2">Nom</th>
            <th class="p-2">Adresse</th>
            <th class="p-2">Email</th>
            <th class="p-2">Téléphone</th>
            <th class="p-2">Statut</th>
            <th class="p-2">Actions</th>
          </tr>
        </thead>
        <tbody id="render-data">
          <!-- Les données des Employeurs seront insérées ici dynamiquement -->
        </tbody>
      </table>
      <div id="pagination" class="pagination flex justify-center"></div>
    </div>

    
<script>

  $(document).on('submit', '#add-employeur', async function(e){
      e.preventDefault();
      const formData = new FormData(this);
      
      const res = await axios.post('/add_employeur', formData)
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
      
  })

  var currentPageP = 1,
      limitP = 10;


  const getEmployeur = async (pageP) => {

      const res = await axios.get('/get_employeurs', {params : {limit : limitP, page : pageP}})

      console.log(res.data.message)
      if (res.data.status === 'success') {
          
          if (Array.isArray(res.data.data)){
              var donnees = Object.values(res.data.data);
              let all = Object.values(res.data.total);
              let totalPages = Math.ceil(all.length / limitP);
              // let session = res.data.session;
              
              let actif = all.filter(employeur => employeur.statut === 'actif'),
                  inactif = all.filter(employeur => employeur.statut === 'inactif');
              
              $('#tot-employeur').text(all.length);
              $('#tot-actif').text(actif.length);
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
                  <td class="p-2">${donne.adresse}</td>
                  <td class="p-2">${donne.email}</td>
                  <td class="p-2">${donne.telephone}</td>
                  <td class="p-2">
                      <span class="px-2 py-1 rounded-full text-sm font-medium ${donne.statut === 'actif' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'}">${donne.statut}</span>
                  </td>
                  <td class="p-2">
                      <button class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600"><i class="fa-solid fa-pen-to-square"></i> Modifier</button>
                      <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"><i class="fa-solid fa-trash"></i> Supprimer</button>
                      <button onclick="window.location.href='/employe?e=${donne.code}'" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600"><i class="fa-solid fa-info-circle"></i> Détails</button>
                  </td>
              </tr>
          `;
      })
      $('#render-data').html(contenuP)
      $('#render-data').hide().fadeIn("slow");
  }

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

</script>
<?php
$script = "/assets/js/pagination.js";
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
