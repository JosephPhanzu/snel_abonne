<?php
$title = "Réceptionnistes | Gestion du personnel d'accueil";
ob_start();
?>

<!-- En-tête -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Gestion des réceptionnistes</h1>
        <p class="text-gray-500 text-sm">Gérez le personnel d'accueil et d'administration</p>
    </div>
    <button id="openAddModalBtn" class="bg-primary hover:bg-blue-800 text-white px-5 py-2.5 rounded-xl shadow-md transition flex items-center gap-2 self-start">
        <i class="fas fa-plus-circle"></i> Nouveau réceptionniste
    </button>
</div>

<!-- Cartes statistiques réceptionnistes -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-primary">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">Total personnel</p><p id="totalReceptionistsCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-users text-3xl text-primary/30"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-emerald-500">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">En service</p><p id="activeCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-check-circle text-3xl text-emerald-500/30"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-secondary">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">En pause</p><p id="onBreakCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-coffee text-3xl text-secondary/30"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-amber-500">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">Postes</p><p id="positionsCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-badge text-3xl text-amber-500/30"></i>
        </div>
    </div>
</div>

<!-- Filtres et recherche -->
<div class="flex flex-col sm:flex-row justify-between gap-4 mb-6">
    <div class="flex flex-wrap gap-3">
        <select id="positionFilterSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white focus:ring-2 focus:ring-primary/30">
            <option value="all">Tous les postes</option>
            <option value="Accueil principal">Accueil principal</option>
            <option value="Urgences">Accueil Urgences</option>
            <option value="Consultations">Accueil Consultations</option>
            <option value="Hospitalisation">Accueil Hospitalisation</option>
            <option value="Administration">Administration</option>
        </select>
        <select id="statusFilterSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white focus:ring-2 focus:ring-primary/30">
            <option value="all">Tous statuts</option>
            <option value="en service">En service</option>
            <option value="en pause">En pause</option>
            <option value="absent">Absent</option>
            <option value="congé">En congé</option>
        </select>
        <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="searchInput" placeholder="Rechercher réceptionniste..." class="pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm w-64">
        </div>
        <button id="resetFiltersBtn" class="text-gray-600 hover:text-primary bg-gray-100 px-4 rounded-xl text-sm transition"><i class="fas fa-undo-alt mr-1"></i> Réinitialiser</button>
    </div>
</div>

<!-- Tableau des réceptionnistes -->
<div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
    <div class="overflow-x-auto custom-scroll">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3">Matricule</th>
                    <th class="px-4 py-3">Nom & Prénom</th>
                    <th class="px-4 py-3">Poste</th>
                    <th class="px-4 py-3">Contact</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3">Horaires</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                 </tr>
            </thead>
            <tbody id="receptionistTableBody" class="divide-y divide-gray-100"></tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div class="bg-gray-50 px-4 py-3 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-gray-200">
        <div class="text-xs text-gray-500"><span id="paginationInfo"></span></div>
        <div class="flex gap-2">
            <button id="prevPageBtn" class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 disabled:opacity-40 transition"><i class="fas fa-chevron-left"></i> Précédent</button>
            <button id="nextPageBtn" class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 disabled:opacity-40 transition">Suivant <i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</div>

<!-- MODAL AJOUT / MODIFICATION RÉCEPTIONNISTE -->
<div id="receptionistModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-200">
    <div class="bg-white rounded-2xl max-w-lg w-full mx-4 shadow-2xl overflow-hidden transform transition-all">
        <div class="bg-primary px-6 py-4 flex justify-between items-center">
            <h3 id="modalTitle" class="text-white font-bold text-xl">Ajouter un réceptionniste</h3>
            <button id="closeModalBtn" class="text-white/80 hover:text-white text-2xl">&times;</button>
        </div>
        <form id="receptionistForm" class="p-6 space-y-4">
            <input type="hidden" id="editId" value="">
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-gray-700 text-sm font-semibold">Nom *</label><input type="text" id="lastName" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/30"></div>
                <div><label class="block text-gray-700 text-sm font-semibold">Prénom *</label><input type="text" id="firstName" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5"></div>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold">Poste *</label>
                <select id="position" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5">
                    <option value="Accueil principal">Accueil principal</option>
                    <option value="Urgences">Accueil Urgences</option>
                    <option value="Consultations">Accueil Consultations</option>
                    <option value="Hospitalisation">Accueil Hospitalisation</option>
                    <option value="Administration">Administration</option>
                </select>
            </div>
            <div><label class="block text-gray-700 text-sm font-semibold">Téléphone *</label><input type="tel" id="phone" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5" placeholder="+33 X XX XX XX XX"></div>
            <div><label class="block text-gray-700 text-sm font-semibold">Email *</label><input type="email" id="email" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5" placeholder="prenom.nom@hopital.fr"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-gray-700 text-sm font-semibold">Horaire début</label><input type="time" id="shiftStart" class="w-full border border-gray-200 rounded-xl px-3 py-2.5" value="08:00"></div>
                <div><label class="block text-gray-700 text-sm font-semibold">Horaire fin</label><input type="time" id="shiftEnd" class="w-full border border-gray-200 rounded-xl px-3 py-2.5" value="17:00"></div>
            </div>
            <div><label class="block text-gray-700 text-sm font-semibold">Statut</label>
                <select id="status" class="w-full border border-gray-200 rounded-xl px-3 py-2.5">
                    <option value="en service">En service</option>
                    <option value="en pause">En pause</option>
                    <option value="absent">Absent</option>
                    <option value="congé">En congé</option>
                </select>
            </div>
            <div><label class="block text-gray-700 text-sm font-semibold">Date d'embauche</label><input type="date" id="hireDate" class="w-full border border-gray-200 rounded-xl px-3 py-2.5"></div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" id="cancelModalBtn" class="px-5 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">Annuler</button>
                <button type="submit" class="px-5 py-2 bg-primary text-white rounded-xl shadow hover:bg-blue-900 transition">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DÉTAILS RÉCEPTIONNISTE -->
<div id="viewReceptionistModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-200">
    <div class="bg-white rounded-2xl max-w-md w-full mx-4 shadow-2xl overflow-hidden">
        <div class="bg-primary px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold text-xl"><i class="fas fa-id-card mr-2"></i>Détails réceptionniste</h3>
            <button id="closeViewModalBtn" class="text-white/80 hover:text-white text-2xl">&times;</button>
        </div>
        <div class="p-6" id="viewReceptionistContent"></div>
    </div>
</div>

<script>
// ==================== DONNÉES RÉCEPTIONNISTES ====================
let receptionists = [
    { id: "R1001", lastName: "Laurent", firstName: "Marie", position: "Accueil principal", phone: "+33 6 12 34 56 78", email: "m.laurent@hopital.fr", shiftStart: "08:00", shiftEnd: "17:00", status: "en service", hireDate: "2020-03-15" },
    { id: "R1002", lastName: "Dubois", firstName: "Thomas", position: "Urgences", phone: "+33 6 23 45 67 89", email: "t.dubois@hopital.fr", shiftStart: "14:00", shiftEnd: "22:00", status: "en service", hireDate: "2021-06-10" },
    { id: "R1003", lastName: "Martins", firstName: "Sophie", position: "Consultations", phone: "+33 7 89 12 34 56", email: "s.martins@hopital.fr", shiftStart: "08:30", shiftEnd: "17:30", status: "en pause", hireDate: "2019-11-20" },
    { id: "R1004", lastName: "Lefebvre", firstName: "Nicolas", position: "Hospitalisation", phone: "+33 6 45 67 89 01", email: "n.lefebvre@hopital.fr", shiftStart: "09:00", shiftEnd: "18:00", status: "absent", hireDate: "2022-01-05" },
    { id: "R1005", lastName: "Garcia", firstName: "Camille", position: "Administration", phone: "+33 7 12 34 56 78", email: "c.garcia@hopital.fr", shiftStart: "08:00", shiftEnd: "16:30", status: "en service", hireDate: "2018-08-14" },
    { id: "R1006", lastName: "Petit", firstName: "Antoine", position: "Urgences", phone: "+33 6 98 76 54 32", email: "a.petit@hopital.fr", shiftStart: "22:00", shiftEnd: "06:00", status: "en service", hireDate: "2023-03-01" },
    { id: "R1007", lastName: "Roux", firstName: "Julie", position: "Accueil principal", phone: "+33 7 55 66 77 88", email: "j.roux@hopital.fr", shiftStart: "07:30", shiftEnd: "16:30", status: "congé", hireDate: "2021-10-12" },
    { id: "R1008", lastName: "Simon", firstName: "Pierre", position: "Consultations", phone: "+33 6 11 22 33 44", email: "p.simon@hopital.fr", shiftStart: "10:00", shiftEnd: "19:00", status: "en service", hireDate: "2020-09-22" }
];

// Génération ID unique
function generateReceptionistId() {
    let maxNum = 0;
    receptionists.forEach(r => { let num = parseInt(r.id.substring(1)); if (num > maxNum) maxNum = num; });
    return "R" + (maxNum + 1);
}

// État filtres et pagination
let currentSearch = "";
let currentPositionFilter = "all";
let currentStatusFilter = "all";
let currentPage = 1;
const rowsPerPage = 8;

function getFilteredReceptionists() {
    let filtered = receptionists.filter(r => {
        const searchTerm = currentSearch.toLowerCase();
        const fullName = `${r.firstName} ${r.lastName}`.toLowerCase();
        const matchSearch = fullName.includes(searchTerm) || 
                            r.lastName.toLowerCase().includes(searchTerm) || 
                            r.firstName.toLowerCase().includes(searchTerm) || 
                            r.position.toLowerCase().includes(searchTerm) ||
                            r.id.toLowerCase().includes(searchTerm);
        const matchPosition = (currentPositionFilter === "all") || (r.position === currentPositionFilter);
        const matchStatus = (currentStatusFilter === "all") || (r.status === currentStatusFilter);
        return matchSearch && matchPosition && matchStatus;
    });
    return filtered;
}

// Mise à jour des statistiques
function updateStats() {
    const total = receptionists.length;
    const active = receptionists.filter(r => r.status === "en service").length;
    const onBreak = receptionists.filter(r => r.status === "en pause").length;
    const uniquePositions = [...new Set(receptionists.map(r => r.position))].length;
    document.getElementById("totalReceptionistsCount").innerText = total;
    document.getElementById("activeCount").innerText = active;
    document.getElementById("onBreakCount").innerText = onBreak;
    document.getElementById("positionsCount").innerText = uniquePositions;
}

// Statut badge CSS
function getStatusBadgeClass(status) {
    switch(status) {
        case "en service": return "bg-emerald-100 text-emerald-700";
        case "en pause": return "bg-amber-100 text-amber-700";
        case "absent": return "bg-red-100 text-red-700";
        case "congé": return "bg-blue-100 text-blue-700";
        default: return "bg-gray-100 text-gray-700";
    }
}

function getStatusLabel(status) {
    const labels = { "en service": "En service", "en pause": "En pause", "absent": "Absent", "congé": "En congé" };
    return labels[status] || status;
}

function getPositionBadge(position) {
    const colors = {
        "Accueil principal": "bg-primary/10 text-primary",
        "Urgences": "bg-red-100 text-red-700",
        "Consultations": "bg-secondary/10 text-secondary",
        "Hospitalisation": "bg-emerald-100 text-emerald-700",
        "Administration": "bg-purple-100 text-purple-700"
    };
    return colors[position] || "bg-gray-100 text-gray-700";
}

// Rendu tableau
function renderTable() {
    const filtered = getFilteredReceptionists();
    const totalFiltered = filtered.length;
    const totalPages = Math.ceil(totalFiltered / rowsPerPage);
    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
    if (currentPage < 1) currentPage = 1;
    const start = (currentPage - 1) * rowsPerPage;
    const paginated = filtered.slice(start, start + rowsPerPage);
    
    const tbody = document.getElementById("receptionistTableBody");
    tbody.innerHTML = "";
    if (paginated.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center py-12 text-gray-400"><i class="fas fa-user-slash text-3xl mb-2 block"></i>Aucun réceptionniste trouvé</td></tr>`;
    } else {
        paginated.forEach(r => {
            const statusClass = getStatusBadgeClass(r.status);
            const positionClass = getPositionBadge(r.position);
            const row = `
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono text-xs font-semibold text-gray-600">${r.id}</td>
                    <td class="px-4 py-3 font-medium">${escapeHtml(r.firstName)} ${escapeHtml(r.lastName)}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-lg text-xs font-medium ${positionClass}">${escapeHtml(r.position)}</span></td>
                    <td class="px-4 py-3 text-sm">${escapeHtml(r.phone || "—")}</td>
                    <td class="px-4 py-3 text-sm">${escapeHtml(r.email)}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-semibold ${statusClass}">${getStatusLabel(r.status)}</span></td>
                    <td class="px-4 py-3 text-sm">${r.shiftStart} - ${r.shiftEnd}</td>
                    <td class="px-4 py-3 text-center space-x-2">
                        <button onclick="viewReceptionist('${r.id}')" class="text-gray-500 hover:text-secondary p-1" title="Voir détails"><i class="fas fa-eye"></i></button>
                        <button onclick="editReceptionist('${r.id}')" class="text-blue-600 hover:text-blue-800 p-1" title="Modifier"><i class="far fa-edit"></i></button>
                        <button onclick="deleteReceptionist('${r.id}')" class="text-red-500 hover:text-red-700 p-1" title="Supprimer"><i class="far fa-trash-alt"></i></button>
                      </td>
                  </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }
    document.getElementById("paginationInfo").innerHTML = `${totalFiltered ? start+1 : 0} - ${Math.min(start+rowsPerPage, totalFiltered)} sur ${totalFiltered} réceptionnistes`;
    document.getElementById("prevPageBtn").disabled = currentPage === 1 || totalFiltered === 0;
    document.getElementById("nextPageBtn").disabled = currentPage === totalPages || totalFiltered === 0;
    updateStats();
}

function escapeHtml(str) { if(!str) return ''; return str.replace(/[&<>]/g, function(m){if(m==='&') return '&amp;'; if(m==='<') return '&lt;'; if(m==='>') return '&gt;'; return m;}); }

// ==================== ACTIONS CRUD ====================

// Voir détails réceptionniste
window.viewReceptionist = (id) => {
    const receptionist = receptionists.find(r => r.id === id);
    if (!receptionist) return;
    
    const content = `
        <div class="space-y-4">
            <div class="flex justify-center">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary to-blue-700 flex items-center justify-center text-white text-3xl font-bold">
                    ${receptionist.firstName.charAt(0)}${receptionist.lastName.charAt(0)}
                </div>
            </div>
            <div class="text-center">
                <h4 class="text-xl font-bold text-gray-800">${receptionist.firstName} ${receptionist.lastName}</h4>
                <p class="text-secondary font-medium">${receptionist.position}</p>
            </div>
            <div class="border-t border-gray-100 pt-4 space-y-2">
                <div class="flex justify-between"><span class="text-gray-500 text-sm">Matricule:</span><span class="font-mono">${receptionist.id}</span></div>
                <div class="flex justify-between"><span class="text-gray-500 text-sm">Téléphone:</span><span>${receptionist.phone || "Non renseigné"}</span></div>
                <div class="flex justify-between"><span class="text-gray-500 text-sm">Email:</span><span>${receptionist.email}</span></div>
                <div class="flex justify-between"><span class="text-gray-500 text-sm">Horaires:</span><span>${receptionist.shiftStart} - ${receptionist.shiftEnd}</span></div>
                <div class="flex justify-between"><span class="text-gray-500 text-sm">Statut:</span><span class="px-2 py-0.5 rounded-full text-xs ${getStatusBadgeClass(receptionist.status)}">${getStatusLabel(receptionist.status)}</span></div>
                <div class="flex justify-between"><span class="text-gray-500 text-sm">Date d'embauche:</span><span>${receptionist.hireDate || "Non renseignée"}</span></div>
            </div>
        </div>
    `;
    document.getElementById("viewReceptionistContent").innerHTML = content;
    document.getElementById("viewReceptionistModal").classList.remove("hidden");
};

// Éditer réceptionniste
window.editReceptionist = (id) => {
    const receptionist = receptionists.find(r => r.id === id);
    if (receptionist) {
        document.getElementById("modalTitle").innerText = "Modifier le réceptionniste";
        document.getElementById("editId").value = receptionist.id;
        document.getElementById("lastName").value = receptionist.lastName;
        document.getElementById("firstName").value = receptionist.firstName;
        document.getElementById("position").value = receptionist.position;
        document.getElementById("phone").value = receptionist.phone || "";
        document.getElementById("email").value = receptionist.email;
        document.getElementById("shiftStart").value = receptionist.shiftStart;
        document.getElementById("shiftEnd").value = receptionist.shiftEnd;
        document.getElementById("status").value = receptionist.status;
        document.getElementById("hireDate").value = receptionist.hireDate || "";
        document.getElementById("receptionistModal").classList.remove("hidden");
    }
};

// Supprimer réceptionniste
window.deleteReceptionist = (id) => {
    const receptionist = receptionists.find(r => r.id === id);
    if (receptionist) {
        if (confirm(`⚠️ Supprimer définitivement ${receptionist.firstName} ${receptionist.lastName} ?`)) {
            receptionists = receptionists.filter(r => r.id !== id);
            if (getFilteredReceptionists().length === 0 && currentPage > 1) currentPage--;
            renderTable();
            alert(`✅ ${receptionist.firstName} ${receptionist.lastName} a été supprimé`);
        }
    }
};

// ==================== FORMULAIRE AJOUT / MODIFICATION ====================
document.getElementById("receptionistForm").addEventListener("submit", (e) => {
    e.preventDefault();
    const editId = document.getElementById("editId").value;
    const lastName = document.getElementById("lastName").value.trim();
    const firstName = document.getElementById("firstName").value.trim();
    const email = document.getElementById("email").value.trim();
    const phone = document.getElementById("phone").value.trim();
    
    if (!lastName || !firstName) return alert("Nom et prénom obligatoires");
    if (!email) return alert("Email obligatoire");
    if (!email.includes("@")) return alert("Email invalide");
    if (!phone) return alert("Téléphone obligatoire");
    
    const formData = {
        lastName, firstName,
        position: document.getElementById("position").value,
        phone: phone,
        email: email,
        shiftStart: document.getElementById("shiftStart").value,
        shiftEnd: document.getElementById("shiftEnd").value,
        status: document.getElementById("status").value,
        hireDate: document.getElementById("hireDate").value,
    };
    
    if (editId) {
        const idx = receptionists.findIndex(r => r.id === editId);
        if (idx !== -1) receptionists[idx] = { ...receptionists[idx], ...formData, id: editId };
        alert(`✅ Réceptionniste ${firstName} ${lastName} modifié avec succès`);
    } else {
        const newId = generateReceptionistId();
        receptionists.push({ id: newId, ...formData });
        alert(`✅ Réceptionniste ${firstName} ${lastName} ajouté avec succès (Matricule: ${newId})`);
    }
    closeModal();
    renderTable();
});

function closeModal() {
    document.getElementById("receptionistModal").classList.add("hidden");
    document.getElementById("receptionistForm").reset();
    document.getElementById("editId").value = "";
    document.getElementById("modalTitle").innerText = "Ajouter un réceptionniste";
}

// ==================== GESTION DES MODALS ====================
document.getElementById("openAddModalBtn").onclick = () => {
    document.getElementById("editId").value = "";
    document.getElementById("receptionistForm").reset();
    document.getElementById("modalTitle").innerText = "Ajouter un réceptionniste";
    document.getElementById("receptionistModal").classList.remove("hidden");
};
document.getElementById("closeModalBtn").onclick = closeModal;
document.getElementById("cancelModalBtn").onclick = closeModal;
document.getElementById("closeViewModalBtn").onclick = () => {
    document.getElementById("viewReceptionistModal").classList.add("hidden");
};

// ==================== FILTRES ET RECHERCHE ====================
function updateFiltersAndSearch() {
    currentSearch = document.getElementById("searchInput").value;
    currentPositionFilter = document.getElementById("positionFilterSelect").value;
    currentStatusFilter = document.getElementById("statusFilterSelect").value;
    currentPage = 1;
    renderTable();
}

document.getElementById("searchInput").addEventListener("input", updateFiltersAndSearch);
document.getElementById("positionFilterSelect").addEventListener("change", updateFiltersAndSearch);
document.getElementById("statusFilterSelect").addEventListener("change", updateFiltersAndSearch);
document.getElementById("resetFiltersBtn").addEventListener("click", () => {
    document.getElementById("searchInput").value = "";
    document.getElementById("positionFilterSelect").value = "all";
    document.getElementById("statusFilterSelect").value = "all";
    updateFiltersAndSearch();
});

// ==================== PAGINATION ====================
document.getElementById("prevPageBtn").onclick = () => { if (currentPage > 1) { currentPage--; renderTable(); } };
document.getElementById("nextPageBtn").onclick = () => { 
    const max = Math.ceil(getFilteredReceptionists().length / rowsPerPage); 
    if (currentPage < max) { currentPage++; renderTable(); } 
};

// ==================== SIDEBAR MOBILE ====================
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("mobileSidebarOverlay");
if (document.getElementById("mobileMenuBtn")) {
    document.getElementById("mobileMenuBtn").onclick = () => {
        sidebar.classList.remove("-translate-x-full");
        overlay.classList.remove("invisible", "opacity-0");
        overlay.classList.add("opacity-100", "visible");
    };
}
function closeSidebar() {
    sidebar.classList.add("-translate-x-full");
    overlay.classList.remove("opacity-100", "visible");
    overlay.classList.add("invisible", "opacity-0");
}
if (overlay) overlay.addEventListener("click", closeSidebar);

// ==================== INITIALISATION ====================
renderTable();
</script>

<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
?>