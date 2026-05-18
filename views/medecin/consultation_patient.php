<?php
$title = "Espace Médecin | Dr. Sophie Martin";
ob_start();
?>

<!-- En-tête médecin -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tableau de bord médical</h1>
        <p class="text-gray-500 text-sm">Gérez vos patients, prescriptions et examens</p>
    </div>
    <div class="flex items-center gap-3 bg-white rounded-xl shadow-sm px-4 py-2">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-secondary to-orange-500 flex items-center justify-center text-white font-bold">DR</div>
        <div>
            <p class="font-semibold text-gray-800" id="doctorNameDisplay">Dr. Sophie Martin</p>
            <p class="text-xs text-gray-400" id="doctorSpecialtyDisplay">Cardiologie</p>
        </div>
    </div>
</div>

<!-- Cartes statistiques médecin -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-primary">
        <div class="flex justify-between items-center">
            <div><p class="text-gray-500 text-sm">Mes patients</p><p id="myPatientsCount" class="text-2xl font-bold text-gray-800">0</p></div>
            <i class="fas fa-users text-3xl text-primary/30"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-emerald-500">
        <div class="flex justify-between items-center"><div><p class="text-gray-500 text-sm">Consultations aujourd'hui</p><p id="todayConsultations" class="text-2xl font-bold text-gray-800">0</p></div><i class="fas fa-calendar-day text-3xl text-emerald-500/30"></i></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-secondary">
        <div class="flex justify-between items-center"><div><p class="text-gray-500 text-sm">Prescriptions en attente</p><p id="pendingPrescriptions" class="text-2xl font-bold text-gray-800">0</p></div><i class="fas fa-prescription-bottle text-3xl text-secondary/30"></i></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-amber-500">
        <div class="flex justify-between items-center"><div><p class="text-gray-500 text-sm">Examens demandés</p><p id="labRequestsCount" class="text-2xl font-bold text-gray-800">0</p></div><i class="fas fa-microscope text-3xl text-amber-500/30"></i></div>
    </div>
</div>

<!-- Liste des patients assignés -->
<div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 mb-6">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-user-md text-primary mr-2"></i>Mes patients assignés</h2>
        <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="searchPatientInput" placeholder="Rechercher patient..." class="pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm w-64">
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3">Patient</th>
                    <th class="px-6 py-3">Âge / Sexe</th>
                    <th class="px-6 py-3">Diagnostic</th>
                    <th class="px-6 py-3">Dernière consultation</th>
                    <th class="px-6 py-3">Statut</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                 </tr>
            </thead>
            <tbody id="myPatientsTableBody" class="divide-y divide-gray-100"></tbody>
        </table>
    </div>
</div>

<!-- MODAL CONSULTATION PATIENT (Dossier complet + Prescriptions) -->
<div id="consultationModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-200 overflow-y-auto py-8">
    <div class="bg-white rounded-2xl max-w-4xl w-full mx-4 shadow-2xl overflow-hidden">
        <div class="bg-primary px-6 py-4 flex justify-between items-center sticky top-0">
            <h3 class="text-white font-bold text-xl">Dossier patient</h3>
            <button id="closeConsultModalBtn" class="text-white/80 hover:text-white text-2xl">&times;</button>
        </div>
        
        <div class="p-6 max-h-[80vh] overflow-y-auto">
            <!-- Informations patient -->
            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 id="consultPatientName" class="text-xl font-bold text-gray-800"></h4>
                        <p id="consultPatientInfo" class="text-gray-500 text-sm"></p>
                        <p class="mt-2"><span class="font-semibold">N° dossier:</span> <span id="consultPatientId"></span></p>
                        <p><span class="font-semibold">Contact:</span> <span id="consultPatientPhone"></span></p>
                        <p><span class="font-semibold">Adresse:</span> <span id="consultPatientAddress"></span></p>
                    </div>
                    <div class="text-right">
                        <span id="consultPatientStatus" class="px-3 py-1 rounded-full text-sm font-semibold"></span>
                    </div>
                </div>
            </div>
            
            <!-- Diagnostic actuel -->
            <div class="mb-6">
                <h5 class="font-bold text-gray-700 mb-2"><i class="fas fa-stethoscope text-secondary mr-2"></i>Diagnostic Médical</h5>
                <div class="bg-amber-50 rounded-xl p-3 border border-amber-200">
                    <p  class="text-gray-700">Génère texte automatiquement</p>
                </div>
            </div>
            
            <!-- Onglets Prescriptions -->
            <div class="border-b border-gray-200 mb-4">
                <nav class="flex gap-4">
                    <button onclick="showPrescriptionTab('diagnostic')" id="tabDiagnosticBtn" class="py-2 px-4 border-b-2 border-transparent text-gray-500 font-medium">Diagnostic</button>
                    <button onclick="showPrescriptionTab('examens')" id="tabExamensBtn" class="py-2 px-4 border-b-2 border-transparent text-gray-500 font-medium">Examens de laboratoire</button>
                    <button onclick="showPrescriptionTab('medicaments')" id="tabMedicamentsBtn" class="py-2 px-4 border-b-2 border-transparent text-gray-500 font-medium">Médicaments</button>
                    <!-- <button onclick="showPrescriptionTab('historique')" id="tabHistoriqueBtn" class="py-2 px-4 border-b-2 border-transparent text-gray-500 font-medium">Historique prescriptions</button> -->
                </nav>
            </div>

            <!-- Formulaire diagnostic -->
            <div id="diagnosticTab" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                    <!-- <h5 class="font-bold text-gray-700 mb-2"><i class="fas fa-file-medical text-secondary mr-2"></i>Diagnostic</h5> -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-1">Diagnostic / Notes</label>
                        <textarea id="examInstructions" rows="2" class="w-full border border-gray-200 rounded-xl px-3 py-2" placeholder="Précisions pour le laboratoire..."></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button onclick="updateDiagnosis()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl transition flex items-center gap-2">
                            <i class="fas fa-save"></i> Enregistrer le diagnostic
                        </button>
                    </div>
                </div>
            </div>

            <!-- Formulaire prescription examens -->
            <div id="examensTab" class="space-y-4 hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-1">Type d'examen *</label>
                        <select id="examType" class="w-full border border-gray-200 rounded-xl px-3 py-2">
                            <option value="">Sélectionner...</option>
                            <option value="Prise de sang">🩸 Prise de sang</option>
                            <option value="Radiographie">🩻 Radiographie</option>
                            <option value="IRM">🧠 IRM</option>
                            <option value="Scanner">📊 Scanner</option>
                            <option value="Échographie">🫀 Échographie</option>
                            <option value="Electrocardiogramme">❤️ ECG</option>
                            <option value="Test COVID">🦠 Test COVID</option>
                            <option value="Analyse d'urine">💧 Analyse d'urine</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-1">Date souhaitée</label>
                        <input type="date" id="examDate" class="w-full border border-gray-200 rounded-xl px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-1">Instructions / Notes</label>
                    <textarea id="examInstructions" rows="2" class="w-full border border-gray-200 rounded-xl px-3 py-2" placeholder="Précisions pour le laboratoire..."></textarea>
                </div>
                <div class="flex justify-end">
                    <button onclick="prescribeExam()" class="bg-secondary hover:bg-orange-600 text-white px-5 py-2 rounded-xl transition flex items-center gap-2">
                        <i class="fas fa-flask"></i> Prescrire l'examen
                    </button>
                </div>
            </div>
            
            <!-- Formulaire prescription médicaments -->
            <div id="medicamentsTab" class="space-y-4 hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-1">Médicament *</label>
                        <select id="medicationName" class="w-full border border-gray-200 rounded-xl px-3 py-2">
                            <option value="">Sélectionner...</option>
                            <option value="Paracétamol 500mg">Paracétamol 500mg</option>
                            <option value="Ibuprofène 400mg">Ibuprofène 400mg</option>
                            <option value="Amoxicilline 1g">Amoxicilline 1g</option>
                            <option value="Aspirine 100mg">Aspirine 100mg</option>
                            <option value="Oméprazole 20mg">Oméprazole 20mg</option>
                            <option value="Metformine 850mg">Metformine 850mg</option>
                            <option value="Atorvastatine 20mg">Atorvastatine 20mg</option>
                            <option value="Salbutamol inhalateur">Salbutamol inhalateur</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-1">Posologie</label>
                        <input type="text" id="dosage" class="w-full border border-gray-200 rounded-xl px-3 py-2" placeholder="ex: 1 comprimé matin et soir">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-1">Durée (jours)</label>
                        <input type="number" id="duration" class="w-full border border-gray-200 rounded-xl px-3 py-2" placeholder="7">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-1">Quantité</label>
                        <input type="number" id="quantity" class="w-full border border-gray-200 rounded-xl px-3 py-2" placeholder="boîte(s)">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-1">Renouvellement</label>
                        <select id="refill" class="w-full border border-gray-200 rounded-xl px-3 py-2">
                            <option value="0">Aucun</option>
                            <option value="1">1 renouvellement</option>
                            <option value="2">2 renouvellements</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button onclick="prescribeMedication()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-xl transition flex items-center gap-2">
                        <i class="fas fa-prescription"></i> Prescrire le médicament
                    </button>
                </div>
            </div>
            
            <!-- Historique des prescriptions -->
            <!-- <div id="historiqueTab" class="hidden">
                <div class="space-y-3 max-h-96 overflow-y-auto" id="prescriptionsHistoryList">
                    <p class="text-gray-400 text-center py-8">Aucune prescription pour le moment</p>
                </div>
            </div> -->
        </div>
    </div>
</div>

<script>
// ==================== SESSION MÉDECIN CONNECTÉ ====================
const currentDoctor = {
    id: "M1001",
    name: "Dr. Sophie Martin",
    specialty: "Cardiologie"
};

// Afficher les infos médecin
document.getElementById("doctorNameDisplay").innerText = currentDoctor.name;
document.getElementById("doctorSpecialtyDisplay").innerText = currentDoctor.specialty;

// ==================== LISTE DES PATIENTS ASSIGNÉS À CE MÉDECIN ====================
let myPatients = [
    { id: "P1001", lastName: "Dubois", firstName: "Sophie", birthDate: "1985-03-12", gender: "F", phone: "0612345678", address: "12 rue des Lilas, Paris", diagnosis: "Hypertension artérielle", status: "hospitalisé", lastConsultation: "2025-05-01" },
    { id: "P1004", lastName: "Bernard", firstName: "Thomas", birthDate: "1978-09-30", gender: "M", phone: "0623456712", address: "21 rue Victor Hugo, Bordeaux", diagnosis: "Douleurs thoraciques", status: "en consultation", lastConsultation: "2025-05-05" },
    { id: "P1008", lastName: "Simon", firstName: "Antoine", birthDate: "1968-06-14", gender: "M", phone: "0788990011", address: "45 avenue de la République, Nice", diagnosis: "Insuffisance cardiaque", status: "hospitalisé", lastConsultation: "2025-05-03" }
];

// Base de données des prescriptions (stockage localStorage simulé)
let prescriptionsDB = JSON.parse(localStorage.getItem("mediflow_prescriptions")) || {};

function savePrescriptions() {
    localStorage.setItem("mediflow_prescriptions", JSON.stringify(prescriptionsDB));
}

// ==================== AFFICHAGE DES PATIENTS ====================
function renderMyPatients(searchTerm = "") {
    let filtered = myPatients.filter(p => 
        p.firstName.toLowerCase().includes(searchTerm) || 
        p.lastName.toLowerCase().includes(searchTerm) ||
        p.id.toLowerCase().includes(searchTerm)
    );
    
    const tbody = document.getElementById("myPatientsTableBody");
    tbody.innerHTML = "";
    
    if(filtered.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-8 text-gray-400">Aucun patient assigné</td></tr>`;
    } else {
        filtered.forEach(p => {
            const age = calculateAge(p.birthDate);
            let statusClass = "";
            if(p.status === "hospitalisé") statusClass = "bg-blue-100 text-blue-700";
            else if(p.status === "en consultation") statusClass = "bg-amber-100 text-amber-700";
            else if(p.status === "sorti") statusClass = "bg-green-100 text-green-700";
            else statusClass = "bg-gray-100 text-gray-700";
            
            const row = `<tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-3"><span class="font-medium">${p.firstName} ${p.lastName}</span><br><span class="text-xs text-gray-400">${p.id}</span></td>
                <td class="px-6 py-3">${age} ans / ${p.gender === "M" ? "M" : "F"}</td>
                <td class="px-6 py-3 text-sm">${p.diagnosis || "—"}</td>
                <td class="px-6 py-3 text-sm">${p.lastConsultation || "—"}</td>
                <td class="px-6 py-3"><span class="px-2 py-1 rounded-full text-xs font-semibold ${statusClass}">${p.status}</span></td>
                <td class="px-6 py-3 text-center">
                    <button onclick="openConsultation('${p.id}')" class="bg-primary text-white px-3 py-1.5 rounded-lg text-sm hover:bg-blue-800 transition"><i class="fas fa-stethoscope mr-1"></i> Consulter</button>
                </td>
             </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }
    
    // Mise à jour stats
    document.getElementById("myPatientsCount").innerText = myPatients.length;
    document.getElementById("todayConsultations").innerText = myPatients.filter(p => p.status === "en consultation").length;
    
    let allPrescriptions = Object.values(prescriptionsDB).flat();
    document.getElementById("pendingPrescriptions").innerText = allPrescriptions.filter(p => p.status === "active").length;
    document.getElementById("labRequestsCount").innerText = allPrescriptions.filter(p => p.type === "lab" && p.status === "active").length;
}

function calculateAge(birthDateStr) {
    if (!birthDateStr) return "—";
    const birth = new Date(birthDateStr);
    const diff = Date.now() - birth.getTime();
    const ageDate = new Date(diff);
    return Math.abs(ageDate.getUTCFullYear() - 1970);
}

// Recherche en temps réel
document.getElementById("searchPatientInput").addEventListener("input", (e) => {
    renderMyPatients(e.target.value.toLowerCase());
});

// ==================== CONSULTATION PATIENT ====================
let currentConsultPatient = null;

window.openConsultation = (patientId) => {
    currentConsultPatient = myPatients.find(p => p.id === patientId);
    // if(!currentConsultPatient) return;
    
    // Remplir les infos patient
    document.getElementById("consultPatientName").innerHTML = `${currentConsultPatient.firstName} ${currentConsultPatient.lastName}`;
    document.getElementById("consultPatientInfo").innerHTML = `${calculateAge(currentConsultPatient.birthDate)} ans • ${currentConsultPatient.gender === "M" ? "Masculin" : "Féminin"}`;
    document.getElementById("consultPatientId").innerText = currentConsultPatient.id;
    document.getElementById("consultPatientPhone").innerText = currentConsultPatient.phone || "Non renseigné";
    document.getElementById("consultPatientAddress").innerText = currentConsultPatient.address || "Non renseignée";
    // document.getElementById("consultPatientDiagnosis").innerText = currentConsultPatient.diagnosis || "Non renseigné";
    
    let statusClass = "";
    if(currentConsultPatient.status === "hospitalisé") statusClass = "bg-blue-100 text-blue-700";
    else if(currentConsultPatient.status === "en consultation") statusClass = "bg-amber-100 text-amber-700";
    else statusClass = "bg-gray-100 text-gray-700";
    document.getElementById("consultPatientStatus").innerHTML = currentConsultPatient.status;
    document.getElementById("consultPatientStatus").className = `px-3 py-1 rounded-full text-sm font-semibold ${statusClass}`;
    
    // Charger l'historique des prescriptions
    // loadPrescriptionHistory(patientId);
    
    // Réinitialiser les formulaires
    document.getElementById("examType").value = "";
    document.getElementById("examInstructions").value = "";
    document.getElementById("medicationName").value = "";
    document.getElementById("dosage").value = "";
    document.getElementById("duration").value = "";
    document.getElementById("quantity").value = "";
    
    document.getElementById("consultationModal").classList.remove("hidden");
    showPrescriptionTab('diagnostic');
};

// Navigation entre onglets
window.showPrescriptionTab = (tab) => {
    const diagnosticTab = document.getElementById("diagnosticTab");
    const examensTab = document.getElementById("examensTab");
    const medicamentsTab = document.getElementById("medicamentsTab");
    const historiqueTab = document.getElementById("historiqueTab");
    const tabExamensBtn = document.getElementById("tabExamensBtn");
    const tabMedicamentsBtn = document.getElementById("tabMedicamentsBtn");
    
    diagnosticTab.classList.add("hidden");
    examensTab.classList.add("hidden");
    medicamentsTab.classList.add("hidden");
    
    tabDiagnosticBtn.classList.remove("border-primary", "text-primary");
    tabExamensBtn.classList.remove("border-primary", "text-primary");
    tabMedicamentsBtn.classList.remove("border-primary", "text-primary");
    tabExamensBtn.classList.add("border-transparent", "text-gray-500");
    tabMedicamentsBtn.classList.add("border-transparent", "text-gray-500");
    
    if(tab === "examens") {
        examensTab.classList.remove("hidden");
        tabExamensBtn.classList.add("border-primary", "text-primary");
    } else if(tab === "medicaments") {
        medicamentsTab.classList.remove("hidden");
        tabMedicamentsBtn.classList.add("border-primary", "text-primary");
    } else if (tab === "diagnostic") {
        diagnosticTab.classList.remove("hidden");
        tabDiagnosticBtn.classList.add("border-primary", "text-primary");
    }
};

// // Charger historique des prescriptions
// function loadPrescriptionHistory(patientId) {
//     const historyList = document.getElementById("prescriptionsHistoryList");
//     const patientPrescriptions = prescriptionsDB[patientId] || [];
    
//     if(patientPrescriptions.length === 0) {
//         historyList.innerHTML = '<p class="text-gray-400 text-center py-8">Aucune prescription pour le moment</p>';
//         return;
//     }
    
//     historyList.innerHTML = "";
//     patientPrescriptions.reverse().forEach(p => {
//         const date = new Date(p.date).toLocaleDateString('fr-FR');
//         const badgeClass = p.type === "med" ? "bg-emerald-100 text-emerald-700" : "bg-secondary/20 text-secondary";
//         const icon = p.type === "med" ? "💊" : "🔬";
//         const title = p.type === "med" ? "Médicament prescrit" : "Examen prescrit";
        
//         const card = `<div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
//             <div class="flex justify-between items-start">
//                 <div class="flex items-center gap-2">
//                     <span class="text-xl">${icon}</span>
//                     <div>
//                         <p class="font-semibold">${title}</p>
//                         <p class="text-sm">${p.name}</p>
//                         ${p.details ? `<p class="text-xs text-gray-500 mt-1">${p.details}</p>` : ''}
//                     </div>
//                 </div>
//                 <span class="text-xs text-gray-400">${date}</span>
//             </div>
//             <div class="mt-2 flex justify-between items-center">
//                 <span class="text-xs px-2 py-0.5 rounded-full ${badgeClass}">Dr. ${currentDoctor.name}</span>
//                 <span class="text-xs text-gray-400">Prescrit le ${date}</span>
//             </div>
//         </div>`;
//         historyList.insertAdjacentHTML('beforeend', card);
//     });
// }

// Prescrire un examen de laboratoire
window.prescribeExam = () => {
    if(!currentConsultPatient) return;
    const examType = document.getElementById("examType").value;
    if(!examType) {
        alert("Veuillez sélectionner un type d'examen");
        return;
    }
    
    const examDate = document.getElementById("examDate").value;
    const instructions = document.getElementById("examInstructions").value;
    
    const prescription = {
        id: Date.now(),
        type: "lab",
        name: examType,
        date: new Date().toISOString(),
        scheduledDate: examDate,
        instructions: instructions,
        doctor: currentDoctor.name,
        status: "active"
    };
    
    if(!prescriptionsDB[currentConsultPatient.id]) prescriptionsDB[currentConsultPatient.id] = [];
    prescriptionsDB[currentConsultPatient.id].push(prescription);
    savePrescriptions();
    
    alert(`✅ Examen "${examType}" prescrit pour ${currentConsultPatient.firstName} ${currentConsultPatient.lastName}`);
    document.getElementById("examType").value = "";
    document.getElementById("examInstructions").value = "";
    loadPrescriptionHistory(currentConsultPatient.id);
    renderMyPatients(document.getElementById("searchPatientInput")?.value || "");
};

// Prescrire un médicament
window.prescribeMedication = () => {
    if(!currentConsultPatient) return;
    const medicationName = document.getElementById("medicationName").value;
    if(!medicationName) {
        alert("Veuillez sélectionner un médicament");
        return;
    }
    
    const dosage = document.getElementById("dosage").value;
    const duration = document.getElementById("duration").value;
    const quantity = document.getElementById("quantity").value;
    const refill = document.getElementById("refill").value;
    
    let details = `${dosage ? `Posologie: ${dosage}` : ""}`;
    if(duration) details += `${dosage ? " • " : ""}Durée: ${duration} jours`;
    if(quantity) details += `${(dosage || duration) ? " • " : ""}Qté: ${quantity}`;
    if(refill !== "0") details += `${(dosage || duration || quantity) ? " • " : ""}Renouvellement: ${refill}`;
    
    const prescription = {
        id: Date.now(),
        type: "med",
        name: medicationName,
        dosage: dosage,
        duration: duration,
        quantity: quantity,
        refill: refill,
        details: details,
        date: new Date().toISOString(),
        doctor: currentDoctor.name,
        status: "active"
    };
    
    if(!prescriptionsDB[currentConsultPatient.id]) prescriptionsDB[currentConsultPatient.id] = [];
    prescriptionsDB[currentConsultPatient.id].push(prescription);
    savePrescriptions();
    
    alert(`💊 Médicament "${medicationName}" prescrit pour ${currentConsultPatient.firstName} ${currentConsultPatient.lastName}`);
    document.getElementById("medicationName").value = "";
    document.getElementById("dosage").value = "";
    document.getElementById("duration").value = "";
    document.getElementById("quantity").value = "";
    document.getElementById("refill").value = "0";
    loadPrescriptionHistory(currentConsultPatient.id);
    renderMyPatients(document.getElementById("searchPatientInput")?.value || "");
};

// Fermeture modale
document.getElementById("closeConsultModalBtn").onclick = () => {
    document.getElementById("consultationModal").classList.add("hidden");
    currentConsultPatient = null;
};

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
renderMyPatients();
</script>

<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
?>