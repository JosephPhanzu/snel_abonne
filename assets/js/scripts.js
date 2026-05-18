$(document).ready(function(){

    let uri = ''
    // Lecture de l'url actuel
    let currentUrl = window.location.href;
    alert('bb');
    
    if (currentUrl.includes('employe')) {
        uri = '/get_employe';
        function tableDatabi (donnees) {
            let i = 1;
            let contenuP = "";
            donnees.map((donne) => {
                contenuP += `
                    <tr>
                        <td>${i++}</td>
                        <td>${donne.nom}</td>
                        <td>${donne.prenom}</td>
                        <td>${donne.telephone}</td>
                        <td style="min-width: 100px">
                            <a type="button" class="btn btn-primary btn-sm mx-1 rounded-3" id="select-bi"  data-code="${donne.code}" title="Ajouter">Séléctionner</a>
                        </td>
                    </tr>
                `;
            })
            $('#table-first').html(contenuP)
            $('#table-first').hide().fadeIn("slow");
        }
    }else if (currentUrl.includes('bus')) {
        uri = '/get_bus';
        function tableDatabi (donnees) {
            let i = 1;
            let contenuP = "";
            donnees.forEach(donne => {
                contenuP += `
                    <tr>
                        <td>${i++}</td>
                        <td>${donne.numero}</td>
                        <td>${donne.nbr_place}</td>
                        <td style="min-width: 100px">
                            <a type="button" class="btn btn-primary btn-sm mx-1 rounded-3" id="select-bi"  data-code="${donne.code}" title="Ajouter">Séléctionner</a>
                        </td>
                    </tr>
                `;
            })
            $('#table-bus').html(contenuP)
            $('#table-bus').hide().fadeIn("slow");
        }
    }else if (currentUrl.includes('trajet')) {
        uri = '/get_trajet';
        function tableDatabi (donnees) {
            let i = 1;
            let contenu = "";
            donnees.map((donne) => {
                contenu += `
                    <tr>
                        <td>${i++}</td>
                        <td>${donne.depart}</td>
                        <td>${donne.arrive}</td>
                        <td>${donne.prix}</td>
                        <td>${donne.date} ${donne.heure}</td>
                        <td>${donne.numero}</td>
                        <td style="min-width: 100px">
                            <a type="button" class="btn btn-primary btn-sm mx-1 rounded-3" id="select-bi"  data-code="${donne.code}" title="Ajouter">Séléctionner</a>
                        </td>
                    </tr>
                `;
            })
            $('#table-trajet').html(contenu)
            $('#table-trajet').hide().fadeIn("slow");
        }
    }else if (currentUrl.includes('vente')) {
        uri = '/get_vente';
        function tableDatabi (donnees) {
            
            let i = 1;
            let contenu = "";
            donnees.map((donne) => {
                contenu += `
                    <tr>
                        <td>${i++}</td>
                        <td>${donne.nom_client} ${donne.prenom}</td>
                        <td>${donne.telephone}</td>
                        <td>${donne.depart}</td>
                        <td>${donne.arrive}</td>
                        <td>${donne.prix}</td>
                        <td>${donne.date} ${donne.heure}</td>
                        <td style="min-width: 100px">
                            <a type="button" class="btn btn-primary btn-sm mx-1 rounded-3" id="select-bi"  data-code="${donne.code}" title="Ajouter">Séléctionner</a>
                        </td>
                    </tr>
                `;
                console.log(donne.nom_client);
            });
            $('#table-vente').html(contenu)
            $('#table-vente').hide().fadeIn("slow");
        }
    }


    const code_agence = $('#code_agence').val();
    const limitP = 10; // ou valeur définie ailleurs
    let currentPageP = 1;

    const getData = async (pageP) => {
        
        const response = await axios.get(uri, {params : { code_agence : code_agence, limit : limitP, page : pageP}})
            
        console.log(response.data.message);
        if (response.data.status === 'success') {
            
            if (Array.isArray(response.data.data)){
                var donnees = Object.values(response.data.data);
                let total = parseInt(response.data.total);
                let totalPages = Math.ceil(total / limitP);
                // let session = response.data.session;
                tableDatabi(donnees);

                renderPaginationbi(totalPages, pageP);

            }else{
                alert("Erreur : Données reçues non valides." + response.data.message + ' ' + response.data.total);
            }
        }
        else{
            $('#table-first').html('<div class="alert alert-warning rounded-3">'+response.data.message+'</div>');
        }
    }

    // La création de la pagination
    const renderPaginationbi = (totalPages, currentPageP) => {
        let paginationText = '';
        for (let i = 1; i<=totalPages; i++) {
            paginationText += '<button class="page-link select-bi mx-1 my-2" data-page="'+i+'">'+i+'</button>';
        }
        $('#pagination-bi').html(paginationText);
        $('#pagination-bi button[data-page="'+ currentPageP +'"]').addClass('active');
    }

    // Au clique de bouton de la pagination
    $(document).on('click', '.select-bi', function(){
        const page = $(this).data('page');
        getData(page);
    })
    getData(currentPageP);

    // sent data
    $(document).on('submit', '#add-pharmaciemtm', async function(e){
        e.preventDefault();
        const formData = new FormData(this);
        let typeSent = $(this).data('title');

        let url = '',
            info = 'info';
        if (typeSent === "employe") {
            url = '/add_user';
        }else if (typeSent === "bus") {
            url = '/add_bus';
            info = 'info-bus';
        }else if (typeSent === "trajet") {
            url = '/add_trajet';
            info = 'info-trajet';
        }else if (typeSent === "vente") {
            url = '/add_vente';
            info = 'info-vente';
        }

        const response = await axios.post(url, formData)
        
        console.log(response.data.message);
        if (response.data.status === "success") {
            $('#'+info).removeClass('d-none alert-danger').addClass('alert-success').hide().fadeIn("slow");
            setTimeout(() => {
                location.reload();
            }, 2000);
        }else{
            $('#'+info).removeClass('d-none alert-success').addClass('alert-danger').hide().fadeIn("slow");
        }
        $('#'+info).html(response.data.message);
        
    })

})