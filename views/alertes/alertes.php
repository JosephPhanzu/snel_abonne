<?php

$title = "Alertes | Welcome";

ob_start();
?>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <i data-lucide="alert-triangle" class="h-8 w-8 text-red-600 mr-3"></i>
            Centre d'alertes
        </h1>
        <p class="text-gray-600 mt-2">Surveillez les alertes importantes de vos pharmacies</p>
    </div>

    <!-- Alert Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg border shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total alertes</p>
                    <p class="text-2xl font-bold text-gray-900">23</p>
                </div>
                <i data-lucide="bell" class="h-8 w-8 text-blue-600"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg border shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Critiques</p>
                    <p class="text-2xl font-bold text-gray-900">5</p>
                </div>
                <i data-lucide="alert-circle" class="h-8 w-8 text-red-600"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg border shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Péremption</p>
                    <p class="text-2xl font-bold text-gray-900">12</p>
                </div>
                <i data-lucide="calendar" class="h-8 w-8 text-orange-600"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg border shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Stock</p>
                    <p class="text-2xl font-bold text-gray-900">6</p>
                </div>
                <i data-lucide="package" class="h-8 w-8 text-yellow-600"></i>
            </div>
        </div>
    </div>

    <!-- Critical Alerts -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-red-900 mb-4">🚨 Alertes critiques</h2>
        <div class="space-y-4">
            <div class="bg-white border-l-4 border-red-500 rounded-lg shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="alert-circle" class="h-6 w-6 text-red-600 mt-1"></i>
                        <div>
                            <h3 class="font-semibold text-red-900">Produit expiré</h3>
                            <p class="text-red-700 mt-1">Sirop contre la toux - Expiré depuis 2 jours</p>
                            <p class="text-sm text-red-600 mt-2">Stock: 15 unités à retirer</p>
                        </div>
                    </div>
                    <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                        Retirer du stock
                    </button>
                </div>
            </div>

            <div class="bg-white border-l-4 border-red-500 rounded-lg shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="x-circle" class="h-6 w-6 text-red-600 mt-1"></i>
                        <div>
                            <h3 class="font-semibold text-red-900">Rupture de stock</h3>
                            <p class="text-red-700 mt-1">Aspegic 1000mg - Stock épuisé</p>
                            <p class="text-sm text-red-600 mt-2">Dernière vente: il y a 3 heures</p>
                        </div>
                    </div>
                    <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                        Commander d'urgence
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Important Alerts -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-orange-900 mb-4">⚠️ Alertes importantes</h2>
        <div class="space-y-4">
            <div class="bg-white border-l-4 border-orange-500 rounded-lg shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="calendar" class="h-6 w-6 text-orange-600 mt-1"></i>
                        <div>
                            <h3 class="font-semibold text-orange-900">Péremption proche</h3>
                            <p class="text-orange-700 mt-1">Ventoline 100µg - Expire dans 15 jours</p>
                            <p class="text-sm text-orange-600 mt-2">Stock: 20 unités - Date: 20/07/2024</p>
                        </div>
                    </div>
                    <button class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md text-sm">
                        Planifier promo
                    </button>
                </div>
            </div>

            <div class="bg-white border-l-4 border-orange-500 rounded-lg shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="trending-down" class="h-6 w-6 text-orange-600 mt-1"></i>
                        <div>
                            <h3 class="font-semibold text-orange-900">Stock faible</h3>
                            <p class="text-orange-700 mt-1">Amoxicilline 500mg - Stock: 5 unités</p>
                            <p class="text-sm text-orange-600 mt-2">Seuil minimum: 10 unités</p>
                        </div>
                    </div>
                    <button class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md text-sm">
                        Réapprovisionner
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Other Alerts -->
    <div>
        <h2 class="text-xl font-semibold text-gray-900 mb-4">📋 Autres alertes</h2>
        <div class="bg-white rounded-lg border shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Stock</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Vitamines C</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Stock: 8 unités (seuil: 15)</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">05/06/2024</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-blue-600 hover:text-blue-900">Commander</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Info</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Masques chirurgicaux</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Nouveau lot reçu</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">04/06/2024</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-green-600 hover:text-green-900">Marquer comme lu</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
