<?php
$title = "Laboratoire | Gestion des examens";
ob_start();
?>

<!-- En-tête laboratoire -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Laboratoire d'analyses</h1>
        <p class="text-gray-500 text-sm">Gérez les examens prescrits et saisissez les résultats</p>
    </div>
    <div class="flex items-center gap-3 bg-white rounded-xl shadow-sm px-4 py-2">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-blue-700 flex items-center justify-center text-white font-bold">
            <i class="fas fa-microscope"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800">Laboratoire Central</p>
            <p class="text-xs text-gray-400">Biochimie - Hématologie</p>
        </div>
    </div>
</div>

<!-- Cartes statistiques laboratoire -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-primary">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">Examens en attente</p><p id="pendingExamsCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-hourglass-half text-3xl text-primary/30"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-secondary">
        <div class="flex justify-between items-center"><div><p class="text-gray-500 text-sm">Examens aujourd'hui</p><p id="todayExamsCount" class="text-2xl font-bold text-gray-800">0</p></div><i class="fas fa-calendar-day text-3xl text-secondary/30"></i></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-emerald-500">
        <div class="flex justify-between items-center"><div><p class="text-gray-500 text-sm">Résultats validés</p><p id="completedExamsCount" class="text-2xl font-bold text-gray-800">0</p></div><i class="fas fa-check-circle text-3xl text-emerald-500/30"></i></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-amber-500">
        <div class="flex justify-between items-center"><div><p class="text-gray-500 text-sm">Médecins prescripteurs</p><p id="doctorsCount" class="text-2xl font-bold text-gray-800">0</p></div><i class="fas fa-user-md text-3xl text-amber-500/30"></i></div>
    </div>
</div>

<!-- Filtres -->
<div class="flex flex-col sm:flex-row justify-between gap-4 mb-6">
    <div class="flex flex-wrap gap-3">
        <select id="statusFilterSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white focus:ring-2 focus:ring-primary/30">
            <option value="all">Tous les statuts</option>
            <option value="en attente">En attente</option>
            <option value="en cours">En cours</option>
            <option value="terminé">Terminé</option>
        </select>
        <select id="doctorFilterSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white focus:ring-2 focus:ring-primary/30">
            <option value="all">Tous les médecins</option>
        </select>
        <input type="date" id="dateFilterSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white">
        <button id="resetFiltersBtn" class="text-gray-600 hover:text-primary bg-gray-100 px-4 rounded-xl text-sm transition"><i class="fas fa-undo-alt mr-1"></i> Réinitialiser</button>
    </div>
    <div class="flex gap-2">
        <button id="refreshBtn" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-xl transition"><i class="fas fa-sync-alt"></i> Actualiser</button>
    </div>
</div>

<!-- Liste des examens prescrits -->
<div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-flask text-secondary mr-2"></i>Examens prescrits par les médecins</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3">ID Examen</th>
                    <th class="px-6 py-3">Patient</th>
                    <th class="px-6 py-3">Médecin prescripteur</th>
                    <th class="px-6 py-3">Type d'examen</th>
                    <th class="px-6 py-3">Date prescription</th>
                    <th class="px-6 py-3">Statut</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                 </tr>
            </thead>
            <tbody id="examsTableBody" class="divide-y divide-gray-100"></tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
        <div class="text-xs text-gray-500"><span id="paginationInfo"></span></div>
        <div class="flex gap-2">
            <button id="prevPageBtn" class="px-3 py-1 border rounded-lg text-gray-600 hover:bg-gray-100 disabled:opacity-40"><i class="fas fa-chevron-left"></i> Précédent</button>
            <button id="nextPageBtn" class="px-3 py-1 border rounded-lg text-gray-600 hover:bg-gray-100 disabled:opacity-40">Suivant <i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</div>

<!-- MODAL SAISIE DES RÉSULTATS -->
<div id="resultModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-200 overflow-y-auto py-8">
    <div class="bg-white rounded-2xl max-w-2xl w-full mx-4 shadow-2xl overflow-hidden">
        <div class="bg-secondary px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold text-xl"><i class="fas fa-edit mr-2"></i>Saisie des résultats</h3>
            <button id="closeResultModalBtn" class="text-white/80 hover:text-white text-2xl">&times;</button>
        </div>
        
        <div class="p-6">
            <!-- Récapitulatif examen -->
            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400">Patient</p>
                        <p class="font-semibold" id="resultPatientName"></p>
                        <p class="text-sm text-gray-500" id="resultPatientId"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Médecin prescripteur</p>
                        <p class="font-semibold" id="resultDoctorName"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Type d'examen</p>
                        <p class="font-semibold text-secondary" id="resultExamType"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Date de prescription</p>
                        <p class="font-semibold" id="resultPrescriptionDate"></p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <p class="text-xs text-gray-400">Instructions / Notes du médecin</p>
                    <p class="text-sm italic text-gray-600" id="resultInstructions">—</p>
                </div>
            </div>
            
            <!-- Formulaire résultats -->
            <form id="resultForm" class="space-y-5">
                <input type="hidden" id="examId">
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Résultats de l'analyse *</label>
                    <textarea id="labResults" rows="5" required class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-secondary/30" placeholder="Description détaillée des résultats...
Exemple:
- Glycémie: 1.05 g/L (normal)
- Cholestérol total: 1.80 g/L
- Triglycérides: 1.20 g/L"></textarea>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Valeurs de référence / Interprétation</label>
                    <textarea id="referenceRange" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-secondary/30" placeholder="Valeurs normales de référence..."></textarea>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Commentaires additionnels</label>
                    <textarea id="labComments" rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-secondary/30" placeholder="Remarques pour le médecin..."></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Statut de l'examen</label>
                        <select id="examStatus" class="w-full border border-gray-200 rounded-xl px-4 py-2.5">
                            <option value="terminé">Terminé - Résultats disponibles</option>
                            <option value="en cours">En cours d'analyse</option>
                            <option value="à refaire">À refaire - Échantillon non valide</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Date de réalisation</label>
                        <input type="date" id="analysisDate" class="w-full border border-gray-200 rounded-xl px-4 py-2.5">
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" id="cancelResultBtn" class="px-5 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">Annuler</button>
                    <button type="submit" class="px-5 py-2 bg-secondary text-white rounded-xl shadow hover:bg-orange-600 transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Enregistrer les résultats
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL VISUALISATION DES RÉSULTATS -->
<div id="viewResultModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-200 overflow-y-auto py-8">
    <div class="bg-white rounded-2xl max-w-2xl w-full mx-4 shadow-2xl overflow-hidden">
        <div class="bg-primary px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold text-xl"><i class="fas fa-file-alt mr-2"></i>Résultats d'examen</h3>
            <button id="closeViewModalBtn" class="text-white/80 hover:text-white text-2xl">&times;</button>
        </div>
        
        <div class="p-6 max-h-[70vh] overflow-y-auto" id="viewResultContent">
            <!-- Contenu dynamique -->
        </div>
    </div>
</div>

<script>
// ==================== BASE DE DONNÉES EXAMENS ====================
let labExams = JSON.parse(localStorage.getItem("mediflow_lab_exams")) || [];

// Initialiser quelques examens si vide
if(labExams.length === 0) {
    labExams = [
        { id: "LAB001", patientId: "P1001", patientName: "Sophie Dubois", doctorId: "M1001", doctorName: "Dr. Sophie Martin", examType: "Prise de sang", prescriptionDate: "2025-05-01", scheduledDate: "2025-05-05", instructions: "Vérifier glycémie et cholestérol", status: "en attente", results: null, referenceRange: null, comments: null, analysisDate: null },
        { id: "LAB002", patientId: "P1004", patientName: "Thomas Bernard", doctorId: "M1001", doctorName: "Dr. Sophie Martin", examType: "Échocardiogramme", prescriptionDate: "2025-05-03", scheduledDate: "2025-05-07", instructions: "Évaluer fonction cardiaque", status: "en attente", results: null, referenceRange: null, comments: null, analysisDate: null },
        { id: "LAB003", patientId: "P1008", patientName: "Antoine Simon", doctorId: "M1001", doctorName: "Dr. Sophie Martin", examType: "Prise de sang", prescriptionDate: "2025-05-02", scheduledDate: "2025-05-06", instructions: "Bilan complet", status: "terminé", results: "Glycémie: 1.25 g/L\nCholestérol: 2.10 g/L\nTriglycérides: 1.50 g/L", referenceRange: "Glycémie: 0.70-1.10 g/L\nCholestérol: <2.00 g/L", comments: "Hypercholestérolémie modérée", analysisDate: "2025-05-06" },
        { id: "LAB004", patientId: "P1002", patientName: "Lucas Martin", doctorId: "M1004", doctorName: "Dr. Nicolas Robert", examType: "Radiographie", prescriptionDate: "2025-05-04", scheduledDate: "2025-05-08", instructions: "Fracture tibia contrôle", status: "en cours", results: null, referenceRange: null, comments: null, analysisDate: null }
    ];
    saveExams();
}

function saveExams() {
    localStorage.setItem("mediflow_lab_exams", JSON.stringify(labExams));
}

// Variables pagination
let currentPage = 1;
let rowsPerPage = 8;
let currentStatusFilter = "all";
let currentDoctorFilter = "all";
let currentDateFilter = "";

function getFilteredExams() {
    let filtered = labExams.filter(e => {
        const matchStatus = (currentStatusFilter === "all") || (e.status === currentStatusFilter);
        const matchDoctor = (currentDoctorFilter === "all") || (e.doctorId === currentDoctorFilter);
        const matchDate = !currentDateFilter || (e.prescriptionDate === currentDateFilter);
        return matchStatus && matchDoctor && matchDate;
    });
    return filtered;
}

// Peupler filtre médecins
function populateDoctorFilter() {
    const doctors = [...new Set(labExams.map(e => e.doctorId))];
    const select = document.getElementById("doctorFilterSelect");
    select.innerHTML = '<option value="all">Tous les médecins</option>';
    doctors.forEach(docId => {
        const exam = labExams.find(e => e.doctorId === docId);
        if(exam) {
            select.innerHTML += `<option value="${docId}">${exam.doctorName}</option>`;
        }
    });
}

// Mise à jour statistiques
function updateStats() {
    const pending = labExams.filter(e => e.status === "en attente").length;
    const today = new Date().toISOString().slice(0,10);
    const todayExams = labExams.filter(e => e.scheduledDate === today || e.prescriptionDate === today).length;
    const completed = labExams.filter(e => e.status === "terminé").length;
    const uniqueDoctors = [...new Set(labExams.map(e => e.doctorId))].length;
    
    document.getElementById("pendingExamsCount").innerText = pending;
    document.getElementById("todayExamsCount").innerText = todayExams;
    document.getElementById("completedExamsCount").innerText = completed;
    document.getElementById("doctorsCount").innerText = uniqueDoctors;
}

// Rendu tableau
function renderTable() {
    const filtered = getFilteredExams();
    const totalFiltered = filtered.length;
    const totalPages = Math.ceil(totalFiltered / rowsPerPage);
    if(currentPage > totalPages && totalPages > 0) currentPage = totalPages;
    if(currentPage < 1) currentPage = 1;
    const start = (currentPage - 1) * rowsPerPage;
    const paginated = filtered.slice(start, start + rowsPerPage);
    
    const tbody = document.getElementById("examsTableBody");
    tbody.innerHTML = "";
    
    if(paginated.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-8 text-gray-400">Aucun examen trouvé</td></tr>`;
    } else {
        paginated.forEach(e => {
            let statusClass = "";
            let statusText = "";
            if(e.status === "en attente") { statusClass = "bg-amber-100 text-amber-700"; statusText = "En attente"; }
            else if(e.status === "en cours") { statusClass = "bg-blue-100 text-blue-700"; statusText = "En cours"; }
            else if(e.status === "terminé") { statusClass = "bg-emerald-100 text-emerald-700"; statusText = "Terminé"; }
            else { statusClass = "bg-gray-100 text-gray-700"; statusText = e.status; }
            
            const row = `<tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-3 font-mono text-xs">${e.id}</td>
                <td class="px-6 py-3"><span class="font-medium">${e.patientName}</span><br><span class="text-xs text-gray-400">${e.patientId}</span></td>
                <td class="px-6 py-3 text-sm">${e.doctorName}</td>
                <td class="px-6 py-3"><span class="px-2 py-1 rounded-lg text-xs bg-secondary/10 text-secondary">${e.examType}</span></td>
                <td class="px-6 py-3 text-sm">${e.prescriptionDate}</td>
                <td class="px-6 py-3"><span class="px-2 py-1 rounded-full text-xs font-semibold ${statusClass}">${statusText}</span></td>
                <td class="px-6 py-3 text-center space-x-2">
                    ${e.status !== "terminé" ? `<button onclick="openResultModal('${e.id}')" class="bg-secondary text-white px-3 py-1.5 rounded-lg text-sm hover:bg-orange-600 transition"><i class="fas fa-edit mr-1"></i> Saisir résultats</button>` : 
                    `<button onclick="viewResults('${e.id}')" class="bg-primary text-white px-3 py-1.5 rounded-lg text-sm hover:bg-blue-800 transition"><i class="fas fa-eye mr-1"></i> Voir résultats</button>`}
                 </td>
             </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }
    
    document.getElementById("paginationInfo").innerHTML = `${totalFiltered ? start+1 : 0} - ${Math.min(start+rowsPerPage, totalFiltered)} sur ${totalFiltered} examens`;
    document.getElementById("prevPageBtn").disabled = currentPage === 1;
    document.getElementById("nextPageBtn").disabled = currentPage === totalPages;
    updateStats();
}

// Ouvrir modal saisie résultats
window.openResultModal = (examId) => {
    const exam = labExams.find(e => e.id === examId);
    if(!exam) return;
    
    document.getElementById("examId").value = exam.id;
    document.getElementById("resultPatientName").innerHTML = `${exam.patientName} <span class="text-xs text-gray-400">(${exam.patientId})</span>`;
    document.getElementById("resultPatientId").innerText = exam.patientId;
    document.getElementById("resultDoctorName").innerText = exam.doctorName;
    document.getElementById("resultExamType").innerText = exam.examType;
    document.getElementById("resultPrescriptionDate").innerText = exam.prescriptionDate;
    document.getElementById("resultInstructions").innerHTML = exam.instructions || "—";
    
    document.getElementById("labResults").value = exam.results || "";
    document.getElementById("referenceRange").value = exam.referenceRange || "";
    document.getElementById("labComments").value = exam.comments || "";
    document.getElementById("examStatus").value = exam.status === "terminé" ? "terminé" : (exam.status === "en cours" ? "en cours" : "terminé");
    document.getElementById("analysisDate").value = exam.analysisDate || new Date().toISOString().slice(0,10);
    
    document.getElementById("resultModal").classList.remove("hidden");
};

// Enregistrer les résultats
document.getElementById("resultForm").addEventListener("submit", (e) => {
    e.preventDefault();
    const examId = document.getElementById("examId").value;
    const examIndex = labExams.findIndex(e => e.id === examId);
    
    if(examIndex !== -1) {
        labExams[examIndex].results = document.getElementById("labResults").value;
        labExams[examIndex].referenceRange = document.getElementById("referenceRange").value;
        labExams[examIndex].comments = document.getElementById("labComments").value;
        labExams[examIndex].status = document.getElementById("examStatus").value;
        labExams[examIndex].analysisDate = document.getElementById("analysisDate").value;
        
        saveExams();
        
        // Ajouter une notification dans le système (simulée)
        addNotificationToDoctor(labExams[examIndex]);
        
        alert(`✅ Résultats enregistrés pour l'examen ${examId}`);
        closeResultModal();
        renderTable();
        populateDoctorFilter();
    }
});

// Ajouter notification pour le médecin (simulée dans localStorage)
function addNotificationToDoctor(exam) {
    let notifications = JSON.parse(localStorage.getItem("mediflow_doctor_notifications")) || [];
    notifications.push({
        id: Date.now(),
        doctorId: exam.doctorId,
        doctorName: exam.doctorName,
        patientName: exam.patientName,
        examType: exam.examType,
        examId: exam.id,
        message: `Les résultats de l'examen "${exam.examType}" pour ${exam.patientName} sont disponibles.`,
        date: new Date().toISOString(),
        read: false
    });
    localStorage.setItem("mediflow_doctor_notifications", JSON.stringify(notifications));
}

// Visualiser les résultats
window.viewResults = (examId) => {
    const exam = labExams.find(e => e.id === examId);
    if(!exam || !exam.results) return;
    
    const modalContent = document.getElementById("viewResultContent");
    modalContent.innerHTML = `
        <div class="space-y-4">
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="grid grid-cols-2 gap-3">
                    <div><p class="text-xs text-gray-400">Patient</p><p class="font-semibold">${exam.patientName}</p><p class="text-sm text-gray-500">${exam.patientId}</p></div>
                    <div><p class="text-xs text-gray-400">Médecin prescripteur</p><p class="font-semibold">${exam.doctorName}</p></div>
                    <div><p class="text-xs text-gray-400">Type d'examen</p><p class="font-semibold text-secondary">${exam.examType}</p></div>
                    <div><p class="text-xs text-gray-400">Date de réalisation</p><p class="font-semibold">${exam.analysisDate || "Non spécifiée"}</p></div>
                </div>
            </div>
            
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <h4 class="font-bold text-gray-700 mb-2"><i class="fas fa-chart-line text-secondary mr-2"></i>Résultats</h4>
                <div class="bg-gray-50 rounded-lg p-3 whitespace-pre-wrap font-mono text-sm">${escapeHtml(exam.results)}</div>
            </div>
            
            ${exam.referenceRange ? `
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <h4 class="font-bold text-gray-700 mb-2"><i class="fas fa-book text-primary mr-2"></i>Valeurs de référence</h4>
                <div class="bg-gray-50 rounded-lg p-3 whitespace-pre-wrap text-sm">${escapeHtml(exam.referenceRange)}</div>
            </div>` : ''}
            
            ${exam.comments ? `
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <h4 class="font-bold text-amber-700 mb-2"><i class="fas fa-comment mr-2"></i>Commentaires du laboratoire</h4>
                <p class="text-sm">${escapeHtml(exam.comments)}</p>
            </div>` : ''}
            
            <div class="bg-blue-50 rounded-xl p-3 text-center">
                <p class="text-xs text-gray-500">Résultats validés le ${new Date(exam.analysisDate || exam.prescriptionDate).toLocaleDateString('fr-FR')}</p>
            </div>
        </div>
    `;
    
    document.getElementById("viewResultModal").classList.remove("hidden");
};

function escapeHtml(str) { if(!str) return ''; return str.replace(/[&<>]/g, function(m){if(m==='&') return '&amp;'; if(m==='<') return '&lt;'; if(m==='>') return '&gt;'; return m;}); }

function closeResultModal() {
    document.getElementById("resultModal").classList.add("hidden");
    document.getElementById("resultForm").reset();
}

document.getElementById("closeResultModalBtn").onclick = closeResultModal;
document.getElementById("cancelResultBtn").onclick = closeResultModal;
document.getElementById("closeViewModalBtn").onclick = () => {
    document.getElementById("viewResultModal").classList.add("hidden");
};

// Filtres
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
document.getElementById("refreshBtn").onclick = () => { renderTable(); populateDoctorFilter(); };

document.getElementById("prevPageBtn").onclick = () => { if(currentPage > 1) { currentPage--; renderTable(); } };
document.getElementById("nextPageBtn").onclick = () => { const max = Math.ceil(getFilteredExams().length / rowsPerPage); if(currentPage < max) { currentPage++; renderTable(); } };

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
populateDoctorFilter();
renderTable();
</script>

<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
?>