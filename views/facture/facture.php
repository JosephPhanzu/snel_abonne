<?php

// use App\Facture;
// use App\Facture_conv;
$title = "Facture | Welcome";

$role = $session->getRole();

ob_start();
?>
<div x-transition>
    <h2 class="text-2xl font-bold mb-4">🧾 Factures <?= $role ?></h2>

    <!-- Note expliquant le coût total de la facture par rapport au prix par kWh et la TVA de 3.5% ajoutée avec un design profession au moins 150 mots -->

    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
        <p class="text-sm text-blue-700">💡 Note : Le montant total de la facture est calculé en multipliant la consommation en kWh par le prix unitaire de 665 CDF par kWh. Par exemple, si un abonné consomme 150 kWh, le coût de base serait de 150 kWh x 665 CDF/kWh = 15 000 CDF. Ensuite, une TVA de 3.5% est appliquée sur ce montant pour obtenir le total final de la facture. Dans cet exemple, la TVA serait de 15 000 CDF x 3.5% = 525 CDF, ce qui porterait le montant total à payer à 15 525 CDF. Il est important de comprendre cette structure tarifaire pour mieux gérer les factures et anticiper les coûts liés à la consommation d'électricité.</p>
    </div>

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
                    <?php if($role == 'abonne'): ?>
                    <th class="py-2 px-2">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <template x-for="f in paginatedFactures" :key="f.id">
                    <tr class="border-t">
                        <td class="py-2 px-2" x-text="f.nom"></td>
                        <td class="py-2 px-2" x-text="f.montant+' CDF'"></td>
                        <td class="py-2 px-2" x-text="f.mois"></td>
                        <td class="py-2 px-2">
                            <span :class="f.statut === 'Payée' ? 'badge-paye' : 'badge-impaye'" class="px-2 py-0.5 rounded-full text-xs" x-text="f.statut"></span>
                        </td>
                        <?php if($role == 'abonne'): ?>
                        <td class="py-2 px-2">
                            <button @click="f.statut === 'Non payée' ? ajouterPaiement(f) : null" :class="f.statut === 'Non payée' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600'" class="text-white px-3 py-1 rounded" x-text="f.statut === 'Payée' ? 'Déjà Payer' : 'Payer'"></button>
                            </button>
                        </td>
                        <?php endif; ?>
                    </tr>
                </template>
            </tbody>
        </table>
        <div class="flex items-center justify-between mt-4 px-6">
            <button :disabled="pageFactures === 1" @click="pageFactures--" class="bg-blue-500 rounded-lg p-2 text-white m-2">Préc</button>
            <span>Page <span x-text="pageFactures"></span> / <span x-text="totalPages(factures)"></span></span>
            <button :disabled="pageFactures >= totalPages(factures)" @click="pageFactures++" class="bg-blue-500 rounded-lg p-2 text-white m-2">Suiv</button>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
