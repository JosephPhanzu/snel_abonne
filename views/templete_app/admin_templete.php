<?php

  use App\Permission;
  $permission = new Permission();

  if (!$session->isConnected()) {
    $session->logout();
    header('Location: /login');
  }

  if (!($permission->getUser_per($session->getUserCode()))) {
    $session->logout();
    header('Location: /login');
  }

  $nom = $session->getNom();
  $prenom = $session->getPrenom();
  $role = $session->getRole();

  $dashboard_url = $session->isAdminConnected() ? '/dashboard_admin' : '/dashboard_proprietaire';

  $currentPage = trim($_SERVER['REQUEST_URI'], '/');

    $currentPage = parse_url($currentPage, PHP_URL_PATH);

    function currentPageActive($uri, $class, $currentPage) {
        if (is_array($uri)) :
            echo in_array($currentPage, $uri) ? $class : "text-gray-700 hover:bg-gray-50";
        else :
            echo $currentPage === $uri ? $class : "text-gray-700 hover:bg-gray-50";
        endif;
    }

    $active = 'text-blue-600  bg-blue-50';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'WelcomPage' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link rel="stylesheet" href="/assets/css/style.css">
  	<link rel="shortcut icon" type="image/png" href="/assets/img/logo.png"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.9.0/dist/axios.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-white border-r h-screen flex flex-col fixed z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
            <div class="p-6 border-b">
                <div class="flex items-center">
                    <i data-lucide="building-2" class="h-8 w-8 text-blue-600 mr-3"></i>
                    <h1 class="text-xl font-bold text-gray-900">WillPharma</h1>
                </div>
            </div>
            
            <nav class="flex-1 p-4 space-y-2">
                <a href="<?= $dashboard_url ?>" class="flex items-center px-3 py-2 text-sm font-medium <?php currentPageActive($dashboard_url, $active, $currentPage) ?> rounded-md">
                    <i data-lucide="layout-dashboard" class="mr-3 h-4 w-4"></i>
                    Tableau de bord
                </a>
                <?php if ($session->isAdminConnected()): ?>
                <a href="/gestion_partenaire" class="flex items-center px-3 py-2 text-sm font-medium <?php currentPageActive(["gestion_partenaire", "info_partenaire"], $active, $currentPage) ?> rounded-md">
                    <i data-lucide="users" class="mr-3 h-4 w-4"></i>
                    Gestion des partenaires
                </a>
                <?php endif; ?>
                <a href="/settings" class="flex items-center px-3 py-2 text-sm font-medium <?php currentPageActive("settings", $active, $currentPage) ?> rounded-md">
                    <i data-lucide="settings" class="mr-3 h-4 w-4"></i>
                    Paramètres
                </a>
            </nav>
            
            <div class="p-4 border-t">
                <button id="deconnexion" class="flex items-center px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-md w-full">
                    <i data-lucide="log-out" class="mr-3 h-4 w-4"></i>
                    Déconnexion
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div id="main" class="flex-1 p-8 bg-gradient-to-br from-slate-900 via-blue-900 to-black md:ml-64">
            <div class="flex justify-between items-center mb-6 md:hidden" id="block-menu-toggle">
              <button id="menu-toggle" class="p-2 text-white">
                  <i data-lucide="menu" class="h-6 w-6"></i>
              </button>
            </div>
            <div class="flex justify-between items-center mb-6 hidden md:grid sticky top-0 z-40" id="block-menu-toggle-md">
              <button id="menu-toggle-md" class="p-2 text-white">
                  <i data-lucide="menu" class="h-6 w-6"></i>
              </button>
            </div>
            <div class="max-w-7xl mx-auto">
                
                <?= $content ?>

            </div>
        </div>
    </div>
    <style>
        .menulong{
            margin-left: 250px;
            transition: .3s ease-in-out;
        }
        .main-full {
            display: block !important;
            transition: .3s ease-in-out;
        }
  </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        lucide.createIcons();

        $(document).on('click', '#menu-toggle', function () {
          	$('#sidebar').toggleClass('-translate-x-full');
          	$('#block-menu-toggle').toggleClass('menulong');
        });

        $(document).on('click', '#menu-toggle-md', function () {
          	$('#sidebar').toggleClass('hidden fixed');
            $('#main').toggleClass('md:ml-64');
        });

        $(document).on('scroll', function() {
          	var scroll = $(window).scrollTop();
          	if (scroll > 0) {
              	$('#block-menu-toggle-md').addClass('bg-blue/40 backdrop-blur-sm');
            } else {
              	$('#block-menu-toggle-md').removeClass('bg-blue/40 backdrop-blur-sm');
            }
        });
        
        $(document).on('click', '#deconnexion', function(e){
            window.location.replace('/deconnexion');
        })
    
    </script>
</body>
</html>