<?php

// use App\Facture;
// use App\Facture_conv;
$title = "Paiement | Welcome";

$role = $session->getRole();

ob_start();
?>
<div x-transition>
    <h2 class="text-2xl font-bold mb-4">💳 Paiements</h2>

    <div class="flex flex-wrap gap-3 mb-4">
        <?php if($role == 'agent'): ?>
        <div>
            <label class="block text-sm font-medium">Abonné</label>
            <select x-model="filtreAbonne" class="mt-1 border rounded px-3 py-2">
            <option value="">Tous les abonnés</option>
            <template x-for="a in abonnes" :key="a.id">
                <option :value="a.id" x-text="a.nom"></option>
            </template>
            </select>
        </div>
        <?php endif; ?>
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

    <div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-2">Abonné</th>
                    <th class="py-2 px-2">Montant</th>
                    <th class="py-2 px-2">Mois</th>
                    <th class="py-2 px-2">Statut</th>
                    <th class="py-2 px-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="p in paginatedPaiements" :key="p.id">
                    <tr class="border-t">
                        <td class="py-2 px-2" x-text="p.nom"></td>
                        <td class="py-2 px-2" x-text="p.montant+' CDF'"></td>
                        <td class="py-2 px-2" x-text="p.mois"></td>
                        <td class="py-2 px-2">
                            <span :class="p.paye ? 'badge-paye' : 'badge-impaye'" class="px-2 py-0.5 rounded-full text-xs" x-text="p.paye ? 'Payé' : 'Impayé'"></span>
                        </td>
                        <td class="py-2 px-2">
                            <button @click="togglePaiement(p)" :class="p.paye ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600'" class="text-white px-3 py-1 rounded" x-text="p.paye ? 'Marquer comme impayé' : 'Marquer comme payé'">
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <div class="flex items-center justify-between mt-4 px-6">
            <button :disabled="pagePaiements === 1" @click="pagePaiements--" class="bg-blue-500 rounded-lg p-2 text-white m-2">Préc</button>
            <span>Page <span x-text="pagePaiements"></span> / <span x-text="totalPages(paiements)"></span></span>
            <button :disabled="pagePaiements >= totalPages(paiements)" @click="pagePaiements++" class="bg-blue-500 rounded-lg p-2 text-white m-2">Suiv</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
