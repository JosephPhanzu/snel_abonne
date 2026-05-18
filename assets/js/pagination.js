<script src="https://cdn.tailwindcss.com"></script>

function renderPagination(totalPages, currentPage) {
    let paginationText = '';

    // Bouton "Précédent"
    if (currentPage > 1) {
        paginationText += '<button class="page-link mx-1 my-2 btn btn-sm" data-page="'+(currentPage-1)+'">«</button>';
    }

    // Toujours afficher la première page
    if (currentPage > 3) {
        paginationText += '<button class="page-link mx-1 my-2 btn btn-sm" data-page="1">1</button>';
        if (currentPage > 4) {
            paginationText += '<span class="mx-1">...</span>';
        }
    }

    // Afficher les pages autour de la page courante
    for (let i = Math.max(1, currentPage-2); i <= Math.min(totalPages, currentPage+2); i++) {
        if (i === currentPage) {
            paginationText += '<button class="page-link mx-1 my-2 btn btn-sm active" data-page="'+i+'">'+i+'</button>';
        } else {
            paginationText += '<button class="page-link mx-1 my-2 btn btn-sm" data-page="'+i+'">'+i+'</button>';
        }
    }

    // Toujours afficher la dernière page
    if (currentPage < totalPages - 2) {
        if (currentPage < totalPages - 3) {
            paginationText += '<span class="mx-1">...</span>';
        }
        paginationText += '<button class="page-link mx-1 my-2 btn btn-sm" data-page="'+totalPages+'">'+totalPages+'</button>';
    }

    // Bouton "Suivant"
    if (currentPage < totalPages) {
        paginationText += '<button class="page-link mx-1 my-2 btn btn-sm" data-page="'+(currentPage+1)+'">»</button>';
    }

    $('#pagination').html(paginationText);
}
