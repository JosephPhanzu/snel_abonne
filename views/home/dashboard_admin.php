<?php

use App\Proprietaire;
use App\Pharmacie;
use App\Employe;

$nbr_partenaire = COUNT((new Proprietaire())->getAll());
$nbr_pharmaie = COUNT((new Pharmacie())->getAll());
$nbr_employe = COUNT((new Employe())->getAll());

$title = "Dashboard - Admin | Welcome";

ob_start();
?>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-200 flex items-center">
            <i data-lucide="layout-dashboard" class="h-8 w-8 text-blue-600 mr-3"></i>
            Tableau de bord
        </h1>
        <p class="text-blue-100 mt-2">Vue d'ensemble de nos Partenaires</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-black/40 border-blue-500/30 rounded-lg rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Nombre de partenaire</p>
                    <p class="text-2xl font-bold text-blue-200"><?= $nbr_partenaire ?? 00 ?></p>
                </div>
                <i data-lucide="euro" class="h-8 w-8 text-green-600"></i>
            </div>
        </div>

        <div class="bg-black/40 border-blue-500/30 rounded-lg rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Nombre de pharmacie</p>
                    <p class="text-2xl font-bold text-blue-200"><?= $nbr_pharmaie ?? 00 ?></p>
                </div>
                <i data-lucide="shopping-cart" class="h-8 w-8 text-blue-600"></i>
            </div>
        </div>

        <div class="bg-black/40 border-blue-500/30 rounded-lg rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Nombre d'employés gérés</p>
                    <p class="text-2xl font-bold text-blue-200"><?= $nbr_employe ?? 00 ?></p>
                </div>
                <i data-lucide="users" class="h-8 w-8 text-purple-600"></i>
            </div>
        </div>

        <!-- <div class="bg-black/40 border-blue-500/30 rounded-lg rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Alertes</p>
                    <p class="text-2xl font-bold text-blue-200">0</p>
                </div>
                <i data-lucide="alert-triangle" class="h-8 w-8 text-red-600"></i>
            </div>
        </div> -->
    </div>

    <!-- Recent Activity -->
    <div class="bg-black/40 border-blue-500/30 rounded-lg rounded-lg shadow-sm">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-blue-200">Activité récente</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                    <span class="text-sm text-blue-100">Vente de Doliprane 1000mg - 15,40 €</span>
                    <span class="ml-auto text-xs text-gray-400">Il y a 5 min</span>
                </div>
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                    <span class="text-sm text-blue-100">Nouvelle commande de Marie Lambert</span>
                    <span class="ml-auto text-xs text-gray-400">Il y a 12 min</span>
                </div>
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-orange-500 rounded-full mr-3"></div>
                    <span class="text-sm text-blue-100">Alerte stock bas: Ventoline</span>
                    <span class="ml-auto text-xs text-gray-400">Il y a 1h</span>
                </div>
            </div>
        </div>
    </div>
<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/admin_templete.php";
