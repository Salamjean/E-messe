// Attend que le contenu de la page soit entièrement chargé
document.addEventListener('DOMContentLoaded', function () {
    
    // --- MODIFIÉ : Gestion du Modal d'Information ---
    const eventDetailModal = document.getElementById('eventDetailModal');
    
    if (eventDetailModal) {
        eventDetailModal.addEventListener('show.bs.modal', function (event) {
            const card = event.relatedTarget;
            
            // Extrait toutes les informations des attributs data-*
            const imageUrl = card.getAttribute('data-image-src');
            const title = card.getAttribute('data-title');
            const dates = card.getAttribute('data-dates');
            const location = card.getAttribute('data-location');
            const celebrant = card.getAttribute('data-celebrant');
            const participation = card.getAttribute('data-participation');
            const description = card.getAttribute('data-description');
            
            // Sélectionne les éléments du modal
            const modalImage = eventDetailModal.querySelector('#modal-image');
            const modalTitle = eventDetailModal.querySelector('#modal-title');
            const modalDates = eventDetailModal.querySelector('#modal-dates');
            const modalLocation = eventDetailModal.querySelector('#modal-location');
            const modalCelebrant = eventDetailModal.querySelector('#modal-celebrant');
            const modalParticipation = eventDetailModal.querySelector('#modal-participation');
            const modalDescription = eventDetailModal.querySelector('#modal-description');
            
            // Met à jour le contenu du modal
            modalTitle.textContent = title;
            modalDates.textContent = dates;
            modalLocation.textContent = location;
            modalCelebrant.textContent = celebrant;
            modalParticipation.textContent = participation;
            modalDescription.textContent = description;

            // Gère l'affichage de l'image
            if (imageUrl) {
                modalImage.src = imageUrl;
                modalImage.style.display = 'block';
            } else {
                modalImage.style.display = 'none';
            }
        });
    }

    // Initialiser DataTables si la table existe sur la page
    if (document.getElementById('events-table')) {
        initializeDataTable();
    }
});

/**
 * Initialise le plugin DataTables sur la table des événements.
 */
function initializeDataTable() {
    // Le code de cette fonction reste inchangé
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
    // Le code de cette fonction reste inchangé
    const cardView = document.getElementById('card-view');
    const tableView = document.getElementById('table-view');
    const cardBtn = document.getElementById('card-view-btn');
    const tableBtn = document.getElementById('table-view-btn');
    
    if (viewType === 'card') {
        cardView.style.display = 'block';
        tableView.style.display = 'none';
        cardBtn.classList.add('active');
        tableBtn.classList.remove('active');
    } else {
        cardView.style.display = 'none';
        tableView.style.display = 'block';
        tableBtn.classList.add('active');
        cardBtn.classList.remove('active');
        
        if ($.fn.DataTable.isDataTable('#events-table')) {
            $('#events-table').DataTable().responsive.recalc();
        }
    }
}