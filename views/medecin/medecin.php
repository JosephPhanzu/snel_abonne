<?php
$title = "Médecins | Gestion du corps médical";
ob_start();
?>

<!-- En-tête -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Gestion des médecins</h1>
        <p class="text-gray-500 text-sm">Ajoutez, modifiez et gérez les médecins de l'établissement</p>
    </div>
    <button id="openAddModalBtn" class="bg-primary hover:bg-blue-800 text-white px-5 py-2.5 rounded-xl shadow-md transition flex items-center gap-2 self-start">
        <i class="fas fa-plus-circle"></i> Nouveau médecin
    </button>
</div>

<!-- Cartes statistiques médecins -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-primary">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">Total médecins</p><p id="totalDoctorsCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-user-md text-3xl text-primary/30"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-emerald-500">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">Disponibles</p><p id="availableCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-check-circle text-3xl text-emerald-500/30"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-secondary">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">En consultation</p><p id="consultingCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-stethoscope text-3xl text-secondary/30"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-amber-500">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">Spécialités</p><p id="specialtiesCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-brain text-3xl text-amber-500/30"></i>
        </div>
    </div>
</div>

<!-- Filtres et recherche -->
<div class="flex flex-col sm:flex-row justify-between gap-4 mb-6">
    <div class="flex flex-wrap gap-3">
        <select id="specialtyFilterSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white focus:ring-2 focus:ring-primary/30">
            <option value="all">Toutes spécialités</option>
        </select>
        <select id="statusFilterSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white focus:ring-2 focus:ring-primary/30">
            <option value="all">Tous statuts</option>
            <option value="disponible">Disponible</option>
            <option value="en consultation">En consultation</option>
            <option value="en congé">En congé</option>
            <option value="absent">Absent</option>
        </select>
        <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="searchInput" placeholder="Rechercher médecin..." class="pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm w-64">
        </div>
        <button id="resetFiltersBtn" class="text-gray-600 hover:text-primary bg-gray-100 px-4 rounded-xl text-sm transition"><i class="fas fa-undo-alt mr-1"></i> Réinitialiser</button>
    </div>
</div>

<!-- Tableau des médecins -->
<div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
    <div class="overflow-x-auto custom-scroll">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3">Matricule</th>
                    <th class="px-4 py-3">Nom & Prénom</th>
                    <th class="px-4 py-3">Spécialité</th>
                    <th class="px-4 py-3">Contact</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3">Patients</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                 </tr>
            </thead>
            <tbody id="doctorTableBody" class="divide-y divide-gray-100"></tbody>
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

<!-- MODAL AJOUT / MODIFICATION MÉDECIN -->
<div id="doctorModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-200">
    <div class="bg-white rounded-2xl max-w-lg w-full mx-4 shadow-2xl overflow-hidden transform transition-all overflow-y-auto max-h-[90vh]">
        <div class="bg-primary px-6 py-4 flex justify-between items-center">
            <h3 id="modalTitle" class="text-white font-bold text-xl">Ajouter un médecin</h3>
            <button id="closeModalBtn" class="text-white/80 hover:text-white text-2xl">&times;</button>
        </div>
        <form id="doctorForm" class="p-6 space-y-4">
            <input type="hidden" id="editId" value="">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold">Nom *</label>
                    <input type="text" name="nom" id="lastName" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/30">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold">Prénom *</label>
                    <input type="text" name="prenom" id="firstName" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5">
                </div>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold">Spécialité *</label>
                <select name="specialite" id="specialty" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5">
                    <option value="Cardiologie">Cardiologie</option>
                    <option value="Neurologie">Neurologie</option>
                    <option value="Pédiatrie">Pédiatrie</option>
                    <option value="Orthopédie">Orthopédie</option>
                    <option value="Gynécologie">Gynécologie</option>
                    <option value="Dermatologie">Dermatologie</option>
                    <option value="Psychiatrie">Psychiatrie</option>
                    <option value="Médecine générale">Médecine générale</option>
                    <option value="Pneumologie">Pneumologie</option>
                    <option value="Gastro-entérologie">Gastro-entérologie</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold">Téléphone</label>
                <input type="tel" name="tel" id="phone" class="w-full border border-gray-200 rounded-xl px-3 py-2.5" placeholder="+33 X XX XX XX XX">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold">Email *</label>
                <input type="email" name="email" id="email" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5" placeholder="prenom.nom@hopital.fr">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold">Mot de passe *</label>
                <input type="password" name="mdp" id="password" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5" placeholder="••••••••">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold">Confirmer le mot de passe *</label>
                <input type="password" name="mdp1" id="confirmPassword" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5" placeholder="••••••••">
            </div>

            <div><label class="block text-gray-700 text-sm font-semibold">Statut</label>
                <select name="statut" id="status" class="w-full border border-gray-200 rounded-xl px-3 py-2.5">
                    <option value="disponible">Disponible</option>
                    <option value="en consultation">En consultation</option>
                    <option value="en congé">En congé</option>
                    <option value="absent">Absent</option>
                </select>
            </div>
            
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" id="cancelModalBtn" class="px-5 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">Annuler</button>
                <button type="submit" class="px-5 py-2 bg-primary text-white rounded-xl shadow hover:bg-blue-900 transition">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DÉTAILS MÉDECIN -->
<div id="viewDoctorModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-200">
    <div class="bg-white rounded-2xl max-w-md w-full mx-4 shadow-2xl overflow-hidden">
        <div class="bg-primary px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold text-xl"><i class="fas fa-user-md mr-2"></i>Détails médecin</h3>
            <button id="closeViewModalBtn" class="text-white/80 hover:text-white text-2xl">&times;</button>
        </div>
        <div class="p-6" id="viewDoctorContent"></div>
    </div>
</div>

<script>
// ==================== DONNÉES MÉDECINS ====================
let doctors = [];
doctors = [
    { id: "M1001", lastName: "Martin", firstName: "Sophie", specialty: "Cardiologie", phone: "+33 6 12 34 56 78", email: "s.martin@hopital.fr", status: "disponible", patientCount: 24 },
    { id: "M1002", lastName: "Bernard", firstName: "Thomas", specialty: "Neurologie", phone: "+33 6 23 45 67 89", email: "t.bernard@hopital.fr", status: "en consultation", patientCount: 18 },
    { id: "M1003", lastName: "Petit", firstName: "Julie", specialty: "Pédiatrie", phone: "+33 7 89 12 34 56", email: "j.petit@hopital.fr", status: "disponible", patientCount: 32 },
    { id: "M1004", lastName: "Robert", firstName: "Nicolas", specialty: "Orthopédie", phone: "+33 6 45 67 89 01", email: "n.robert@hopital.fr", status: "en congé", patientCount: 12 },
    { id: "M1005", lastName: "Durand", firstName: "Camille", specialty: "Gynécologie", phone: "+33 7 12 34 56 78", email: "c.durand@hopital.fr", status: "disponible", patientCount: 41 },
    { id: "M1006", lastName: "Moreau", firstName: "Antoine", specialty: "Médecine générale", phone: "+33 6 98 76 54 32", email: "a.moreau@hopital.fr", status: "en consultation", patientCount: 56 },
    { id: "M1007", lastName: "Simon", firstName: "Laure", specialty: "Dermatologie", phone: "+33 7 55 66 77 88", email: "l.simon@hopital.fr", status: "disponible", patientCount: 23 },
    { id: "M1008", lastName: "Michel", firstName: "Pierre", specialty: "Psychiatrie", phone: "+33 6 11 22 33 44", email: "p.michel@hopital.fr", status: "absent", patientCount: 8 },
    { id: "M1009", lastName: "Lefebvre", firstName: "Claire", specialty: "Pneumologie", phone: "+33 6 77 88 99 00", email: "c.lefebvre@hopital.fr", status: "disponible", patientCount: 15 },
    { id: "M1010", lastName: "Garcia", firstName: "Philippe", specialty: "Gastro-entérologie", phone: "+33 6 99 00 11 22", email: "p.garcia@hopital.fr", status: "en consultation", patientCount: 27 }
];

// Génération ID unique
function generateDoctorId() {
    let maxNum = 0;
    doctors.forEach(d => { let num = parseInt(d.id.substring(1)); if (num > maxNum) maxNum = num; });
    return "M" + (maxNum + 1);
}

// État filtres et pagination
let currentSearch = "";
let currentSpecialtyFilter = "all";
let currentStatusFilter = "all";
let currentPage = 1;
const rowsPerPage = 8;

// Récupérer toutes les spécialités uniques
function getUniqueSpecialties() {
    const specialties = [...new Set(doctors.map(d => d.specialty))];
    return specialties.sort();
}

function populateSpecialtyFilter() {
    const select = document.getElementById("specialtyFilterSelect");
    const specialties = getUniqueSpecialties();
    select.innerHTML = '<option value="all">Toutes spécialités</option>';
    specialties.forEach(spec => {
        select.innerHTML += `<option value="${spec}">${spec}</option>`;
    });
}

function getFilteredDoctors() {
    let filtered = doctors.filter(d => {
        const searchTerm = currentSearch.toLowerCase();
        const fullName = `${d.firstName} ${d.lastName}`.toLowerCase();
        const matchSearch = fullName.includes(searchTerm) || 
                            d.lastName.toLowerCase().includes(searchTerm) || 
                            d.firstName.toLowerCase().includes(searchTerm) || 
                            d.specialty.toLowerCase().includes(searchTerm) ||
                            d.id.toLowerCase().includes(searchTerm);
        const matchSpecialty = (currentSpecialtyFilter === "all") || (d.specialty === currentSpecialtyFilter);
        const matchStatus = (currentStatusFilter === "all") || (d.status === currentStatusFilter);
        return matchSearch && matchSpecialty && matchStatus;
    });
    return filtered;
}

// Mise à jour des statistiques
function updateStats() {
    const total = doctors.length;
    const available = doctors.filter(d => d.status === "disponible").length;
    const consulting = doctors.filter(d => d.status === "en consultation").length;
    const uniqueSpecialties = getUniqueSpecialties().length;
    document.getElementById("totalDoctorsCount").innerText = total;
    document.getElementById("availableCount").innerText = available;
    document.getElementById("consultingCount").innerText = consulting;
    document.getElementById("specialtiesCount").innerText = uniqueSpecialties;
}

// Statut badge CSS
function getStatusBadgeClass(status) {
    switch(status) {
        case "disponible": return "bg-emerald-100 text-emerald-700";
        case "en consultation": return "bg-amber-100 text-amber-700";
        case "en congé": return "bg-blue-100 text-blue-700";
        case "absent": return "bg-red-100 text-red-700";
        default: return "bg-gray-100 text-gray-700";
    }
}

function getStatusLabel(status) {
    const labels = { "disponible": "Disponible", "en consultation": "En consultation", "en congé": "En congé", "absent": "Absent" };
    return labels[status] || status;
}

// Rendu tableau
function renderTable() {
    const filtered = getFilteredDoctors();
    const totalFiltered = filtered.length;
    const totalPages = Math.ceil(totalFiltered / rowsPerPage);
    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
    if (currentPage < 1) currentPage = 1;
    const start = (currentPage - 1) * rowsPerPage;
    const paginated = filtered.slice(start, start + rowsPerPage);
    
    const tbody = document.getElementById("doctorTableBody");
    tbody.innerHTML = "";
    if (paginated.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center py-12 text-gray-400"><i class="fas fa-user-md-slash text-3xl mb-2 block"></i>Aucun médecin trouvé</td><tr>`;
    } else {
        paginated.forEach(d => {
            const statusClass = getStatusBadgeClass(d.status);
            const row = `
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono text-xs font-semibold text-gray-600">${d.id}</td>
                    <td class="px-4 py-3 font-medium">${escapeHtml(d.firstName)} ${escapeHtml(d.lastName)}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-lg text-xs font-medium bg-secondary/10 text-secondary">${escapeHtml(d.specialty)}</span></td>
                    <td class="px-4 py-3 text-sm">${escapeHtml(d.phone || "—")}</td>
                    <td class="px-4 py-3 text-sm">${escapeHtml(d.email)}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-semibold ${statusClass}">${getStatusLabel(d.status)}</span></td>
                    <td class="px-4 py-3 text-center"><span class="bg-gray-100 px-2 py-1 rounded-full text-xs">${d.patientCount} patients</span></td>
                    <td class="px-4 py-3 text-center space-x-2">
                        <button onclick="viewDoctor('${d.id}')" class="text-gray-500 hover:text-secondary p-1" title="Voir détails"><i class="fas fa-eye"></i></button>
                        <button onclick="editDoctor('${d.id}')" class="text-blue-600 hover:text-blue-800 p-1" title="Modifier"><i class="far fa-edit"></i></button>
                        <button onclick="deleteDoctor('${d.id}')" class="text-red-500 hover:text-red-700 p-1" title="Supprimer"><i class="far fa-trash-alt"></i></button>
                     </td>
                  </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }
    document.getElementById("paginationInfo").innerHTML = `${totalFiltered ? start+1 : 0} - ${Math.min(start+rowsPerPage, totalFiltered)} sur ${totalFiltered} médecins`;
    document.getElementById("prevPageBtn").disabled = currentPage === 1 || totalFiltered === 0;
    document.getElementById("nextPageBtn").disabled = currentPage === totalPages || totalFiltered === 0;
    updateStats();
}

function escapeHtml(str) { if(!str) return ''; return str.replace(/[&<>]/g, function(m){if(m==='&') return '&amp;'; if(m==='<') return '&lt;'; if(m==='>') return '&gt;'; return m;}); }

// ==================== ACTIONS CRUD ====================

// Voir détails médecin
window.viewDoctor = (id) => {
    const doctor = doctors.find(d => d.id === id);
    if (!doctor) return;
    
    const content = `
        <div class="space-y-4">
            <div class="flex justify-center">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary to-blue-700 flex items-center justify-center text-white text-3xl font-bold">
                    ${doctor.firstName.charAt(0)}${doctor.lastName.charAt(0)}
                </div>
            </div>
            <div class="text-center">
                <h4 class="text-xl font-bold text-gray-800">${doctor.firstName} ${doctor.lastName}</h4>
                <p class="text-secondary font-medium">${doctor.specialty}</p>
            </div>
            <div class="border-t border-gray-100 pt-4 space-y-2">
                <div class="flex justify-between"><span class="text-gray-500 text-sm">Matricule:</span><span class="font-mono">${doctor.id}</span></div>
                <div class="flex justify-between"><span class="text-gray-500 text-sm">Téléphone:</span><span>${doctor.phone || "Non renseigné"}</span></div>
                <div class="flex justify-between"><span class="text-gray-500 text-sm">Email:</span><span>${doctor.email}</span></div>
                <div class="flex justify-between"><span class="text-gray-500 text-sm">Statut:</span><span class="px-2 py-0.5 rounded-full text-xs ${getStatusBadgeClass(doctor.status)}">${getStatusLabel(doctor.status)}</span></div>
                <div class="flex justify-between"><span class="text-gray-500 text-sm">Patients suivis:</span><span class="font-semibold">${doctor.patientCount}</span></div>
            </div>
        </div>
    `;
    document.getElementById("viewDoctorContent").innerHTML = content;
    document.getElementById("viewDoctorModal").classList.remove("hidden");
};

// Éditer médecin
window.editDoctor = (id) => {
    const doctor = doctors.find(d => d.id === id);
    if (doctor) {
        document.getElementById("modalTitle").innerText = "Modifier le médecin";
        document.getElementById("editId").value = doctor.id;
        document.getElementById("lastName").value = doctor.lastName;
        document.getElementById("firstName").value = doctor.firstName;
        document.getElementById("specialty").value = doctor.specialty;
        document.getElementById("phone").value = doctor.phone || "";
        document.getElementById("email").value = doctor.email;
        document.getElementById("status").value = doctor.status;
        document.getElementById("doctorModal").classList.remove("hidden");
    }
};

// Supprimer médecin
window.deleteDoctor = (id) => {
    // Vérifier si le médecin a des patients assignés
    const doctor = doctors.find(d => d.id === id);
    if (doctor && doctor.patientCount > 0) {
        if (!confirm(`⚠️ Ce médecin suit actuellement ${doctor.patientCount} patient(s).\nLa suppression entraînera la dissociation de ces patients.\nConfirmer la suppression ?`)) {
            return;
        }
    } else {
        if (!confirm(`⚠️ Supprimer définitivement ${doctor.firstName} ${doctor.lastName} ?`)) {
            return;
        }
    }
    
    doctors = doctors.filter(d => d.id !== id);
    populateSpecialtyFilter();
    if (getFilteredDoctors().length === 0 && currentPage > 1) currentPage--;
    renderTable();
};

// ==================== FORMULAIRE AJOUT / MODIFICATION ====================
document.getElementById("doctorForm").addEventListener("submit", async(e) => {
    e.preventDefault();
    const editId = document.getElementById("editId").value;
    const lastName = document.getElementById("lastName").value.trim();
    const firstName = document.getElementById("firstName").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    
    if (!lastName || !firstName) return alert("Nom et prénom obligatoires");
    if (!email) return alert("Email obligatoire");
    if (!email.includes("@")) return alert("Email invalide");
    if (!password) return alert("Mot de passe obligatoire");
    if (password !== confirmPassword) return alert("Les mots de passe ne correspondent pas");
    
    const formData = {
        lastName, firstName,
        specialty: document.getElementById("specialty").value,
        phone: document.getElementById("phone").value,
        email: email,
        mdp: password,
        status: document.getElementById("status").value,
        patientCount: parseInt(document.getElementById("patientCount").value) || 0,
    };
    
    if (editId) {
        document.getElementById('password').style.display = 'none';
        document.getElementById('confirmPassword').style.display = 'none';
        
        const res = await axios.put(`/update_doctor/${editId}`, { ...formData }).catch(err => {
            alert("Erreur lors de la modification du médecin : " + (err.response?.data?.message || err.message));
        });
        if (res.data.status === 'success'){
            alert(`✅ Médecin ${firstName} ${lastName} modifié avec succès`);
            const index = doctors.findIndex(d => d.id === editId);
            if (index !== -1) {
                doctors[index] = { id: editId, ...formData };
            }
        }else{
            alert("Erreur lors de la modification du médecin : " + (res.data.message || "Erreur inconnue"));
        }

    } else {
        // const newId = generateDoctorId();
        console.log("Form data validée, envoi au serveur...");
        const res = await axios.post("/add_doctor", { ...formData }).catch(err => {
            alert("Erreur lors de l'ajout du médecin : " + (err.response?.data?.message || err.message));
        });
        if (res.data.status === 'success'){
            alert(`✅ Médecin ${firstName} ${lastName} ajouté avec succès (Matricule: ${newId})`);
        }else{
            alert("Erreur lors de l'ajout du médecin : " + (res.data.message || "Erreur inconnue"));
        }
        // doctors.push({ id: newId, ...formData });
        
    }
    closeModal();
    populateSpecialtyFilter();
    renderTable();
});

function closeModal() {
    document.getElementById("doctorModal").classList.add("hidden");
    document.getElementById("doctorForm").reset();
    document.getElementById("editId").value = "";
    document.getElementById("modalTitle").innerText = "Ajouter un médecin";
}

// ==================== GESTION DES MODALS ====================
document.getElementById("openAddModalBtn").onclick = () => {
    document.getElementById("editId").value = "";
    document.getElementById("doctorForm").reset();
    document.getElementById("modalTitle").innerText = "Ajouter un médecin";
    document.getElementById("doctorModal").classList.remove("hidden");
};
document.getElementById("closeModalBtn").onclick = closeModal;
document.getElementById("cancelModalBtn").onclick = closeModal;
document.getElementById("closeViewModalBtn").onclick = () => {
    document.getElementById("viewDoctorModal").classList.add("hidden");
};

// ==================== FILTRES ET RECHERCHE ====================
function updateFiltersAndSearch() {
    currentSearch = document.getElementById("searchInput").value;
    currentSpecialtyFilter = document.getElementById("specialtyFilterSelect").value;
    currentStatusFilter = document.getElementById("statusFilterSelect").value;
    currentPage = 1;
    renderTable();
}

document.getElementById("searchInput").addEventListener("input", updateFiltersAndSearch);
document.getElementById("specialtyFilterSelect").addEventListener("change", updateFiltersAndSearch);
document.getElementById("statusFilterSelect").addEventListener("change", updateFiltersAndSearch);
document.getElementById("resetFiltersBtn").addEventListener("click", () => {
    document.getElementById("searchInput").value = "";
    document.getElementById("specialtyFilterSelect").value = "all";
    document.getElementById("statusFilterSelect").value = "all";
    updateFiltersAndSearch();
});

// ==================== PAGINATION ====================
document.getElementById("prevPageBtn").onclick = () => { if (currentPage > 1) { currentPage--; renderTable(); } };
document.getElementById("nextPageBtn").onclick = () => { 
    const max = Math.ceil(getFilteredDoctors().length / rowsPerPage); 
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
populateSpecialtyFilter();
renderTable();
</script>

<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
?>