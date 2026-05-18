document.addEventListener('DOMContentLoaded', function(){
    const dateInput = document.getElementById('reportDate');
    const pharmaSelect = document.getElementById('pharmaSelect');
    const btnGenerate = document.getElementById('btnGenerate');
    const btnExport = document.getElementById('btnExport');

    // set default date to today
    const today = new Date().toISOString().slice(0,10);
    dateInput.value = today;

    // load pharmacies
    axios.get('/get_pharmacie').then(res=>{
        if (res.data && res.data.data) {
            res.data.data.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.code;
                opt.textContent = p.nom_pharmacie || p.nom || p.name || p.code;
                pharmaSelect.appendChild(opt);
            });
        }
    }).catch(()=>{});

    function renderReport(data){
        document.getElementById('rDate').textContent = data.date || '';
        document.getElementById('rTotal').textContent = data.total_sales || 0;
        document.getElementById('rCount').textContent = data.invoices_count || 0;

        const tbody = document.querySelector('#topProducts tbody');
        tbody.innerHTML = '';
        (data.top_products || []).forEach(p=>{
            const tr = document.createElement('tr');
            tr.innerHTML = `<td>${p.name}</td><td>${p.qty}</td><td>${p.sales}</td>`;
            tbody.appendChild(tr);
        });
    }

    btnGenerate.addEventListener('click', ()=>{
        const date = dateInput.value;
        const code_pharmacie = pharmaSelect.value;
        axios.get('/get_daily_report', { params: { date, code_pharmacie } })
            .then(res=>{
                if (res.data && res.data.status === 'success') {
                    renderReport(res.data.data);
                } else {
                    alert(res.data.message || 'Erreur');
                }
            }).catch(err=>{ alert('Erreur serveur'); });
    });

    btnExport.addEventListener('click', ()=>{
        const rows = [];
        const date = document.getElementById('rDate').textContent;
        rows.push(['date','total_sales','invoices_count']);
        rows.push([date, document.getElementById('rTotal').textContent, document.getElementById('rCount').textContent]);
        rows.push([]);
        rows.push(['Top Products']);
        rows.push(['name','qty','sales']);
        document.querySelectorAll('#topProducts tbody tr').forEach(tr=>{
            const cols = Array.from(tr.children).map(td=>td.textContent.replace(/\n|\r/g,''));
            rows.push(cols);
        });

        const csvContent = rows.map(e=>e.join(',')).join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `daily_report_${date || new Date().toISOString().slice(0,10)}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});
