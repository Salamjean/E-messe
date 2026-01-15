// Attend que le contenu de la page soit entièrement chargé
document.addEventListener('DOMContentLoaded', function () {
    
    // --- Gestion du Modal d'Information (Supprimée car les infos sont sur la carte) ---


    // Initialiser DataTables
    if (document.getElementById('events-table')) {
        initializeDataTable();
    }
});

/**
 * Initialise le plugin DataTables sur la table des événements.
 */
function initializeDataTable() {
    if ($.fn.DataTable.isDataTable('#events-table')) {
        return;
    }
    $('#events-table').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' },
        responsive: true,
        order: [[3, 'asc']],
        pageLength: 10,
    });
}

/**
 * Bascule l'affichage entre la vue en cartes et la vue en tableau.
 * @param {'card' | 'table'} viewType Le type de vue à afficher.
 */
function toggleView(viewType) {
    const cardView = document.getElementById('card-view');
    const tableView = document.getElementById('table-view');
    const cardBtn = document.getElementById('card-view-btn');
    const tableBtn = document.getElementById('table-view-btn');
    
    if (viewType === 'card') {
        if(cardView) cardView.style.display = 'block';
        if(tableView) tableView.style.display = 'none';
        if(cardBtn) cardBtn.classList.add('active');
        if(tableBtn) tableBtn.classList.remove('active');
    } else {
        if(cardView) cardView.style.display = 'none';
        if(tableView) tableView.style.display = 'block';
        if(tableBtn) tableBtn.classList.add('active');
        if(cardBtn) cardBtn.classList.remove('active');
        
        if ($.fn.DataTable.isDataTable('#events-table')) {
            $('#events-table').DataTable().responsive.recalc();
        } else {
            initializeDataTable();
        }
    }
}