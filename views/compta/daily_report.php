<?php
$title = "Rapport journalier";
$js = "/assets/js/report.js";

ob_start();
?>

<div class="report-box">
    <h2>Rapport de ventes journalier</h2>
    <div>
        <label>Date: <input type="date" id="reportDate" value=""></label>
        <label>Pharmacie: <select id="pharmaSelect"><option value="">Toutes</option></select></label>
        <button id="btnGenerate">Générer</button>
        <button id="btnExport">Exporter CSV</button>
    </div>

    <div id="summary" style="margin-top:16px;">
        <p><strong>Date:</strong> <span id="rDate"></span></p>
        <p><strong>Total ventes:</strong> <span id="rTotal">0</span></p>
        <p><strong>Nombre factures:</strong> <span id="rCount">0</span></p>
    </div>

    <h3>Top produits</h3>
    <table id="topProducts" class="table">
        <thead><tr><th>Produit</th><th>Quantité</th><th>Ventes</th></tr></thead>
        <tbody></tbody>
    </table>
</div>
<!-- <script src="" defer></script> -->
<style> .report-box{max-width:900px;margin:20px auto;padding:16px;background:#fff;border:1px solid #e5e5e5;} </style>

<?php
$content = ob_get_clean();

require_once __DIR__ . '/../templete_app/main_templete.php';