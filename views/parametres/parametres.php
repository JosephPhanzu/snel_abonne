<?php
$title = "Paramètres | Configuration";
ob_start();
?>

<!-- En-tête -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Paramètres</h1>
        <p class="text-gray-500 text-sm">Configurez l'application, gérez les accès et personnalisez votre espace</p>
    </div>
</div>

<!-- Navigation interne paramètres -->
<div class="border-b border-gray-200">
    <nav class="flex flex-wrap gap-2 -mb-px">
        <button onclick="showTab('general')" id="tabGeneralBtn" class="settings-tab active-tab py-2 px-4 border-b-2 border-primary text-primary font-medium text-sm transition">Informations générales</button>
        <button onclick="showTab('users')" id="tabUsersBtn" class="settings-tab py-2 px-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm transition">Utilisateurs & Accès</button>
        <button onclick="showTab('notifications')" id="tabNotificationsBtn" class="settings-tab py-2 px-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm transition">Notifications</button>
        <button onclick="showTab('security')" id="tabSecurityBtn" class="settings-tab py-2 px-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm transition">Sécurité</button>
        <button onclick="showTab('backup')" id="tabBackupBtn" class="settings-tab py-2 px-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm transition">Sauvegarde</button>
    </nav>
</div>

<!-- TAB 1 : INFORMATIONS GÉNÉRALES -->
<div id="generalTab" class="mt-6 space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-hospital text-primary"></i> Informations de l'établissement</h2>
        <form id="hospitalInfoForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-gray-700 text-sm font-semibold mb-1">Nom de l'établissement</label><input type="text" id="hospitalName" class="w-full border border-gray-200 rounded-xl px-3 py-2.5" value="MediFlow Centre Hospitalier"></div>
                <div><label class="block text-gray-700 text-sm font-semibold mb-1">SIRET</label><input type="text" id="siret" class="w-full border border-gray-200 rounded-xl px-3 py-2.5" value="123 456 789 00012"></div>
                <div><label class="block text-gray-700 text-sm font-semibold mb-1">Téléphone</label><input type="tel" id="hospitalPhone" class="w-full border border-gray-200 rounded-xl px-3 py-2.5" value="+33 1 23 45 67 89"></div>
                <div><label class="block text-gray-700 text-sm font-semibold mb-1">Email</label><input type="email" id="hospitalEmail" class="w-full border border-gray-200 rounded-xl px-3 py-2.5" value="contact@mediflow.fr"></div>
                <div class="md:col-span-2"><label class="block text-gray-700 text-sm font-semibold mb-1">Adresse</label><input type="text" id="hospitalAddress" class="w-full border border-gray-200 rounded-xl px-3 py-2.5" value="12 avenue de la République, 75011 Paris"></div>
            </div>
            <div class="flex justify-end"><button type="submit" class="bg-primary text-white px-5 py-2 rounded-xl hover:bg-blue-800">Enregistrer</button></div>
        </form>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-palette text-secondary"></i> Apparence</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div><label class="block text-gray-700 text-sm font-semibold mb-1">Couleur principale</label><input type="color" id="primaryColor" class="w-full h-12 border rounded-xl cursor-pointer" value="#1E3A8A"></div>
            <div><label class="block text-gray-700 text-sm font-semibold mb-1">Couleur secondaire</label><input type="color" id="secondaryColor" class="w-full h-12 border rounded-xl cursor-pointer" value="#F97316"></div>
            <div><label class="block text-gray-700 text-sm font-semibold mb-1">Thème</label><select id="theme" class="w-full border border-gray-200 rounded-xl px-3 py-2.5"><option value="light">Clair</option><option value="dark">Sombre</option><option value="system">Système</option></select></div>
        </div>
    </div>
</div>

<!-- TAB 2 : UTILISATEURS & ACCÈS -->
<div id="usersTab" class="mt-6 hidden space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-4"><h2 class="text-lg font-bold text-gray-800">Utilisateurs du système</h2><button id="addUserBtn" class="bg-secondary text-white px-4 py-2 rounded-xl text-sm flex items-center gap-2"><i class="fas fa-plus"></i> Ajouter</button></div>
        <div class="overflow-x-auto"><table class="w-full"><thead><tr class="border-b"><th class="text-left py-2">Utilisateur</th><th class="text-left py-2">Rôle</th><th class="text-left py-2">Statut</th><th class="text-left py-2">Actions</th></tr></thead><tbody id="usersList"></tbody></table></div>
    </div>
</div>

<!-- TAB 3 : NOTIFICATIONS -->
<div id="notificationsTab" class="mt-6 hidden space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6"><h2 class="text-lg font-bold text-gray-800 mb-4">Configuration des alertes</h2>
        <div class="space-y-3"><label class="flex items-center gap-3"><input type="checkbox" id="notifAppointments" class="w-4 h-4 text-primary" checked> <span>Nouveaux rendez-vous</span></label>
        <label class="flex items-center gap-3"><input type="checkbox" id="notifPayments" class="w-4 h-4 text-primary" checked> <span>Paiements reçus</span></label>
        <label class="flex items-center gap-3"><input type="checkbox" id="notifHospitalizations" class="w-4 h-4 text-primary" checked> <span>Hospitalisations critiques</span></label>
        <label class="flex items-center gap-3"><input type="checkbox" id="notifReports" class="w-4 h-4 text-primary"> <span>Rapports hebdomadaires</span></label></div>
        <button id="saveNotifBtn" class="mt-4 bg-primary text-white px-5 py-2 rounded-xl">Enregistrer</button>
    </div>
</div>

<!-- TAB 4 : SÉCURITÉ -->
<div id="securityTab" class="mt-6 hidden space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6"><h2 class="text-lg font-bold text-gray-800 mb-4">Changer le mot de passe</h2>
        <form id="passwordForm" class="space-y-4"><div><label>Mot de passe actuel</label><input type="password" id="currentPassword" class="w-full border rounded-xl px-3 py-2.5"></div>
        <div><label>Nouveau mot de passe</label><input type="password" id="newPassword" class="w-full border rounded-xl px-3 py-2.5"></div>
        <div><label>Confirmation</label><input type="password" id="confirmPassword" class="w-full border rounded-xl px-3 py-2.5"></div>
        <button type="submit" class="bg-primary text-white px-5 py-2 rounded-xl">Mettre à jour</button></form>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6"><h2 class="text-lg font-bold text-gray-800 mb-4">Sessions actives</h2><p class="text-gray-500">Vous êtes connecté sur 2 appareils</p><button class="mt-2 text-red-600 text-sm">Déconnecter tous les appareils</button></div>
</div>

<!-- TAB 5 : SAUVEGARDE -->
<div id="backupTab" class="mt-6 hidden space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6 text-center"><i class="fas fa-database text-5xl text-primary mb-4 block"></i><h2 class="text-xl font-bold">Sauvegarde des données</h2><p class="text-gray-500 mb-4">Dernière sauvegarde: 25/04/2025 02:00</p><button id="backupNowBtn" class="bg-secondary text-white px-6 py-2 rounded-xl"><i class="fas fa-cloud-upload-alt mr-2"></i> Sauvegarder maintenant</button></div>
    <div class="bg-white rounded-2xl shadow-sm p-6"><h2 class="font-bold mb-2">Restaurer</h2><input type="file" id="restoreFile" class="w-full border rounded-xl p-2"><button id="restoreBtn" class="mt-2 bg-amber-500 text-white px-4 py-2 rounded-xl">Restaurer</button></div>
</div>

<script>
    function showTab(tab) { ['general','users','notifications','security','backup'].forEach(t => { document.getElementById(t+'Tab').classList.add('hidden'); }); document.getElementById(tab+'Tab').classList.remove('hidden'); document.querySelectorAll('.settings-tab').forEach(btn => { btn.classList.remove('border-primary','text-primary'); btn.classList.add('border-transparent','text-gray-500'); }); document.getElementById('tab'+tab.charAt(0).toUpperCase()+tab.slice(1)+'Btn').classList.add('border-primary','text-primary'); }
    document.getElementById("hospitalInfoForm").addEventListener("submit", (e) => { e.preventDefault(); alert("Informations mises à jour"); });
    document.getElementById("passwordForm").addEventListener("submit", (e) => { e.preventDefault(); alert("Mot de passe modifié"); });
    document.getElementById("backupNowBtn").onclick = () => alert("Sauvegarde initiée..."); document.getElementById("restoreBtn").onclick = () => alert("Restauration démarrée"); document.getElementById("saveNotifBtn").onclick = () => alert("Préférences enregistrées");
    const sidebar = document.getElementById("sidebar"); const overlay = document.getElementById("mobileSidebarOverlay");
    document.getElementById("mobileMenuBtn").onclick = () => { sidebar.classList.remove("-translate-x-full"); overlay.classList.remove("invisible","opacity-0"); overlay.classList.add("opacity-100","visible"); };
    function closeSidebarMobile() { sidebar.classList.add("-translate-x-full"); overlay.classList.remove("opacity-100","visible"); overlay.classList.add("invisible","opacity-0"); }
    overlay.addEventListener("click", closeSidebarMobile);
    const users = [{name:"Dr. Rivera", role:"Admin", status:"Actif"},{name:"Dr. Sophie Martin", role:"Médecin", status:"Actif"}];
    function renderUsers() { const tbody = document.getElementById("usersList"); tbody.innerHTML = ""; users.forEach(u => { tbody.innerHTML += `<tr class="border-b"><td class="py-2">${u.name}</td><td>${u.role}</td><td><span class="text-green-600">●</span> ${u.status}</td><td><button class="text-blue-600">✏️</button> <button class="text-red-600">🗑️</button></td></tr>`; }); }
    renderUsers(); document.getElementById("addUserBtn").onclick = () => alert("Formulaire d'ajout d'utilisateur");
</script>

<?php
$content = ob_get_clean();
require __DIR__ . "/../templete_app/main_templete.php";
?>