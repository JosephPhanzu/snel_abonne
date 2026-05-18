<?php
$title = "Tableau de bord | MediFlow";
ob_start();
?>

<!-- En-tête -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tableau de bord</h1>
        <p class="text-gray-500 text-sm">Vue d'ensemble de l'activité hospitalière</p>
    </div>
    <div class="flex gap-2">
        <select id="periodSelect" class="border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white">
            <option value="today">Aujourd'hui</option>
            <option value="week" selected>Cette semaine</option>
            <option value="month">Ce mois</option>
            <option value="year">Cette année</option>
        </select>
        <button onclick="refreshData()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-xl transition"><i class="fas fa-sync-alt"></i></button>
    </div>
</div>

<!-- Cartes statistiques principales -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-primary hover:shadow-md transition">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-500 text-sm">Patients totaux</p>
                <p id="totalPatients" class="text-3xl font-bold text-gray-800">1,284</p>
                <p class="text-xs text-green-600 mt-1"><i class="fas fa-arrow-up"></i> +12%</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <i class="fas fa-users text-primary text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-emerald-500 hover:shadow-md transition">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-500 text-sm">Médecins</p>
                <p id="totalDoctors" class="text-3xl font-bold text-gray-800">48</p>
                <p class="text-xs text-green-600 mt-1"><i class="fas fa-arrow-up"></i> +3</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-emerald-500/10 flex items-center justify-center">
                <i class="fas fa-user-md text-emerald-500 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-secondary hover:shadow-md transition">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-500 text-sm">Hospitalisations</p>
                <p id="totalHospitalizations" class="text-3xl font-bold text-gray-800">156</p>
                <p class="text-xs text-red-600 mt-1"><i class="fas fa-arrow-up"></i> +5%</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-secondary/10 flex items-center justify-center">
                <i class="fas fa-procedures text-secondary text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-amber-500 hover:shadow-md transition">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-500 text-sm">Diagnostics</p>
                <p id="totalDiagnostics" class="text-3xl font-bold text-gray-800">892</p>
                <p class="text-xs text-green-600 mt-1"><i class="fas fa-arrow-up"></i> +18%</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-amber-500/10 flex items-center justify-center">
                <i class="fas fa-file-alt text-amber-500 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Deuxième ligne de cartes -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-purple-500">
        <div class="flex justify-between items-start">
            <div><p class="text-gray-500 text-sm">Rendez-vous (mois)</p><p id="monthlyAppointments" class="text-2xl font-bold text-gray-800">342</p><p class="text-xs text-green-600"><i class="fas fa-calendar"></i> +23</p></div>
            <div class="w-10 h-10 rounded-full bg-purple-500/10 flex items-center justify-center"><i class="fas fa-calendar-check text-purple-500"></i></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-rose-500">
        <div class="flex justify-between items-start"><div><p class="text-gray-500 text-sm">Urgences (jour)</p><p id="emergenciesToday" class="text-2xl font-bold text-gray-800">24</p><p class="text-xs text-red-600"><i class="fas fa-ambulance"></i> +7</p></div><div class="w-10 h-10 rounded-full bg-rose-500/10 flex items-center justify-center"><i class="fas fa-ambulance text-rose-500"></i></div></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-cyan-500">
        <div class="flex justify-between items-start"><div><p class="text-gray-500 text-sm">Lits disponibles</p><p id="availableBeds" class="text-2xl font-bold text-gray-800">32</p><p class="text-xs text-blue-600"><i class="fas fa-bed"></i> sur 210</p></div><div class="w-10 h-10 rounded-full bg-cyan-500/10 flex items-center justify-center"><i class="fas fa-bed text-cyan-500"></i></div></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-indigo-500">
        <div class="flex justify-between items-start"><div><p class="text-gray-500 text-sm">Taux d'occupation</p><p id="occupancyRate" class="text-2xl font-bold text-gray-800">84.8%</p><p class="text-xs text-green-600"><i class="fas fa-chart-line"></i> +2%</p></div><div class="w-10 h-10 rounded-full bg-indigo-500/10 flex items-center justify-center"><i class="fas fa-chart-pie text-indigo-500"></i></div></div>
    </div>
</div>

<!-- Graphiques circulaires et tendances -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Graphique répartition des patients par statut -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-800">Répartition des patients</h2>
            <span class="text-xs text-gray-400">par statut médical</span>
        </div>
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="relative w-48 h-48">
                <canvas id="patientStatusChart" width="200" height="200" class="w-full h-full"></canvas>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center"><span id="totalPatientsStat" class="text-2xl font-bold text-gray-800">1,284</span><p class="text-xs text-gray-400">patients</p></div>
                </div>
            </div>
            <div class="flex-1 space-y-2">
                <div class="flex justify-between items-center"><span><i class="fas fa-circle text-blue-500 mr-2"></i> Hospitalisés</span><span id="hospitalizedStat" class="font-semibold">342</span><span id="hospitalizedPercent" class="text-gray-400 text-sm">26.6%</span></div>
                <div class="w-full bg-gray-200 rounded-full h-2"><div id="hospitalizedBar" class="bg-blue-500 h-2 rounded-full" style="width: 0%"></div></div>
                <div class="flex justify-between items-center"><span><i class="fas fa-circle text-green-500 mr-2"></i> En consultation</span><span id="consultStat" class="font-semibold">456</span><span id="consultPercent" class="text-gray-400 text-sm">35.5%</span></div>
                <div class="w-full bg-gray-200 rounded-full h-2"><div id="consultBar" class="bg-green-500 h-2 rounded-full" style="width: 0%"></div></div>
                <div class="flex justify-between items-center"><span><i class="fas fa-circle text-yellow-500 mr-2"></i> Sortis</span><span id="dischargedStat" class="font-semibold">398</span><span id="dischargedPercent" class="text-gray-400 text-sm">31.0%</span></div>
                <div class="w-full bg-gray-200 rounded-full h-2"><div id="dischargedBar" class="bg-yellow-500 h-2 rounded-full" style="width: 0%"></div></div>
                <div class="flex justify-between items-center"><span><i class="fas fa-circle text-red-500 mr-2"></i> Urgences</span><span id="emergenciesStat" class="font-semibold">88</span><span id="emergenciesPercent" class="text-gray-400 text-sm">6.9%</span></div>
                <div class="w-full bg-gray-200 rounded-full h-2"><div id="emergenciesBar" class="bg-red-500 h-2 rounded-full" style="width: 0%"></div></div>
            </div>
        </div>
    </div>

    <!-- Graphique répartition par spécialité médicale -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-800">Consultations par spécialité</h2>
            <span class="text-xs text-gray-400">ce mois</span>
        </div>
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="relative w-48 h-48">
                <canvas id="specialtyChart" width="200" height="200" class="w-full h-full"></canvas>
            </div>
            <div class="flex-1 space-y-2 max-h-48 overflow-y-auto">
                <div class="flex justify-between text-sm"><span><i class="fas fa-circle text-primary mr-2"></i> Cardiologie</span><span id="cardioCount" class="font-semibold">124</span></div>
                <div class="flex justify-between text-sm"><span><i class="fas fa-circle text-secondary mr-2"></i> Neurologie</span><span id="neuroCount" class="font-semibold">87</span></div>
                <div class="flex justify-between text-sm"><span><i class="fas fa-circle text-emerald-500 mr-2"></i> Pédiatrie</span><span id="pediatricsCount" class="font-semibold">112</span></div>
                <div class="flex justify-between text-sm"><span><i class="fas fa-circle text-amber-500 mr-2"></i> Orthopédie</span><span id="orthoCount" class="font-semibold">96</span></div>
                <div class="flex justify-between text-sm"><span><i class="fas fa-circle text-purple-500 mr-2"></i> Gynécologie</span><span id="gynecoCount" class="font-semibold">78</span></div>
                <div class="flex justify-between text-sm"><span><i class="fas fa-circle text-rose-500 mr-2"></i> Médecine générale</span><span id="generalCount" class="font-semibold">234</span></div>
            </div>
        </div>
    </div>
</div>

<!-- Troisième ligne : Évolution récente et indicateurs -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Évolution hebdomadaire -->
    <div class="bg-white rounded-2xl shadow-sm p-6 lg:col-span-2">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-800">Évolution des consultations (7 derniers jours)</h2>
            <select id="evolutionType" class="text-sm border rounded-lg px-2 py-1">
                <option value="consultations">Consultations</option>
                <option value="hospitalisations">Hospitalisations</option>
                <option value="urgences">Urgences</option>
            </select>
        </div>
        <div class="h-64 relative">
            <canvas id="evolutionChart" width="600" height="200" class="w-full h-full"></canvas>
        </div>
    </div>

    <!-- Prochains rendez-vous -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-800">Prochains rendez-vous</h2>
            <a href="/rendez-vous" class="text-secondary text-sm hover:underline">Voir tout</a>
        </div>
        <div class="space-y-3 max-h-72 overflow-y-auto" id="appointmentsList">
            <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-xl"><div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center"><i class="fas fa-user text-primary"></i></div><div class="flex-1"><p class="font-medium">Sophie Dubois</p><p class="text-xs text-gray-400">Cardiologie - 09:00</p></div><span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Confirmé</span></div>
            <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-xl"><div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center"><i class="fas fa-user text-secondary"></i></div><div class="flex-1"><p class="font-medium">Lucas Martin</p><p class="text-xs text-gray-400">Neurologie - 10:30</p></div><span class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full">En attente</span></div>
            <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-xl"><div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center"><i class="fas fa-user text-emerald-500"></i></div><div class="flex-1"><p class="font-medium">Emma Petit</p><p class="text-xs text-gray-400">Pédiatrie - 14:00</p></div><span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Confirmé</span></div>
            <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-xl"><div class="w-10 h-10 rounded-full bg-amber-500/10 flex items-center justify-center"><i class="fas fa-user text-amber-500"></i></div><div class="flex-1"><p class="font-medium">Thomas Bernard</p><p class="text-xs text-gray-400">Cardiologie - 15:30</p></div><span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">Urgent</span></div>
        </div>
    </div>
</div>

<!-- Indicateurs supplémentaires -->
<div class="grid grid-cols-1 sm:grid-cols-4 gap-5 mt-6">
    <div class="bg-gradient-to-r from-primary to-blue-700 rounded-2xl p-4 text-white"><div class="flex justify-between"><div><p class="text-white/80 text-sm">Temps moyen d'attente</p><p class="text-2xl font-bold">18<span class="text-sm">min</span></p></div><i class="fas fa-hourglass-half text-3xl text-white/30"></i></div><div class="mt-2 text-xs text-white/70">-3 min vs hier</div></div>
    <div class="bg-gradient-to-r from-secondary to-orange-600 rounded-2xl p-4 text-white"><div class="flex justify-between"><div><p class="text-white/80 text-sm">Satisfaction patient</p><p class="text-2xl font-bold">94<span class="text-sm">%</span></p></div><i class="fas fa-smile text-3xl text-white/30"></i></div><div class="mt-2 text-xs text-white/70">+2% ce mois</div></div>
    <div class="bg-gradient-to-r from-emerald-500 to-emerald-700 rounded-2xl p-4 text-white"><div class="flex justify-between"><div><p class="text-white/80 text-sm">Taux de réadmission</p><p class="text-2xl font-bold">12<span class="text-sm">%</span></p></div><i class="fas fa-hospital-user text-3xl text-white/30"></i></div><div class="mt-2 text-xs text-white/70">-1.5% vs mois préc.</div></div>
    <div class="bg-gradient-to-r from-purple-500 to-purple-700 rounded-2xl p-4 text-white"><div class="flex justify-between"><div><p class="text-white/80 text-sm">Remboursements traités</p><p class="text-2xl font-bold">156</p></div><i class="fas fa-euro-sign text-3xl text-white/30"></i></div><div class="mt-2 text-xs text-white/70">+23 cette semaine</div></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let patientChart, specialtyChart, evolutionChart;

// Données simulées
const statsData = {
    totalPatients: 1284,
    totalDoctors: 48,
    totalHospitalizations: 156,
    totalDiagnostics: 892,
    monthlyAppointments: 342,
    emergenciesToday: 24,
    availableBeds: 32,
    totalBeds: 210,
    patientsByStatus: { hospitalized: 342, consult: 456, discharged: 398, emergencies: 88 },
    consultationsBySpecialty: { cardiology: 124, neurology: 87, pediatrics: 112, orthopedics: 96, gynecology: 78, general: 234 },
    weeklyEvolution: {
        consultations: [42, 38, 45, 52, 48, 56, 61],
        hospitalizations: [18, 22, 20, 25, 23, 28, 31],
        emergencies: [12, 15, 14, 18, 16, 22, 24]
    },
    nextAppointments: [
        { name: "Sophie Dubois", specialty: "Cardiologie", time: "09:00", status: "confirmé" },
        { name: "Lucas Martin", specialty: "Neurologie", time: "10:30", status: "en attente" },
        { name: "Emma Petit", specialty: "Pédiatrie", time: "14:00", status: "confirmé" },
        { name: "Thomas Bernard", specialty: "Cardiologie", time: "15:30", status: "urgent" }
    ]
};

function updateStats() {
    document.getElementById("totalPatients").innerText = statsData.totalPatients;
    document.getElementById("totalDoctors").innerText = statsData.totalDoctors;
    document.getElementById("totalHospitalizations").innerText = statsData.totalHospitalizations;
    document.getElementById("totalDiagnostics").innerText = statsData.totalDiagnostics;
    document.getElementById("monthlyAppointments").innerText = statsData.monthlyAppointments;
    document.getElementById("emergenciesToday").innerText = statsData.emergenciesToday;
    document.getElementById("availableBeds").innerText = statsData.availableBeds;
    const occupancy = ((statsData.totalBeds - statsData.availableBeds) / statsData.totalBeds * 100).toFixed(1);
    document.getElementById("occupancyRate").innerText = occupancy + "%";
    
    const total = statsData.totalPatients;
    const h = statsData.patientsByStatus.hospitalized, c = statsData.patientsByStatus.consult, d = statsData.patientsByStatus.discharged, e = statsData.patientsByStatus.emergencies;
    document.getElementById("totalPatientsStat").innerText = total;
    document.getElementById("hospitalizedStat").innerText = h; document.getElementById("hospitalizedPercent").innerText = ((h/total)*100).toFixed(1)+"%"; document.getElementById("hospitalizedBar").style.width = ((h/total)*100)+"%";
    document.getElementById("consultStat").innerText = c; document.getElementById("consultPercent").innerText = ((c/total)*100).toFixed(1)+"%"; document.getElementById("consultBar").style.width = ((c/total)*100)+"%";
    document.getElementById("dischargedStat").innerText = d; document.getElementById("dischargedPercent").innerText = ((d/total)*100).toFixed(1)+"%"; document.getElementById("dischargedBar").style.width = ((d/total)*100)+"%";
    document.getElementById("emergenciesStat").innerText = e; document.getElementById("emergenciesPercent").innerText = ((e/total)*100).toFixed(1)+"%"; document.getElementById("emergenciesBar").style.width = ((e/total)*100)+"%";
    
    document.getElementById("cardioCount").innerText = statsData.consultationsBySpecialty.cardiology;
    document.getElementById("neuroCount").innerText = statsData.consultationsBySpecialty.neurology;
    document.getElementById("pediatricsCount").innerText = statsData.consultationsBySpecialty.pediatrics;
    document.getElementById("orthoCount").innerText = statsData.consultationsBySpecialty.orthopedics;
    document.getElementById("gynecoCount").innerText = statsData.consultationsBySpecialty.gynecology;
    document.getElementById("generalCount").innerText = statsData.consultationsBySpecialty.general;
}

function initCharts() {
    const ctx1 = document.getElementById('patientStatusChart').getContext('2d');
    patientChart = new Chart(ctx1, {
        type: 'doughnut',
        data: { labels: ['Hospitalisés', 'Consultation', 'Sortis', 'Urgences'], datasets: [{ data: [342, 456, 398, 88], backgroundColor: ['#1E3A8A', '#10B981', '#F59E0B', '#EF4444'], borderWidth: 0 }] },
        options: { cutout: '60%', plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } }, responsive: true, maintainAspectRatio: true }
    });
    const ctx2 = document.getElementById('specialtyChart').getContext('2d');
    specialtyChart = new Chart(ctx2, {
        type: 'pie', data: { labels: ['Cardiologie', 'Neurologie', 'Pédiatrie', 'Orthopédie', 'Gynécologie', 'Médecine générale'], datasets: [{ data: [124, 87, 112, 96, 78, 234], backgroundColor: ['#1E3A8A', '#F97316', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899'] }] },
        options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 9 } } } }, responsive: true, maintainAspectRatio: true }
    });
    const ctx3 = document.getElementById('evolutionChart').getContext('2d');
    evolutionChart = new Chart(ctx3, {
        type: 'line', data: { labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'], datasets: [{ label: 'Consultations', data: [42, 38, 45, 52, 48, 56, 61], borderColor: '#1E3A8A', backgroundColor: 'rgba(30,58,138,0.1)', fill: true, tension: 0.4 }] },
        options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'top' } } }
    });
}

function updateEvolutionChart(type) {
    let data = [];
    if (type === 'consultations') data = statsData.weeklyEvolution.consultations;
    else if (type === 'hospitalisations') data = statsData.weeklyEvolution.hospitalizations;
    else data = statsData.weeklyEvolution.emergencies;
    let labels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
    let colors = { consultations: '#1E3A8A', hospitalisations: '#10B981', urgences: '#EF4444' };
    evolutionChart.data.datasets[0].data = data;
    evolutionChart.data.datasets[0].label = type.charAt(0).toUpperCase() + type.slice(1);
    evolutionChart.data.datasets[0].borderColor = colors[type] || '#1E3A8A';
    evolutionChart.update();
}

document.getElementById('evolutionType').addEventListener('change', (e) => updateEvolutionChart(e.target.value));
function refreshData() { alert("Données actualisées"); }

updateStats();
initCharts();

const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("mobileSidebarOverlay");
if(document.getElementById("mobileMenuBtn")) {
    document.getElementById("mobileMenuBtn").onclick = () => { sidebar.classList.remove("-translate-x-full"); overlay.classList.remove("invisible","opacity-0"); overlay.classList.add("opacity-100","visible"); };
}
function closeSidebarMobile() { sidebar.classList.add("-translate-x-full"); overlay.classList.remove("opacity-100","visible"); overlay.classList.add("invisible","opacity-0"); }
if(overlay) overlay.addEventListener("click", closeSidebarMobile);
</script>

<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
?>