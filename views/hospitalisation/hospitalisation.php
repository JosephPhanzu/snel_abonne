<?php
$title = "Hospitalisations | Gestion des séjours";
ob_start();
?>

<!-- En-tête -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Gestion des hospitalisations</h1>
        <p class="text-gray-500 text-sm">Suivez les séjours, lits occupés et dossiers d'hospitalisation</p>
    </div>
    <button id="openAddModalBtn" class="bg-primary hover:bg-blue-800 text-white px-5 py-2.5 rounded-xl shadow-md transition flex items-center gap-2 self-start">
        <i class="fas fa-plus-circle"></i> Nouvelle hospitalisation
    </button>
</div>

<!-- Cartes statistiques hospitalisations -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-primary">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">Hospitalisations</p><p id="totalHospitalizations" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-procedures text-3xl text-primary/30"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-emerald-500">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">Lits occupés</p><p id="occupiedBeds" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-bed text-3xl text-emerald-500/30"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-secondary">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">En soins intensifs</p><p id="intensiveCare" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-heartbeat text-3xl text-secondary/30"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-amber-500">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">Sorties (7j)</p><p id="dischargedWeek" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-home text-3xl text-amber-500/30"></i>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="flex flex-col sm:flex-row justify-between gap-4">
    <div class="flex flex-wrap gap-3">
        <select id="serviceFilterSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white focus:ring-2 focus:ring-primary/30">
            <option value="all">Tous les services</option>
            <option value="Cardiologie">Cardiologie</option>
            <option value="Neurologie">Neurologie</option>
            <option value="Pédiatrie">Pédiatrie</option>
            <option value="Orthopédie">Orthopédie</option>
            <option value="Urgences">Urgences</option>
        </select>
        <select id="statusFilterSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white focus:ring-2 focus:ring-primary/30">
            <option value="all">Tous statuts</option>
            <option value="en cours">En cours</option>
            <option value="terminé">Terminé</option>
        </select>
        <button id="resetFiltersBtn" class="text-gray-600 hover:text-primary bg-gray-100 px-4 rounded-xl text-sm transition"><i class="fas fa-undo-alt mr-1"></i> Réinitialiser</button>
    </div>
</div>

<!-- Tableau hospitalisations -->
<div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
    <div class="overflow-x-auto custom-scroll">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3">ID Séjour</th>
                    <th class="px-4 py-3">Patient</th>
                    <th class="px-4 py-3">Service</th>
                    <th class="px-4 py-3">Lit</th>
                    <th class="px-4 py-3">Admission</th>
                    <th class="px-4 py-3">Sortie prévue</th>
                    <th class="px-4 py-3">Médecin référent</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                 </tr>
            </thead>
            <tbody id="hospitalizationTableBody" class="divide-y divide-gray-100"></tbody>
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

<!-- MODAL AJOUT / MODIFICATION HOSPITALISATION -->
<div id="hospitalizationModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-200">
    <div class="bg-white rounded-2xl max-w-lg w-full mx-4 shadow-2xl overflow-hidden">
        <div class="bg-primary px-6 py-4 flex justify-between items-center">
            <h3 id="modalTitle" class="text-white font-bold text-xl">Ajouter une hospitalisation</h3>
            <button id="closeModalBtn" class="text-white/80 hover:text-white text-2xl">&times;</button>
        </div>
        <form id="hospitalizationForm" class="p-6 space-y-4">
            <input type="hidden" id="editId" value="">
            <div>
                <label class="block text-gray-700 text-sm font-semibold">Patient *</label>
                <select id="patientId" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5">
                    <option value="">Sélectionner un patient</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold">Service *</label>
                <select id="service" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5">
                    <option value="Cardiologie">Cardiologie</option>
                    <option value="Neurologie">Neurologie</option>
                    <option value="Pédiatrie">Pédiatrie</option>
                    <option value="Orthopédie">Orthopédie</option>
                    <option value="Urgences">Urgences</option>
                </select>
            </div>
            <div><label class="block text-gray-700 text-sm font-semibold">Numéro de lit</label><input type="text" id="bedNumber" class="w-full border border-gray-200 rounded-xl px-3 py-2.5" placeholder="ex: A-12"></div>
            <div><label class="block text-gray-700 text-sm font-semibold">Médecin référent</label><input type="text" id="referringDoctor" class="w-full border border-gray-200 rounded-xl px-3 py-2.5" placeholder="Dr. Nom Prénom"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-gray-700 text-sm font-semibold">Date admission *</label><input type="date" id="admissionDate" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5"></div>
                <div><label class="block text-gray-700 text-sm font-semibold">Sortie prévue</label><input type="date" id="expectedDischarge" class="w-full border border-gray-200 rounded-xl px-3 py-2.5"></div>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold">Statut</label>
                <select id="status" class="w-full border border-gray-200 rounded-xl px-3 py-2.5">
                    <option value="en cours">En cours</option>
                    <option value="terminé">Terminé</option>
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
        { id: "P1003", name: "Emma Petit" }, { id: "P1004", name: "Thomas Bernard" }
    ];

    let hospitalizations = [
        { id: "H001", patientId: "P1001", patientName: "Sophie Dubois", service: "Cardiologie", bedNumber: "A-12", admissionDate: "2025-04-20", expectedDischarge: "2025-04-27", referringDoctor: "Dr. Sophie Martin", status: "en cours" },
        { id: "H002", patientId: "P1002", patientName: "Lucas Martin", service: "Neurologie", bedNumber: "B-05", admissionDate: "2025-04-21", expectedDischarge: "2025-04-28", referringDoctor: "Dr. Thomas Bernard", status: "en cours" },
        { id: "H003", patientId: "P1004", patientName: "Thomas Bernard", service: "Urgences", bedNumber: "U-03", admissionDate: "2025-04-25", expectedDischarge: "2025-04-26", referringDoctor: "Dr. Pierre Dubois", status: "en cours" },
        { id: "H004", patientId: "P1003", patientName: "Emma Petit", service: "Pédiatrie", bedNumber: "P-08", admissionDate: "2025-04-18", expectedDischarge: "2025-04-24", referringDoctor: "Dr. Julie Petit", status: "terminé" }
    ];

    function generateHospitalizationId() {
        let maxNum = 0;
        hospitalizations.forEach(h => { let num = parseInt(h.id.substring(1)); if (num > maxNum) maxNum = num; });
        return "H" + String(maxNum + 1).padStart(3, '0');
    }

    let currentServiceFilter = "all";
    let currentStatusFilter = "all";
    let currentPage = 1;
    const rowsPerPage = 5;

    function getFilteredHospitalizations() {
        return hospitalizations.filter(h => {
            const matchService = (currentServiceFilter === "all") || (h.service === currentServiceFilter);
            const matchStatus = (currentStatusFilter === "all") || (h.status === currentStatusFilter);
            return matchService && matchStatus;
        });
    }

    function updateStats() {
        const total = hospitalizations.length;
        const occupied = hospitalizations.filter(h => h.status === "en cours").length;
        const intensive = hospitalizations.filter(h => h.service === "Urgences" && h.status === "en cours").length;
        const last7Days = new Date();
        last7Days.setDate(last7Days.getDate() - 7);
        const discharged = hospitalizations.filter(h => h.status === "terminé" && new Date(h.expectedDischarge) >= last7Days).length;
        document.getElementById("totalHospitalizations").innerText = total;
        document.getElementById("occupiedBeds").innerText = occupied;
        document.getElementById("intensiveCare").innerText = intensive;
        document.getElementById("dischargedWeek").innerText = discharged;
    }

    function populatePatientSelect() {
        const select = document.getElementById("patientId");
        select.innerHTML = '<option value="">Sélectionner un patient</option>';
        patientsList.forEach(p => {
            select.innerHTML += `<option value="${p.id}">${p.name}</option>`;
        });
    }

    function renderTable() {
        const filtered = getFilteredHospitalizations();
        const totalFiltered = filtered.length;
        const totalPages = Math.ceil(totalFiltered / rowsPerPage);
        if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
        const start = (currentPage - 1) * rowsPerPage;
        const paginated = filtered.slice(start, start + rowsPerPage);
        
        const tbody = document.getElementById("hospitalizationTableBody");
        tbody.innerHTML = "";
        if (paginated.length === 0) {
            tbody.innerHTML = `<tr><td colspan="9" class="text-center py-12 text-gray-400"><i class="fas fa-hospital text-3xl mb-2 block"></i>Aucune hospitalisation trouvée</td></tr>`;
        } else {
            paginated.forEach(h => {
                let statusClass = h.status === "en cours" ? "bg-emerald-100 text-emerald-700" : "bg-gray-100 text-gray-700";
                const row = `<tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">${h.id}</td>
                    <td class="px-4 py-3 font-medium">${h.patientName}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-lg text-xs bg-blue-100 text-blue-700">${h.service}</span></td>
                    <td class="px-4 py-3">${h.bedNumber || "—"}</td>
                    <td class="px-4 py-3">${h.admissionDate}</td>
                    <td class="px-4 py-3">${h.expectedDischarge || "—"}</td>
                    <td class="px-4 py-3">${h.referringDoctor || "—"}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-semibold ${statusClass}">${h.status === "en cours" ? "En cours" : "Terminé"}</span></td>
                    <td class="px-4 py-3 text-center space-x-2">
                        <button onclick="editHospitalization('${h.id}')" class="text-blue-600 hover:text-blue-800"><i class="far fa-edit"></i></button>
                        <button onclick="deleteHospitalization('${h.id}')" class="text-red-500 hover:text-red-700"><i class="far fa-trash-alt"></i></button>
                        <button onclick="viewHospitalization('${h.id}')" class="text-gray-500 hover:text-secondary"><i class="fas fa-eye"></i></button>
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

    window.editHospitalization = (id) => {
        const h = hospitalizations.find(hosp => hosp.id === id);
        if (h) {
            document.getElementById("modalTitle").innerText = "Modifier l'hospitalisation";
            document.getElementById("editId").value = h.id;
            document.getElementById("patientId").value = h.patientId;
            document.getElementById("service").value = h.service;
            document.getElementById("bedNumber").value = h.bedNumber || "";
            document.getElementById("referringDoctor").value = h.referringDoctor || "";
            document.getElementById("admissionDate").value = h.admissionDate;
            document.getElementById("expectedDischarge").value = h.expectedDischarge || "";
            document.getElementById("status").value = h.status;
            document.getElementById("hospitalizationModal").classList.remove("hidden");
        }
    };

    window.deleteHospitalization = (id) => {
        if (confirm("Supprimer cette hospitalisation ?")) {
            hospitalizations = hospitalizations.filter(h => h.id !== id);
            renderTable();
        }
    };

    window.viewHospitalization = (id) => {
        const h = hospitalizations.find(hosp => hosp.id === id);
        alert(`🏥 Hospitalisation\nPatient: ${h.patientName}\nService: ${h.service}\nLit: ${h.bedNumber || "Non assigné"}\nAdmission: ${h.admissionDate}\nSortie prévue: ${h.expectedDischarge || "Non définie"}\nMédecin: ${h.referringDoctor || "Non assigné"}`);
    };

    document.getElementById("hospitalizationForm").addEventListener("submit", (e) => {
        e.preventDefault();
        const editId = document.getElementById("editId").value;
        const patientId = document.getElementById("patientId").value;
        if (!patientId) return alert("Veuillez sélectionner un patient");
        
        const patient = patientsList.find(p => p.id === patientId);
        
        const formData = {
            patientId, patientName: patient.name,
            service: document.getElementById("service").value,
            bedNumber: document.getElementById("bedNumber").value,
            referringDoctor: document.getElementById("referringDoctor").value,
            admissionDate: document.getElementById("admissionDate").value,
            expectedDischarge: document.getElementById("expectedDischarge").value,
            status: document.getElementById("status").value
        };
        
        if (editId) {
            const idx = hospitalizations.findIndex(h => h.id === editId);
            if (idx !== -1) hospitalizations[idx] = { ...hospitalizations[idx], ...formData, id: editId };
        } else {
            hospitalizations.push({ id: generateHospitalizationId(), ...formData });
        }
        closeModal();
        renderTable();
    });

    function closeModal() {
        document.getElementById("hospitalizationModal").classList.add("hidden");
        document.getElementById("hospitalizationForm").reset();
        document.getElementById("editId").value = "";
    }

    document.getElementById("openAddModalBtn").onclick = () => {
        document.getElementById("editId").value = "";
        document.getElementById("hospitalizationForm").reset();
        document.getElementById("modalTitle").innerText = "Ajouter une hospitalisation";
        document.getElementById("hospitalizationModal").classList.remove("hidden");
    };
    document.getElementById("closeModalBtn").onclick = closeModal;
    document.getElementById("cancelModalBtn").onclick = closeModal;

    function updateFilters() {
        currentServiceFilter = document.getElementById("serviceFilterSelect").value;
        currentStatusFilter = document.getElementById("statusFilterSelect").value;
        currentPage = 1;
        renderTable();
    }

    document.getElementById("serviceFilterSelect").addEventListener("change", updateFilters);
    document.getElementById("statusFilterSelect").addEventListener("change", updateFilters);
    document.getElementById("resetFiltersBtn").addEventListener("click", () => {
        document.getElementById("serviceFilterSelect").value = "all";
        document.getElementById("statusFilterSelect").value = "all";
        updateFilters();
    });
    document.getElementById("prevPageBtn").onclick = () => { if (currentPage > 1) { currentPage--; renderTable(); } };
    document.getElementById("nextPageBtn").onclick = () => { const max = Math.ceil(getFilteredHospitalizations().length / rowsPerPage); if (currentPage < max) { currentPage++; renderTable(); } };

    // Sidebar mobile
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