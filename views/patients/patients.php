<?php
$title = "Patients | Gestion des patients";
ob_start();
?>

<!-- Statistiques (cards) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-primary">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">Total patients</p><p id="totalPatientsCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-users text-3xl text-primary/30"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-emerald-500">
        <div class="flex justify-between items-center"><div><p class="text-gray-500 text-sm">Admis aujourd'hui</p><p id="admittedTodayCount" class="text-2xl font-bold text-gray-800">0</p></div><i class="fas fa-calendar-day text-3xl text-emerald-500/30"></i></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-red-500">
        <div class="flex justify-between items-center"><div><p class="text-gray-500 text-sm">Urgences</p><p id="urgentCount" class="text-2xl font-bold text-gray-800">0</p></div><i class="fas fa-ambulance text-3xl text-red-500/30"></i></div>
    </div>
</div>

<!-- Section Filtres + Ajout -->
<div class="flex flex-col sm:flex-row justify-between items-center gap-3">
    <div class="flex gap-2 flex-wrap">
        <select id="statusFilterSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="all">Tous statuts</option>
            <option value="hospitalisé">Hospitalisé</option>
            <option value="en consultation">En consultation</option>
            <option value="sorti">Sorti</option>
            <option value="urgences">Urgences</option>
        </select>
        <select id="doctorFilterSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="all">Tous les médecins</option>
        </select>
        <button id="resetFiltersBtn" class="text-gray-500 hover:text-primary text-sm bg-gray-100 px-3 rounded-xl"><i class="fas fa-undo-alt mr-1"></i> Réinitialiser</button>
    </div>
    <button id="openAddModalBtn" class="bg-primary hover:bg-blue-800 text-white px-5 py-2 rounded-xl shadow-md transition flex items-center gap-2"><i class="fas fa-plus"></i> Ajouter patient</button>
</div>

<!-- Tableau des patients avec médecin assigné -->
<div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
    <div class="overflow-x-auto custom-scroll">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3">Nom</th>
                    <th class="px-4 py-3">Prénom</th>
                    <th class="px-4 py-3">Âge</th>
                    <th class="px-4 py-3">Sexe</th>
                    <th class="px-4 py-3">Dossier</th>
                    <th class="px-4 py-3">Médecin traitant</th>
                    <th class="px-4 py-3">Spécialité</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="patientTableBody" class="divide-y divide-gray-100 text-sm"></tbody>
        </table>
    </div>
    <!-- Pagination simple -->
    <div class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200 flex-wrap gap-2">
        <div class="text-xs text-gray-500"><span id="paginationInfo"></span></div>
        <div class="flex gap-2">
            <button id="prevPageBtn" class="px-3 py-1 border rounded-lg text-gray-600 hover:bg-gray-100 disabled:opacity-40"><i class="fas fa-chevron-left"></i> Précédent</button>
            <button id="nextPageBtn" class="px-3 py-1 border rounded-lg text-gray-600 hover:bg-gray-100 disabled:opacity-40">Suivant <i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</div>

<!-- MODAL AJOUT / MODIFICATION PATIENT -->
<div id="patientModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-200">
    <div class="bg-white rounded-2xl max-w-lg w-full mx-4 shadow-2xl overflow-hidden transform transition-all overflow-y-auto max-h-[90vh]">
        <div class="bg-primary px-6 py-4 flex justify-between items-center">
            <h3 id="modalTitle" class="text-white font-bold text-xl">Ajouter un patient</h3>
            <button id="closeModalBtn" class="text-white/80 hover:text-white text-2xl">&times;</button>
        </div>
        <form id="patientForm" class="p-6 space-y-4">
            <input type="hidden" id="editId" value="">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold">Nom *</label>
                    <input type="text" name="nom" id="lastName" required class="w-full border rounded-xl px-3 py-2 mt-1 focus:ring-primary/30">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold">Prénom *</label>
                    <input type="text" name="prenom" id="firstName" required class="w-full border rounded-xl px-3 py-2 mt-1">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold">Date naissance</label>
                    <input type="date" id="birthDate" name="naissance" class="w-full border rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold">Sexe</label>
                    <select id="gender" name="sexe" class="w-full border rounded-xl px-3 py-2">
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold">Téléphone</label>
                <input type="tel" id="phone" name="tel" class="w-full border rounded-xl px-3 py-2">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold">Adresse</label>
                <input type="text" name="adresse" id="address" class="w-full border rounded-xl px-3 py-2">
            </div>
            
            <!-- Assignation médecin -->
            <div>
                <label class="block text-gray-700 text-sm font-semibold">Médecin traitant</label>
                <select id="assignedDoctor" name="medecin" class="w-full border rounded-xl px-3 py-2">
                    <option value="">Non assigné</option>
                </select>
            </div>
            
            <div><label class="block text-gray-700 text-sm font-semibold">Statut *</label>
                <select id="status" name="statut" required class="w-full border rounded-xl px-3 py-2">
                    <option value="hospitalisé">Hospitalisé</option>
                    <option value="en consultation">En consultation</option>
                    <option value="sorti">Sorti</option>
                    <option value="urgences">Urgences</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" id="cancelModalBtn" class="px-4 py-2 border rounded-xl text-gray-600">Annuler</button>
                <button type="submit" class="px-5 py-2 bg-primary text-white rounded-xl shadow hover:bg-blue-900">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL ASSIGNER MÉDECIN (popup rapide) -->
<div id="assignDoctorModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-200">
    <div class="bg-white rounded-2xl max-w-md w-full mx-4 shadow-2xl overflow-hidden">
        <div class="bg-secondary px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold text-xl">Assigner un médecin</h3>
            <button id="closeAssignModalBtn" class="text-white/80 hover:text-white text-2xl">&times;</button>
        </div>
        <form id="assignDoctorForm" class="p-6 space-y-4">
            <input type="hidden" id="assignPatientId" value="">
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-1">Patient</label>
                <p id="assignPatientName" class="text-gray-800 font-medium bg-gray-50 p-2 rounded-lg"></p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-1">Médecin traitant *</label>
                <select id="assignDoctorSelect" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5">
                    <option value="">Sélectionner un médecin</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" id="cancelAssignBtn" class="px-4 py-2 border rounded-xl text-gray-600">Annuler</button>
                <button type="submit" class="px-5 py-2 bg-secondary text-white rounded-xl shadow hover:bg-orange-600">Assigner</button>
            </div>
        </form>
    </div>
</div>

<script>
// ==================== LISTE DES MÉDECINS DISPONIBLES ====================
const doctorsList = [
    { id: "M1001", name: "Dr. Sophie Martin", specialty: "Cardiologie" },
    { id: "M1002", name: "Dr. Thomas Bernard", specialty: "Neurologie" },
    { id: "M1003", name: "Dr. Julie Petit", specialty: "Pédiatrie" },
    { id: "M1004", name: "Dr. Nicolas Robert", specialty: "Orthopédie" },
    { id: "M1005", name: "Dr. Camille Durand", specialty: "Gynécologie" },
    { id: "M1006", name: "Dr. Antoine Moreau", specialty: "Médecine générale" },
    { id: "M1007", name: "Dr. Laure Simon", specialty: "Dermatologie" },
    { id: "M1008", name: "Dr. Pierre Michel", specialty: "Psychiatrie" }
];

// ==================== DONNÉES INITIALES PATIENTS AVEC MÉDECIN ASSIGNÉ ====================
let patients = [
    { id: "P1001", lastName: "Dubois", firstName: "Sophie", birthDate: "1985-03-12", gender: "F", phone: "0612345678", address: "12 rue des Lilas, Paris", diagnosis: "Hypertension artérielle", status: "hospitalisé", doctorId: "M1001", doctorName: "Dr. Sophie Martin", doctorSpecialty: "Cardiologie" },
    { id: "P1002", lastName: "Martin", firstName: "Lucas", birthDate: "1992-07-22", gender: "M", phone: "0698765432", address: "8 bd Voltaire, Lyon", diagnosis: "Fracture du tibia", status: "hospitalisé", doctorId: "M1004", doctorName: "Dr. Nicolas Robert", doctorSpecialty: "Orthopédie" },
    { id: "P1003", lastName: "Petit", firstName: "Emma", birthDate: "2010-11-05", gender: "F", phone: "0745123987", address: "3 allée des Jades, Marseille", diagnosis: "Gastro-entérite", status: "en consultation", doctorId: "M1003", doctorName: "Dr. Julie Petit", doctorSpecialty: "Pédiatrie" },
    { id: "P1004", lastName: "Bernard", firstName: "Thomas", birthDate: "1978-09-30", gender: "M", phone: "0623456712", address: "21 rue Victor Hugo, Bordeaux", diagnosis: "Douleurs thoraciques", status: "urgences", doctorId: "M1001", doctorName: "Dr. Sophie Martin", doctorSpecialty: "Cardiologie" },
    { id: "P1005", lastName: "Roux", firstName: "Julie", birthDate: "2001-01-17", gender: "F", phone: "0789456123", address: "15 impasse des Roses, Lille", diagnosis: "Migraine chronique", status: "sorti", doctorId: "", doctorName: "", doctorSpecialty: "" },
    { id: "P1006", lastName: "Leroy", firstName: "Nicolas", birthDate: "1980-04-25", gender: "M", phone: "0611223344", address: "9 place centrale, Nantes", diagnosis: "Diabète type 2", status: "en consultation", doctorId: "M1006", doctorName: "Dr. Antoine Moreau", doctorSpecialty: "Médecine générale" },
    { id: "P1007", lastName: "Moreau", firstName: "Camille", birthDate: "1995-12-10", gender: "F", phone: "0655667788", address: "17 rue des Écoles, Toulouse", diagnosis: "Anxiété généralisée", status: "en consultation", doctorId: "M1008", doctorName: "Dr. Pierre Michel", doctorSpecialty: "Psychiatrie" },
    { id: "P1008", lastName: "Simon", firstName: "Antoine", birthDate: "1968-06-14", gender: "M", phone: "0788990011", address: "45 avenue de la République, Nice", diagnosis: "Insuffisance cardiaque", status: "hospitalisé", doctorId: "M1001", doctorName: "Dr. Sophie Martin", doctorSpecialty: "Cardiologie" }
];

// Helper: calcul âge
function calculateAge(birthDateStr) {
    if (!birthDateStr) return "—";
    const birth = new Date(birthDateStr);
    const diff = Date.now() - birth.getTime();
    const ageDate = new Date(diff);
    return Math.abs(ageDate.getUTCFullYear() - 1970);
}

// Générer ID patient unique
function generatePatientId() {
    let maxId = 1000;
    patients.forEach(p => { let num = parseInt(p.id.substring(1)); if(num > maxId) maxId = num; });
    return "P" + (maxId + 1);
}

// Variables d'état UI
let currentSearch = "";
let currentStatusFilter = "all";
let currentDoctorFilter = "all";
let currentPage = 1;
const rowsPerPage = 5;

// Peupler les listes déroulantes de médecins
function populateDoctorSelects() {
    const doctorSelects = ["assignedDoctor", "assignDoctorSelect", "doctorFilterSelect"];
    doctorSelects.forEach(selectId => {
        const select = document.getElementById(selectId);
        if(select && selectId !== "doctorFilterSelect") {
            const currentValue = select.value;
            select.innerHTML = '<option value="">Non assigné</option>';
            doctorsList.forEach(doc => {
                select.innerHTML += `<option value="${doc.id}" data-specialty="${doc.specialty}">${doc.name} (${doc.specialty})</option>`;
            });
            if(currentValue) select.value = currentValue;
        } else if(select && selectId === "doctorFilterSelect") {
            select.innerHTML = '<option value="all">Tous les médecins</option>';
            doctorsList.forEach(doc => {
                select.innerHTML += `<option value="${doc.id}">${doc.name}</option>`;
            });
        }
    });
}

// Fonction pour obtenir les patients filtrés
function getFilteredPatients() {
    let filtered = patients.filter(p => {
        const searchTerm = currentSearch.toLowerCase();
        const matchesSearch = p.lastName.toLowerCase().includes(searchTerm) || 
                            p.firstName.toLowerCase().includes(searchTerm) || 
                            p.id.toLowerCase().includes(searchTerm);
        const matchesStatus = (currentStatusFilter === "all") || (p.status === currentStatusFilter);
        const matchesDoctor = (currentDoctorFilter === "all") || (p.doctorId === currentDoctorFilter);
        return matchesSearch && matchesStatus && matchesDoctor;
    });
    return filtered;
}

// Mise à jour des cartes statistiques
function updateStats() {
    const total = patients.length;
    const urgentCount = patients.filter(p => p.status === "urgences").length;
    const appointmentsToday = patients.filter(p => p.status === "en consultation").length;
    const admitted = patients.filter(p => p.status === "hospitalisé").length;
    document.getElementById("totalPatientsCount").innerText = total;
    document.getElementById("admittedTodayCount").innerText = admitted;
    document.getElementById("urgentCount").innerText = urgentCount;
    document.getElementById("appointmentsCount").innerText = appointmentsToday;
}

// Rendu du tableau
function renderTable() {
    const filtered = getFilteredPatients();
    const totalFiltered = filtered.length;
    const totalPages = Math.ceil(totalFiltered / rowsPerPage);
    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
    if (currentPage < 1) currentPage = 1;
    const start = (currentPage - 1) * rowsPerPage;
    const paginated = filtered.slice(start, start + rowsPerPage);
    
    const tbody = document.getElementById("patientTableBody");
    tbody.innerHTML = "";
    if(paginated.length === 0) {
        tbody.innerHTML = `<tr><td colspan="9" class="text-center py-8 text-gray-400">Aucun patient trouvé</td></tr>`;
    } else {
        paginated.forEach(p => {
            const age = calculateAge(p.birthDate);
            let statusClass = "";
            if(p.status === "hospitalisé") statusClass = "bg-blue-100 text-blue-700";
            else if(p.status === "en consultation") statusClass = "bg-amber-100 text-amber-700";
            else if(p.status === "sorti") statusClass = "bg-green-100 text-green-700";
            else if(p.status === "urgences") statusClass = "bg-red-100 text-red-700";
            else statusClass = "bg-gray-100 text-gray-700";
            
            const doctorDisplay = p.doctorName ? `${p.doctorName}<br><span class="text-xs text-gray-400">${p.doctorSpecialty || ""}</span>` : '<span class="text-gray-400 italic">Non assigné</span>';
            
            const row = `<tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 font-medium">${escapeHtml(p.lastName)}</td>
                <td class="px-4 py-3">${escapeHtml(p.firstName)}</td>
                <td class="px-4 py-3">${age}</td>
                <td class="px-4 py-3">${p.gender === "M" ? "Masculin" : "Féminin"}</td>
                <td class="px-4 py-3 font-mono text-xs">${p.id}</td>
                <td class="px-4 py-3 text-sm">${doctorDisplay}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded-lg text-xs bg-secondary/10 text-secondary">${p.doctorSpecialty || "—"}</span></td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-semibold ${statusClass}">${p.status}</span></td>
                <td class="px-4 py-3 text-center space-x-2">
                    <button onclick="editPatient('${p.id}')" class="text-blue-600 hover:text-blue-800" title="Modifier"><i class="far fa-edit"></i></button>
                    <button onclick="openAssignDoctorModal('${p.id}')" class="text-secondary hover:text-orange-600" title="Assigner un médecin"><i class="fas fa-user-md"></i></button>
                    <button onclick="deletePatient('${p.id}')" class="text-red-500 hover:text-red-700" title="Supprimer"><i class="far fa-trash-alt"></i></button>
                    <button onclick="viewPatient('${p.id}')" class="text-gray-500 hover:text-gray-700" title="Voir détails"><i class="fas fa-eye"></i></button>
                </td>
            </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }
    document.getElementById("paginationInfo").innerText = `${start+1} - ${Math.min(start+rowsPerPage, totalFiltered)} sur ${totalFiltered} patients`;
    document.getElementById("prevPageBtn").disabled = currentPage === 1;
    document.getElementById("nextPageBtn").disabled = currentPage === totalPages || totalFiltered === 0;
    updateStats();
}

function escapeHtml(str) { if(!str) return ''; return str.replace(/[&<>]/g, function(m){if(m==='&') return '&amp;'; if(m==='<') return '&lt;'; if(m==='>') return '&gt;'; return m;}); }

// Éditer un patient
window.editPatient = (id) => {
    const patient = patients.find(p => p.id === id);
    if(patient) {
        document.getElementById("modalTitle").innerText = "Modifier le patient";
        document.getElementById("editId").value = patient.id;
        document.getElementById("lastName").value = patient.lastName;
        document.getElementById("firstName").value = patient.firstName;
        document.getElementById("birthDate").value = patient.birthDate || "";
        document.getElementById("gender").value = patient.gender;
        document.getElementById("phone").value = patient.phone || "";
        document.getElementById("address").value = patient.address || "";
        document.getElementById("assignedDoctor").value = patient.doctorId || "";
        document.getElementById("status").value = patient.status;
        document.getElementById("patientModal").classList.remove("hidden");
    }
};

// Supprimer un patient
window.deletePatient = (id) => {
    if(confirm("⚠️ Supprimer ce patient définitivement ? Cette action est irréversible.")){
        patients = patients.filter(p => p.id !== id);
        if(getFilteredPatients().length === 0 && currentPage > 1) currentPage--;
        renderTable();
    }
};

// Voir détails patient
window.viewPatient = (id) => {
    const p = patients.find(pat => pat.id === id);
    alert(`👤 ${p.firstName} ${p.lastName}\n📂 Dossier: ${p.id}\n🩺 Diagnostic: ${p.diagnosis || "Non renseigné"}\n📞 Tél: ${p.phone || "—"}\n👨‍⚕️ Médecin: ${p.doctorName || "Non assigné"}\n🏥 Statut: ${p.status}`);
};

// Ouvrir modal d'assignation de médecin
window.openAssignDoctorModal = (id) => {
    const patient = patients.find(p => p.id === id);
    if(patient) {
        document.getElementById("assignPatientId").value = patient.id;
        document.getElementById("assignPatientName").innerHTML = `${patient.firstName} ${patient.lastName} <span class="text-gray-400 text-xs">(Dossier: ${patient.id})</span>`;
        document.getElementById("assignDoctorSelect").value = patient.doctorId || "";
        document.getElementById("assignDoctorModal").classList.remove("hidden");
    }
};

// Sauvegarde formulaire patient (ajout/modif)
document.getElementById("patientForm").addEventListener("submit", (e) => {
    e.preventDefault();
    const editId = document.getElementById("editId").value;
    const lastName = document.getElementById("lastName").value.trim();
    const firstName = document.getElementById("firstName").value.trim();
    if(!lastName || !firstName) return alert("Nom et prénom requis");
    
    const doctorId = document.getElementById("assignedDoctor").value;
    const selectedDoctor = doctorsList.find(d => d.id === doctorId);
    
    const formData = {
        lastName, firstName,
        birthDate: document.getElementById("birthDate").value,
        gender: document.getElementById("gender").value,
        phone: document.getElementById("phone").value,
        address: document.getElementById("address").value,
        diagnosis: document.getElementById("diagnosis").value,
        status: document.getElementById("status").value,
        doctorId: doctorId || "",
        doctorName: selectedDoctor ? selectedDoctor.name : "",
        doctorSpecialty: selectedDoctor ? selectedDoctor.specialty : ""
    };
    
    if(editId) {
        const index = patients.findIndex(p => p.id === editId);
        if(index !== -1) patients[index] = { ...patients[index], ...formData, id: editId };
    } else {
        const newId = generatePatientId();
        patients.push({ id: newId, ...formData });
    }
    closeModal();
    renderTable();
});

// Assignation rapide d'un médecin
document.getElementById("assignDoctorForm").addEventListener("submit", (e) => {
    e.preventDefault();
    const patientId = document.getElementById("assignPatientId").value;
    const doctorId = document.getElementById("assignDoctorSelect").value;
    const selectedDoctor = doctorsList.find(d => d.id === doctorId);
    
    const patient = patients.find(p => p.id === patientId);
    if(patient) {
        patient.doctorId = doctorId || "";
        patient.doctorName = selectedDoctor ? selectedDoctor.name : "";
        patient.doctorSpecialty = selectedDoctor ? selectedDoctor.specialty : "";
        renderTable();
        alert(`✅ Patient ${patient.firstName} ${patient.lastName} assigné à ${selectedDoctor ? selectedDoctor.name : "aucun médecin"}`);
    }
    closeAssignModal();
});

function closeModal() {
    document.getElementById("patientModal").classList.add("hidden");
    document.getElementById("patientForm").reset();
    document.getElementById("editId").value = "";
    document.getElementById("modalTitle").innerText = "Ajouter un patient";
}

function closeAssignModal() {
    document.getElementById("assignDoctorModal").classList.add("hidden");
    document.getElementById("assignPatientId").value = "";
    document.getElementById("assignDoctorSelect").value = "";
}

// Event listeners modals
document.getElementById("openAddModalBtn").onclick = () => { 
    document.getElementById("editId").value = ""; 
    document.getElementById("modalTitle").innerText = "Ajouter un patient"; 
    document.getElementById("patientForm").reset(); 
    document.getElementById("patientModal").classList.remove("hidden"); 
};
document.getElementById("closeModalBtn").onclick = closeModal;
document.getElementById("cancelModalBtn").onclick = closeModal;
document.getElementById("closeAssignModalBtn").onclick = closeAssignModal;
document.getElementById("cancelAssignBtn").onclick = closeAssignModal;

// Filtres & recherche
function updateFilters(){
    currentSearch = document.getElementById("globalSearchInput")?.value || "";
    const mobileSearch = document.getElementById("mobileSearchInput")?.value || "";
    if(mobileSearch) currentSearch = mobileSearch;
    currentStatusFilter = document.getElementById("statusFilterSelect").value;
    currentDoctorFilter = document.getElementById("doctorFilterSelect").value;
    currentPage = 1;
    renderTable();
}

if(document.getElementById("globalSearchInput")) 
    document.getElementById("globalSearchInput").addEventListener("input", updateFilters);
if(document.getElementById("mobileSearchInput")) 
    document.getElementById("mobileSearchInput").addEventListener("input", updateFilters);
document.getElementById("statusFilterSelect").addEventListener("change", updateFilters);
document.getElementById("doctorFilterSelect").addEventListener("change", updateFilters);
document.getElementById("resetFiltersBtn").addEventListener("click", () => {
    if(document.getElementById("globalSearchInput")) document.getElementById("globalSearchInput").value = "";
    if(document.getElementById("mobileSearchInput")) document.getElementById("mobileSearchInput").value = "";
    document.getElementById("statusFilterSelect").value = "all";
    document.getElementById("doctorFilterSelect").value = "all";
    updateFilters();
});

document.getElementById("prevPageBtn").onclick = () => { if(currentPage>1){currentPage--; renderTable();} };
document.getElementById("nextPageBtn").onclick = () => { const max = Math.ceil(getFilteredPatients().length/rowsPerPage); if(currentPage<max){currentPage++; renderTable();} };

// Sidebar mobile
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("mobileSidebarOverlay");
if(document.getElementById("mobileMenuBtn")) {
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
if(overlay) overlay.addEventListener("click", closeSidebar);

// Initialisation
populateDoctorSelects();
renderTable();
</script>

<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
?>