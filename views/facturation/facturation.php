<?php
$title = "Facturation | Gestion financière";
ob_start();
?>

<!-- En-tête -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Gestion de la facturation</h1>
        <p class="text-gray-500 text-sm">Suivez les paiements, émettez des factures et gérez les remboursements</p>
    </div>
    <button id="openAddModalBtn" class="bg-primary hover:bg-blue-800 text-white px-5 py-2.5 rounded-xl shadow-md transition flex items-center gap-2 self-start">
        <i class="fas fa-plus-circle"></i> Nouvelle facture
    </button>
</div>

<!-- Cartes statistiques facturation -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-primary">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">CA total (mois)</p><p id="totalRevenue" class="text-2xl font-bold text-gray-800">0 €</p></div>
            <i class="fas fa-chart-line text-3xl text-primary/30"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-emerald-500">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">Payées</p><p id="paidCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-check-circle text-3xl text-emerald-500/30"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-secondary">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">En attente</p><p id="pendingCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-clock text-3xl text-secondary/30"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-amber-500">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">En retard</p><p id="overdueCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-exclamation-triangle text-3xl text-amber-500/30"></i>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="flex flex-col sm:flex-row justify-between gap-4">
    <div class="flex flex-wrap gap-3">
        <select id="statusFilterSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white focus:ring-2 focus:ring-primary/30">
            <option value="all">Tous les statuts</option>
            <option value="payée">Payée</option>
            <option value="en attente">En attente</option>
            <option value="en retard">En retard</option>
            <option value="remboursée">Remboursée</option>
        </select>
        <select id="periodFilterSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white focus:ring-2 focus:ring-primary/30">
            <option value="all">Toutes périodes</option>
            <option value="today">Aujourd'hui</option>
            <option value="week">Cette semaine</option>
            <option value="month">Ce mois</option>
        </select>
        <button id="resetFiltersBtn" class="text-gray-600 hover:text-primary bg-gray-100 px-4 rounded-xl text-sm transition"><i class="fas fa-undo-alt mr-1"></i> Réinitialiser</button>
    </div>
    <div class="flex gap-2">
        <button id="exportBtn" class="bg-secondary hover:bg-orange-600 text-white px-4 py-2 rounded-xl text-sm transition flex items-center gap-2">
            <i class="fas fa-file-excel"></i> Exporter
        </button>
    </div>
</div>

<!-- Tableau factures -->
<div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
    <div class="overflow-x-auto custom-scroll">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3">N° Facture</th>
                    <th class="px-4 py-3">Patient</th>
                    <th class="px-4 py-3">Date émission</th>
                    <th class="px-4 py-3">Échéance</th>
                    <th class="px-4 py-3">Montant</th>
                    <th class="px-4 py-3">Prestations</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                  </tr>
            </thead>
            <tbody id="invoiceTableBody" class="divide-y divide-gray-100"></tbody>
        </table>
    </div>
    <div class="bg-gray-50 px-4 py-3 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-gray-200">
        <div class="text-xs text-gray-500"><span id="paginationInfo"></span></div>
        <div class="flex gap-2">
            <button id="prevPageBtn" class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 disabled:opacity-40"><i class="fas fa-chevron-left"></i> Précédent</button>
            <button id="nextPageBtn" class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 disabled:opacity-40">Suivant <i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</div>

<!-- MODAL AJOUT / MODIFICATION FACTURE -->
<div id="invoiceModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-200">
    <div class="bg-white rounded-2xl max-w-lg w-full mx-4 shadow-2xl overflow-hidden">
        <div class="bg-primary px-6 py-4 flex justify-between items-center">
            <h3 id="modalTitle" class="text-white font-bold text-xl">Nouvelle facture</h3>
            <button id="closeModalBtn" class="text-white/80 hover:text-white text-2xl">&times;</button>
        </div>
        <form id="invoiceForm" class="p-6 space-y-4">
            <input type="hidden" id="editId" value="">
            <div>
                <label class="block text-gray-700 text-sm font-semibold">Patient *</label>
                <select id="patientId" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5">
                    <option value="">Sélectionner un patient</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-gray-700 text-sm font-semibold">Date émission *</label><input type="date" id="issueDate" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5"></div>
                <div><label class="block text-gray-700 text-sm font-semibold">Date échéance *</label><input type="date" id="dueDate" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5"></div>
            </div>
            <div><label class="block text-gray-700 text-sm font-semibold">Montant (€) *</label><input type="number" id="amount" step="0.01" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5" placeholder="0.00"></div>
            <div><label class="block text-gray-700 text-sm font-semibold">Prestations</label><textarea id="services" rows="3" class="w-full border border-gray-200 rounded-xl px-3 py-2.5" placeholder="Consultation, examens, hospitalisation..."></textarea></div>
            <div><label class="block text-gray-700 text-sm font-semibold">Statut</label>
                <select id="status" class="w-full border border-gray-200 rounded-xl px-3 py-2.5">
                    <option value="en attente">En attente</option>
                    <option value="payée">Payée</option>
                    <option value="en retard">En retard</option>
                    <option value="remboursée">Remboursée</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" id="cancelModalBtn" class="px-5 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">Annuler</button>
                <button type="submit" class="px-5 py-2 bg-primary text-white rounded-xl shadow hover:bg-blue-900">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
    const patientsList = [
        { id: "P1001", name: "Sophie Dubois" }, { id: "P1002", name: "Lucas Martin" },
        { id: "P1003", name: "Emma Petit" }, { id: "P1004", name: "Thomas Bernard" },
        { id: "P1005", name: "Julie Roux" }, { id: "P1006", name: "Nicolas Leroy" }
    ];

    let invoices = [
        { id: "F2025001", patientId: "P1001", patientName: "Sophie Dubois", issueDate: "2025-04-01", dueDate: "2025-04-15", amount: 450.00, services: "Consultation cardiologie + Échographie", status: "payée" },
        { id: "F2025002", patientId: "P1002", patientName: "Lucas Martin", issueDate: "2025-04-05", dueDate: "2025-04-20", amount: 320.50, services: "IRM cérébrale + Consultation", status: "en attente" },
        { id: "F2025003", patientId: "P1003", patientName: "Emma Petit", issueDate: "2025-04-10", dueDate: "2025-04-25", amount: 180.00, services: "Vaccin + Consultation pédiatrique", status: "en attente" },
        { id: "F2025004", patientId: "P1004", patientName: "Thomas Bernard", issueDate: "2025-03-20", dueDate: "2025-04-05", amount: 890.00, services: "Hospitalisation + Radiographie", status: "en retard" },
        { id: "F2025005", patientId: "P1005", patientName: "Julie Roux", issueDate: "2025-04-12", dueDate: "2025-04-27", amount: 250.00, services: "Consultation orthopédique", status: "payée" },
        { id: "F2025006", patientId: "P1006", patientName: "Nicolas Leroy", issueDate: "2025-04-08", dueDate: "2025-04-23", amount: 520.00, services: "Bilan diabète + Analyses", status: "remboursée" }
    ];

    function generateInvoiceId() {
        let maxNum = 0;
        invoices.forEach(i => { let num = parseInt(i.id.substring(4)); if (num > maxNum) maxNum = num; });
        return "F" + String(new Date().getFullYear()) + String(maxNum + 1).padStart(4, '0');
    }

    let currentStatusFilter = "all";
    let currentPeriodFilter = "all";
    let currentPage = 1;
    const rowsPerPage = 5;

    function getFilteredInvoices() {
        let filtered = invoices.filter(i => {
            const matchStatus = (currentStatusFilter === "all") || (i.status === currentStatusFilter);
            let matchPeriod = true;
            const today = new Date();
            const issueDate = new Date(i.issueDate);
            
            if (currentPeriodFilter === "today") {
                matchPeriod = issueDate.toDateString() === today.toDateString();
            } else if (currentPeriodFilter === "week") {
                const weekAgo = new Date();
                weekAgo.setDate(weekAgo.getDate() - 7);
                matchPeriod = issueDate >= weekAgo;
            } else if (currentPeriodFilter === "month") {
                matchPeriod = issueDate.getMonth() === today.getMonth() && issueDate.getFullYear() === today.getFullYear();
            }
            return matchStatus && matchPeriod;
        });
        return filtered;
    }

    function updateStats() {
        const totalRevenue = invoices.reduce((sum, i) => sum + i.amount, 0);
        const paid = invoices.filter(i => i.status === "payée").length;
        const pending = invoices.filter(i => i.status === "en attente").length;
        const overdue = invoices.filter(i => i.status === "en retard").length;
        document.getElementById("totalRevenue").innerHTML = totalRevenue.toLocaleString('fr-FR') + " €";
        document.getElementById("paidCount").innerText = paid;
        document.getElementById("pendingCount").innerText = pending;
        document.getElementById("overdueCount").innerText = overdue;
    }

    function populatePatientSelect() {
        const select = document.getElementById("patientId");
        select.innerHTML = '<option value="">Sélectionner un patient</option>';
        patientsList.forEach(p => {
            select.innerHTML += `<option value="${p.id}">${p.name}</option>`;
        });
    }

    function renderTable() {
        const filtered = getFilteredInvoices();
        const totalFiltered = filtered.length;
        const totalPages = Math.ceil(totalFiltered / rowsPerPage);
        if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
        const start = (currentPage - 1) * rowsPerPage;
        const paginated = filtered.slice(start, start + rowsPerPage);
        
        const tbody = document.getElementById("invoiceTableBody");
        tbody.innerHTML = "";
        if (paginated.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center py-12 text-gray-400"><i class="fas fa-file-invoice text-3xl mb-2 block"></i>Aucune facture trouvée</td></tr>`;
        } else {
            paginated.forEach(i => {
                let statusClass = {
                    "payée": "bg-emerald-100 text-emerald-700",
                    "en attente": "bg-amber-100 text-amber-700",
                    "en retard": "bg-red-100 text-red-700",
                    "remboursée": "bg-blue-100 text-blue-700"
                }[i.status] || "bg-gray-100";
                
                const row = `<tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono text-xs font-semibold">${i.id}</td>
                    <td class="px-4 py-3 font-medium">${i.patientName}</td>
                    <td class="px-4 py-3">${i.issueDate}</td>
                    <td class="px-4 py-3">${i.dueDate}</td>
                    <td class="px-4 py-3 font-semibold text-primary">${i.amount.toFixed(2)} €</td>
                    <td class="px-4 py-3 text-sm max-w-xs truncate">${i.services.substring(0, 50)}${i.services.length > 50 ? '...' : ''}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-semibold ${statusClass}">${i.status}</span></td>
                    <td class="px-4 py-3 text-center space-x-2">
                        <button onclick="editInvoice('${i.id}')" class="text-blue-600 hover:text-blue-800"><i class="far fa-edit"></i></button>
                        <button onclick="deleteInvoice('${i.id}')" class="text-red-500 hover:text-red-700"><i class="far fa-trash-alt"></i></button>
                        <button onclick="viewInvoice('${i.id}')" class="text-gray-500 hover:text-secondary"><i class="fas fa-print"></i></button>
                    </td>
                </tr>`;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }
        document.getElementById("paginationInfo").innerHTML = `${totalFiltered ? start+1 : 0} - ${Math.min(start+rowsPerPage, totalFiltered)} sur ${totalFiltered}`;
        document.getElementById("prevPageBtn").disabled = currentPage === 1;
        document.getElementById("nextPageBtn").disabled = currentPage === totalPages;
        updateStats();
    }

    window.editInvoice = (id) => {
        const invoice = invoices.find(i => i.id === id);
        if (invoice) {
            document.getElementById("modalTitle").innerText = "Modifier la facture";
            document.getElementById("editId").value = invoice.id;
            document.getElementById("patientId").value = invoice.patientId;
            document.getElementById("issueDate").value = invoice.issueDate;
            document.getElementById("dueDate").value = invoice.dueDate;
            document.getElementById("amount").value = invoice.amount;
            document.getElementById("services").value = invoice.services;
            document.getElementById("status").value = invoice.status;
            document.getElementById("invoiceModal").classList.remove("hidden");
        }
    };

    window.deleteInvoice = (id) => {
        if (confirm("Supprimer cette facture ? Cette action est irréversible.")) {
            invoices = invoices.filter(i => i.id !== id);
            renderTable();
        }
    };

    window.viewInvoice = (id) => {
        const i = invoices.find(inv => inv.id === id);
        alert(`📄 FACTURE ${i.id}\nPatient: ${i.patientName}\nDate: ${i.issueDate}\nÉchéance: ${i.dueDate}\nMontant: ${i.amount.toFixed(2)} €\nPrestations: ${i.services}\nStatut: ${i.status}`);
    };

    document.getElementById("invoiceForm").addEventListener("submit", (e) => {
        e.preventDefault();
        const editId = document.getElementById("editId").value;
        const patientId = document.getElementById("patientId").value;
        if (!patientId) return alert("Veuillez sélectionner un patient");
        
        const patient = patientsList.find(p => p.id === patientId);
        
        const formData = {
            patientId, patientName: patient.name,
            issueDate: document.getElementById("issueDate").value,
            dueDate: document.getElementById("dueDate").value,
            amount: parseFloat(document.getElementById("amount").value),
            services: document.getElementById("services").value,
            status: document.getElementById("status").value
        };
        
        if (editId) {
            const idx = invoices.findIndex(i => i.id === editId);
            if (idx !== -1) invoices[idx] = { ...invoices[idx], ...formData, id: editId };
        } else {
            invoices.push({ id: generateInvoiceId(), ...formData });
        }
        closeModal();
        renderTable();
    });

    function closeModal() {
        document.getElementById("invoiceModal").classList.add("hidden");
        document.getElementById("invoiceForm").reset();
        document.getElementById("editId").value = "";
    }

    document.getElementById("openAddModalBtn").onclick = () => {
        document.getElementById("editId").value = "";
        document.getElementById("invoiceForm").reset();
        document.getElementById("modalTitle").innerText = "Nouvelle facture";
        document.getElementById("invoiceModal").classList.remove("hidden");
    };
    document.getElementById("closeModalBtn").onclick = closeModal;
    document.getElementById("cancelModalBtn").onclick = closeModal;

    document.getElementById("exportBtn").onclick = () => {
        alert("Export Excel - Fonctionnalité disponible dans la version premium");
    };

    function updateFilters() {
        currentStatusFilter = document.getElementById("statusFilterSelect").value;
        currentPeriodFilter = document.getElementById("periodFilterSelect").value;
        currentPage = 1;
        renderTable();
    }

    document.getElementById("statusFilterSelect").addEventListener("change", updateFilters);
    document.getElementById("periodFilterSelect").addEventListener("change", updateFilters);
    document.getElementById("resetFiltersBtn").addEventListener("click", () => {
        document.getElementById("statusFilterSelect").value = "all";
        document.getElementById("periodFilterSelect").value = "all";
        updateFilters();
    });
    document.getElementById("prevPageBtn").onclick = () => { if (currentPage > 1) { currentPage--; renderTable(); } };
    document.getElementById("nextPageBtn").onclick = () => { const max = Math.ceil(getFilteredInvoices().length / rowsPerPage); if (currentPage < max) { currentPage++; renderTable(); } };

    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("mobileSidebarOverlay");
    document.getElementById("mobileMenuBtn").onclick = () => {
        sidebar.classList.remove("-translate-x-full");
        overlay.classList.remove("invisible", "opacity-0");
        overlay.classList.add("opacity-100", "visible");
    };
    function closeSidebarMobile() {
        sidebar.classList.add("-translate-x-full");
        overlay.classList.remove("opacity-100", "visible");
        overlay.classList.add("invisible", "opacity-0");
    }
    overlay.addEventListener("click", closeSidebarMobile);

    populatePatientSelect();
    renderTable();
</script>

<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
?>