<?php
$title = "Abonnés | Gestion des abonnés Snel";
ob_start();
?>
<!-- Page Consommation -->
<diV x-transition>

    <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">⚡ Consommation</h2>
    <button @click="openModal('conso')" class="bg-yellow-400 px-4 py-2 rounded-xl font-semibold"><i class="fas fa-plus"></i> Ajouter</button>
    </div>

    <!-- Note sur le prix par kWh et la TVA de 3.5% ajoutée avec un design profession au moins 70 mots -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
        <p class="text-sm text-blue-700">💡 Note : Le prix par kWh est de <span class="font-bold">665 CDF</span>. Une TVA de <span class="font-bold">3.5%</span> est appliquée sur le montant total de la facture. Assurez-vous de prendre en compte ces éléments lors de la facturation pour garantir une tarification précise et conforme aux réglementations en vigueur.</p>
    </div>


    <div class="flex flex-wrap gap-3 mb-4">
        <div>
            <label class="block text-sm font-medium">Abonné</label>
            <select x-model="filtreAbonne" class="mt-1 border rounded px-3 py-2">
            <option value="">Tous les abonnés</option>
            <template x-for="a in abonnes" :key="a.id">
                <option :value="a.id" x-text="a.nom"></option>
            </template>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium">Mois</label>
            <select x-model="filtreMois" class="mt-1 border rounded px-3 py-2">
            <option value="">Tous les mois</option>
            <template x-for="mois in moisDisponibles()" :key="mois">
                <option :value="mois" x-text="mois"></option>
            </template>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-2xl">
    <table class="w-full text-sm">
        <thead class="bg-gray-100 m-0">
            <tr>
                <th class="py-2 px-2">Abonné</th>
                <th class="py-2 px-2">Ancien index</th>
                <th class="py-2 px-2">Nouvel index</th>
                <th class="py-2 px-2">kWh</th>
                <th class="py-2 px-2">Mois & Année</th>
            </tr>
        </thead>
        <tbody class="bg-white rounded-2xl shadow-sm overflow-x-auto p-2">
        <template x-for="c in paginatedConsommations()" :key="c.id">
            <tr class="border-t">
                <td class="py-2 px-2" x-text="c.nom"></td>
                <td class="py-2 px-2" x-text="c.index_ancien"></td>
                <td class="py-2 px-2" x-text="c.index_nouveau"></td>
                <td class="py-2 px-2" class="font-bold" x-text="c.consommation"></td>
                <td class="py-2 px-2" x-text="c.mois + ' ' + c.annee"></td>
            </tr>
        </template>
        </tbody>
    </table>
    <div class="flex items-center justify-between mt-4 px-6">
        <button :disabled="pageConsommations === 1" @click="pageConsommations--" class="bg-blue-500 rounded-lg p-2 text-white m-2">Préc</button>
        <span>Page <span x-text="pageConsommations"></span> / <span x-text="totalPages(filterConsommations())"></span></span>
        <button :disabled="pageConsommations >= totalPages(filterConsommations())" @click="pageConsommations++" class="bg-blue-500 rounded-lg p-2 text-white m-2">Suiv</button>
    </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
?>