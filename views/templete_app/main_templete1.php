<?php

  use App\Permission;

  $permission = new Permission();
  if (!$session->isConnected() ) {
    $session->logout();
    header('Location: /login');
  }

  $nom = $session->getNom();
  // $prenom = $session->getPrenom();

  $role = $session->getRole();


  $currentPage = trim($_SERVER['REQUEST_URI'], '/');

    $currentPage = parse_url($currentPage, PHP_URL_PATH);

    function currentPageActive($uri, $class, $currentPage) {
        if (is_array($uri)) :
            echo in_array($currentPage, $uri) ? $class : "";
        else :
            echo $currentPage === $uri ? $class : "";
        endif;
    }

    $active = 'bg-blue-700';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'Login CNSS' ?></title>
  <link rel="shortcut icon" type="image/jpg" href="/assets/img/cnss.JPG"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="<?= $script ?? '' ?>"></script>
  <style>
    .d-none {
      display: none !important;
    }

    #info, #info1 {
      display: none;
      border-width: 1px;
      border-style: solid;
      border-radius: 1rem;
      padding: 1rem 1.25rem;
      font-size: 0.95rem;
      font-weight: 500;
      line-height: 1.5;
      margin-bottom: 1rem;
      box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
    }

    #info.success, #info1.success {
      background: #ecfdf5;
      border-color: #86efac;
      color: #166534;
    }

    #info.success::before, #info1.success::before {
      content: "✔";
      margin-right: 0.5rem;
    }

    #info.error, #info1.error {
      background: #fee2e2;
      border-color: #fca5a5;
      color: #991b1b;
    }

    #info.error::before, #info1.error::before {
      content: "✖";
      margin-right: 0.5rem;
    }
  </style>

</head>
<body class="bg-gray-100">

<!-- Mobile Topbar (VISIBLE UNIQUEMENT SUR SMARTPHONE) -->
<div class="md:hidden flex items-center justify-between bg-blue-900 text-white px-4 py-3 shadow">
  <div class="flex items-center gap-2">
    <i class="fa-solid fa-shield-halved"></i>
    <span class="font-bold">CNSS</span>
  </div>

  <!-- Bouton toggle menu -->
  <button id="menuBtn" class="focus:outline-none">
    <i class="fa-solid fa-bars text-xl"></i>
  </button>
</div>

<div class="flex h-screen overflow-hidden">

  <!-- Sidebar -->
  <aside id="sidebar" class="fixed md:static z-50 inset-y-0 left-0 w-64 bg-blue-900 text-white p-5 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
    <h1 class="text-2xl font-bold mb-8 hidden md:flex items-center gap-2">
      <i class="fa-solid fa-shield-halved"></i> CNSS
    </h1>

    <nav class="space-y-4">
      <a href="/dashboard" class="flex items-center gap-3 <?php currentPageActive("dashboard", $active, $currentPage) ?> hover:bg-blue-700 p-2 rounded">
        <i class="fa-solid fa-chart-line"></i> Dashboard
      </a>
      <?php if ($session->hasRole('Admin')) : ?>
      <a href="/employeur" class="flex items-center gap-3  <?php currentPageActive(["employeur", "employeur_details"], $active, $currentPage) ?> hover:bg-blue-700 p-2 rounded">
        <i class="fa-solid fa-building"></i> Employeurs
      </a>
      <?php endif; 
      
      if ($session->hasRole('Employeur')) : ?>
      <a href="/employe" class="flex items-center gap-3  <?php currentPageActive("employe", $active, $currentPage) ?> hover:bg-blue-700 p-2 rounded">
        <i class="fa-solid fa-users"></i> Employés
      </a>
      <?php endif; ?>
      <a href="/cotisation" class="flex items-center gap-3  <?php currentPageActive("cotisation", $active, $currentPage) ?> hover:bg-blue-700 p-2 rounded">
        <i class="fa-solid fa-money-bill-wave"></i> Cotisations
      </a>
      <a href="/bordereau" class="flex items-center gap-3  <?php currentPageActive("bordereau", $active, $currentPage) ?> hover:bg-blue-700 p-2 rounded">
        <i class="fa-solid fa-file-invoice"></i> Bordereaux
      </a>
    </nav>
    <div class="mt-8 pt-6 border-t border-blue-700">
        <button
          class="w-full flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl transition duration-200" id="deconnexion"
        >
          <i class="fa-solid fa-right-from-bracket"></i>
          Déconnexion
        </button>
      </div>
  </aside>

  <!-- Overlay (mobile) -->
  <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden md:hidden"></div>

  <!-- Main Content -->
  <main class="flex-1 p-4 md:p-6 overflow-y-auto w-full">
        <script src="https://cdn.jsdelivr.net/npm/axios@1.9.0/dist/axios.min.js"></script>
        
        <?= $content ?? '' ?>
  </main>
</div>

<script>

        $(document).on('click', '#deconnexion', function(e){
            window.location.replace('/deconnexion');
        })


  const salaryInput = document.getElementById('salary');
  const cotisationInput = document.getElementById('cotisation');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const menuBtn = document.getElementById('menuBtn');

  salaryInput.addEventListener('input', () => {
    const salary = parseFloat(salaryInput.value) || 0;
    cotisationInput.value = (salary * 0.15).toFixed(2);
  });

  menuBtn.addEventListener('click', () => {
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
  });

  overlay.addEventListener('click', () => {
    sidebar.classList.add('-translate-x-full');
    overlay.classList.add('hidden');
  });
</script>

</body>
</html>