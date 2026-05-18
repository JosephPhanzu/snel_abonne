<?php
$title = "Rendez-vous | Gestion des consultations";
ob_start();
?>

<!-- En-tête -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Gestion des rendez-vous</h1>
        <p class="text-gray-500 text-sm">Planifiez, gérez et suivez les consultations médicales</p>
    </div>
    <button id="openAddModalBtn" class="bg-primary hover:bg-blue-800 text-white px-5 py-2.5 rounded-xl shadow-md transition flex items-center gap-2 self-start">
        <i class="fas fa-plus-circle"></i> Nouveau rendez-vous
    </button>
</div>

<!-- Cartes statistiques rendez-vous -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-primary">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">Total RDV</p><p id="totalAppointmentsCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-calendar-alt text-3xl text-primary/30"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-emerald-500">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">Aujourd'hui</p><p id="todayCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-calendar-day text-3xl text-emerald-500/30"></i>
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
            <div><p class="text-gray-500 text-sm">Confirmés</p><p id="confirmedCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-check-circle text-3xl text-amber-500/30"></i>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="flex flex-col sm:flex-row justify-between gap-4">
    <div class="flex flex-wrap gap-3">
        <select id="statusFilterSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white focus:ring-2 focus:ring-primary/30">
            <option value="all">Tous les statuts</option>
            <option value="confirmé">Confirmé</option>
            <option value="en attente">En attente</option>
            <option value="terminé">Terminé</option>
            <option value="annulé">Annulé</option>
        </select>
        <select id="doctorFilterSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white focus:ring-2 focus:ring-primary/30">
            <option value="all">Tous les médecins</option>
        </select>
        <input type="date" id="dateFilterSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white focus:ring-2 focus:ring-primary/30">
        <button id="resetFiltersBtn" class="text-gray-600 hover:text-primary bg-gray-100 px-4 rounded-xl text-sm transition"><i class="fas fa-undo-alt mr-1"></i> Réinitialiser</button>
    </div>
</div>

<!-- Tableau rendez-vous -->
<div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
    <div class="overflow-x-auto custom-scroll">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Patient</th>
                    <th class="px-4 py-3">Médecin</th>
                    <th class="px-4 py-3">Spécialité</th>
                    <th class="px-4 py-3">Date & Heure</th>
                    <th class="px-4 py-3">Motif</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                 </tr>
            </thead>
            <tbody id="appointmentTableBody" class="divide-y divide-gray-100"></tbody>
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

<!-- MODAL AJOUT / MODIFICATION RENDEZ-VOUS -->
<div id="appointmentModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-200">
    <div class="bg-white rounded-2xl max-w-lg w-full mx-4 shadow-2xl overflow-hidden transform transition-all">
        <div class="bg-primary px-6 py-4 flex justify-between items-center">
            <h3 id="modalTitle" class="text-white font-bold text-xl">Ajouter un rendez-vous</h3>
            <button id="closeModalBtn" class="text-white/80 hover:text-white text-2xl">&times;</button>
        </div>
        <form id="appointmentForm" class="p-6 space-y-4">
            <input type="hidden" id="editId" value="">
            <div>
                <label class="block text-gray-700 text-sm font-semibold">Patient *</label>
                <select id="patientId" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5">
                    <option value="">Sélectionner un patient</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold">Médecin *</label>
                <select id="doctorId" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5">
                    <option value="">Sélectionner un médecin</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-gray-700 text-sm font-semibold">Date *</label><input type="date" id="appointmentDate" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5"></div>
                <div><label class="block text-gray-700 text-sm font-semibold">Heure *</label><input type="time" id="appointmentTime" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5"></div>
            </div>
            <div><label class="block text-gray-700 text-sm font-semibold">Motif</label><input type="text" id="reason" class="w-full border border-gray-200 rounded-xl px-3 py-2.5" placeholder="Motif de la consultation"></div>
            <div><label class="block text-gray-700 text-sm font-semibold">Statut</label>
                <select id="status" class="w-full border border-gray-200 rounded-xl px-3 py-2.5">
                    <option value="confirmé">Confirmé</option>
                    <option value="en attente">En attente</option>
                    <option value="terminé">Terminé</option>
                    <option value="annulé">Annulé</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" id="cancelModalBtn" class="px-5 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">Annuler</button>
                <button type="submit" class="px-5 py-2 bg-primary text-white rounded-xl shadow hover:bg-blue-900 transition">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Données patients et médecins (liées aux pages précédentes)
    const patientsList = [
        { id: "P1001", name: "Sophie Dubois" }, { id: "P1002", name: "Lucas Martin" },
        { id: "P1003", name: "Emma Petit" }, { id: "P1004", name: "Thomas Bernard" },
        { id: "P1005", name: "Julie Roux" }, { id: "P1006", name: "Nicolas Leroy" }
    ];
    
    const doctorsList = [
        { id: "M1001", name: "Dr. Sophie Martin", specialty: "Cardiologie" },
        { id: "M1002", name: "Dr. Thomas Bernard", specialty: "Neurologie" },
        { id: "M1003", name: "Dr. Julie Petit", specialty: "Pédiatrie" },
        { id: "M1004", name: "Dr. Nicolas Robert", specialty: "Orthopédie" }
    ];

    // Données rendez-vous
    let appointments = [
        { id: "RDV001", patientId: "P1001", patientName: "Sophie Dubois", doctorId: "M1001", doctorName: "Dr. Sophie Martin", specialty: "Cardiologie", date: "2025-04-26", time: "09:00", reason: "Consultation cardiaque", status: "confirmé" },
        { id: "RDV002", patientId: "P1002", patientName: "Lucas Martin", doctorId: "M1002", doctorName: "Dr. Thomas Bernard", specialty: "Neurologie", date: "2025-04-26", time: "10:30", reason: "Maux de tête chroniques", status: "confirmé" },
        { id: "RDV003", patientId: "P1003", patientName: "Emma Petit", doctorId: "M1003", doctorName: "Dr. Julie Petit", specialty: "Pédiatrie", date: "2025-04-26", time: "14:00", reason: "Vaccin", status: "en attente" },
        { id: "RDV004", patientId: "P1004", patientName: "Thomas Bernard", doctorId: "M1001", doctorName: "Dr. Sophie Martin", specialty: "Cardiologie", date: "2025-04-27", time: "11:00", reason: "Douleurs thoraciques", status: "confirmé" },
        { id: "RDV005", patientId: "P1005", patientName: "Julie Roux", doctorId: "M1004", doctorName: "Dr. Nicolas Robert", specialty: "Orthopédie", date: "2025-04-25", time: "15:30", reason: "Fracture poignet", status: "terminé" },
        { id: "RDV006", patientId: "P1006", patientName: "Nicolas Leroy", doctorId: "M1002", doctorName: "Dr. Thomas Bernard", specialty: "Neurologie", date: "2025-04-28", time: "08:30", reason: "Épilepsie", status: "en attente" }
    ];

    function generateAppointmentId() {
        let maxNum = 0;
        appointments.forEach(a => { let num = parseInt(a.id.substring(3)); if (num > maxNum) maxNum = num; });
        return "RDV" + String(maxNum + 1).padStart(3, '0');
    }

    let currentSearch = "";
    let currentStatusFilter = "all";
    let currentDoctorFilter = "all";
    let currentDateFilter = "";
    let currentPage = 1;
    const rowsPerPage = 5;

    function getFilteredAppointments() {
        return appointments.filter(a => {
            const matchStatus = (currentStatusFilter === "all") || (a.status === currentStatusFilter);
            const matchDoctor = (currentDoctorFilter === "all") || (a.doctorId === currentDoctorFilter);
            const matchDate = !currentDateFilter || (a.date === currentDateFilter);
            return matchStatus && matchDoctor && matchDate;
        });
    }

    function updateStats() {
        const total = appointments.length;
        const today = new Date().toISOString().slice(0,10);
        const todayCount = appointments.filter(a => a.date === today).length;
        const pending = appointments.filter(a => a.status === "en attente").length;
        const confirmed = appointments.filter(a => a.status === "confirmé").length;
        document.getElementById("totalAppointmentsCount").innerText = total;
        document.getElementById("todayCount").innerText = todayCount;
        document.getElementById("pendingCount").innerText = pending;
        document.getElementById("confirmedCount").innerText = confirmed;
    }

    function populateDoctorFilter() {
        const select = document.getElementById("doctorFilterSelect");
        select.innerHTML = '<option value="all">Tous les médecins</option>';
        doctorsList.forEach(doc => {
            select.innerHTML += `<option value="${doc.id}">${doc.name}</option>`;
        });
    }

    function populateFormSelects() {
        const patientSelect = document.getElementById("patientId");
        const doctorSelect = document.getElementById("doctorId");
        patientSelect.innerHTML = '<option value="">Sélectionner un patient</option>';
        doctorsList.forEach(doc => {
            doctorSelect.innerHTML = `<option value="${doc.id}">${doc.name} (${doc.specialty})</option>`;
        });
        patientsList.forEach(pat => {
            patientSelect.innerHTML += `<option value="${pat.id}">${pat.name}</option>`;
        });
    }

    function renderTable() {
        const filtered = getFilteredAppointments();
        const totalFiltered = filtered.length;
        const totalPages = Math.ceil(totalFiltered / rowsPerPage);
        if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
        const start = (currentPage - 1) * rowsPerPage;
        const paginated = filtered.slice(start, start + rowsPerPage);
        
        const tbody = document.getElementById("appointmentTableBody");
        tbody.innerHTML = "";
        if (paginated.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center py-12 text-gray-400"><i class="fas fa-calendar-times text-3xl mb-2 block"></i>Aucun rendez-vous trouvé</td></tr>`;
        } else {
            paginated.forEach(a => {
                let statusClass = { confirmé: "bg-emerald-100 text-emerald-700", "en attente": "bg-amber-100 text-amber-700", terminé: "bg-blue-100 text-blue-700", annulé: "bg-red-100 text-red-700" }[a.status] || "bg-gray-100";
                const row = `<tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono text-xs">${a.id}</td>
                    <td class="px-4 py-3 font-medium">${a.patientName}</td>
                    <td class="px-4 py-3">${a.doctorName}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-lg text-xs bg-secondary/10 text-secondary">${a.specialty}</span></td>
                    <td class="px-4 py-3">${a.date} à ${a.time}</td>
                    <td class="px-4 py-3 text-sm">${a.reason || "—"}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-semibold ${statusClass}">${a.status}</span></td>
                    <td class="px-4 py-3 text-center space-x-2">
                        <button onclick="editAppointment('${a.id}')" class="text-blue-600 hover:text-blue-800"><i class="far fa-edit"></i></button>
                        <button onclick="deleteAppointment('${a.id}')" class="text-red-500 hover:text-red-700"><i class="far fa-trash-alt"></i></button>
                        <button onclick="viewAppointment('${a.id}')" class="text-gray-500 hover:text-secondary"><i class="fas fa-eye"></i></button>
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

    window.editAppointment = (id) => {
        const apt = appointments.find(a => a.id === id);
        if (apt) {
            document.getElementById("modalTitle").innerText = "Modifier le rendez-vous";
            document.getElementById("editId").value = apt.id;
            document.getElementById("patientId").value = apt.patientId;
            document.getElementById("doctorId").value = apt.doctorId;
            document.getElementById("appointmentDate").value = apt.date;
            document.getElementById("appointmentTime").value = apt.time;
            document.getElementById("reason").value = apt.reason || "";
            document.getElementById("status").value = apt.status;
            document.getElementById("appointmentModal").classList.remove("hidden");
        }
    };

    window.deleteAppointment = (id) => {
        if (confirm("Supprimer ce rendez-vous ?")) {
            appointments = appointments.filter(a => a.id !== id);
            renderTable();
        }
    };

    window.viewAppointment = (id) => {
        const a = appointments.find(apt => apt.id === id);
        alert(`📅 Rendez-vous\nPatient: ${a.patientName}\nMédecin: ${a.doctorName}\nDate: ${a.date} à ${a.time}\nMotif: ${a.reason || "Non spécifié"}\nStatut: ${a.status}`);
    };

    document.getElementById("appointmentForm").addEventListener("submit", (e) => {
        e.preventDefault();
        const editId = document.getElementById("editId").value;
        const patientId = document.getElementById("patientId").value;
        const doctorId = document.getElementById("doctorId").value;
        if (!patientId || !doctorId) return alert("Veuillez sélectionner un patient et un médecin");
        
        const patient = patientsList.find(p => p.id === patientId);
        const doctor = doctorsList.find(d => d.id === doctorId);
        
        const formData = {
            patientId, patientName: patient.name,
            doctorId, doctorName: doctor.name, specialty: doctor.specialty,
            date: document.getElementById("appointmentDate").value,
            time: document.getElementById("appointmentTime").value,
            reason: document.getElementById("reason").value,
            status: document.getElementById("status").value
        };
        
        if (editId) {
            const idx = appointments.findIndex(a => a.id === editId);
            if (idx !== -1) appointments[idx] = { ...appointments[idx], ...formData, id: editId };
        } else {
            appointments.push({ id: generateAppointmentId(), ...formData });
        }
        closeModal();
        renderTable();
    });

    function closeModal() {
        document.getElementById("appointmentModal").classList.add("hidden");
        document.getElementById("appointmentForm").reset();
        document.getElementById("editId").value = "";
    }

    document.getElementById("openAddModalBtn").onclick = () => {
        document.getElementById("editId").value = "";
        document.getElementById("appointmentForm").reset();
        document.getElementById("modalTitle").innerText = "Ajouter un rendez-vous";
        document.getElementById("appointmentModal").classList.remove("hidden");
    };
    document.getElementById("closeModalBtn").onclick = closeModal;
    document.getElementById("cancelModalBtn").onclick = closeModal;

    function updateFilters() {
        currentStatusFilter = document.getElementById("statusFilterSelect").value;
        currentDoctorFilter = document.getElementById("doctorFilterSelect").value;
        currentDateFilter = document.getElementById("dateFilterSelect").value;
        currentPage = 1;
        renderTable();
    }

    document.getElementById("statusFilterSelect").addEventListener("change", updateFilters);
    document.getElementById("doctorFilterSelect").addEventListener("change", updateFilters);
    document.getElementById("dateFilterSelect").addEventListener("change", updateFilters);
    document.getElementById("resetFiltersBtn").addEventListener("click", () => {
        document.getElementById("statusFilterSelect").value = "all";
        document.getElementById("doctorFilterSelect").value = "all";
        document.getElementById("dateFilterSelect").value = "";
        updateFilters();
    });
    document.getElementById("prevPageBtn").onclick = () => { if (currentPage > 1) { currentPage--; renderTable(); } };
    document.getElementById("nextPageBtn").onclick = () => { const max = Math.ceil(getFilteredAppointments().length / rowsPerPage); if (currentPage < max) { currentPage++; renderTable(); } };

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

    populateDoctorFilter();
    populateFormSelects();
    renderTable();
</script>

<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
?>