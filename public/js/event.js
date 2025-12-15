$(document).ready(function () {
    // --- Vérification des dépendances (inchangé) ---
    function checkDependencies() {
        if (typeof $ === 'undefined') {
            console.error("❌ jQuery n'est pas chargé !");
            return false;
        }
        if (!$.fn.DataTable) {
            console.error("❌ DataTables n'est pas chargé !");
            return false;
        }
        return true;
    }

    if (!checkDependencies()) {
        showAlert('danger', "Erreur : les bibliothèques nécessaires ne sont pas chargées correctement.");
        return;
    }

    console.log("✅ Initialisation de DataTables...");

    let table = $('#eventsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: {
            url: window.eventRoutes.data,
            type: 'GET',
            error: function (xhr, error, thrown) {
                console.error('Erreur DataTables AJAX:', error, thrown);
                showAlert('danger', 'Erreur lors du chargement des données: ' + error);
            }
        },
        columns: [
            { data: 'id', name: 'id', className: 'text-start' },
            { data: 'titre', name: 'titre', className: 'text-start' },
            { data: 'type_event', name: 'type_event', className: 'text-start' },
            { data: 'date_debut', name: 'date_debut', className: 'text-start' },
            { data: 'date_fin', name: 'date_fin', className: 'text-start' },
            { data: 'lieu', name: 'lieu', className: 'text-start' },
            { data: 'celebrant', name: 'celebrant', className: 'text-start' },
            {
                data: 'statut',
                name: 'statut',
                className: 'text-start',
                render: function (data) {
                    let badgeClass = {
                        "Prévu": "info",
                        "En cours": "primary",
                        "Terminé": "success",
                        "Annulé": "danger"
                    }[data] || 'secondary';
                    return `<span class="badge bg-${badgeClass}">${data}</span>`;
                }
            },
            {
                data: 'id',
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center',
                // MODIFICATION : Logique pour désactiver le bouton "Modifier"
                render: function (data, type, row) { // 'row' contient toutes les données de la ligne
                    // Vérifier si l'événement est terminé
                    const isTermine = row.statut === 'Terminé';
                    // Ajouter l'attribut 'disabled' si c'est le cas
                    const editButtonDisabled = isTermine ? 'disabled' : '';
                    // Changer le message au survol si le bouton est désactivé
                    const editButtonTitle = isTermine ? 'Modification impossible, l\'événement est terminé' : 'Modifier';

                    return `
                        <div class="btn-group d-flex justify-content-center gap-2" role="group">
                            <button class="btn btn-sm btn-outline-warning editBtn"
                                    data-id="${data}"
                                    title="${editButtonTitle}"
                                    ${editButtonDisabled}>
                                <i class="material-icons">edit</i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger deleteBtn" data-id="${data}" title="Supprimer">
                                <i class="material-icons">delete</i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        language: { /* ... (votre configuration de langue, inchangée) ... */ },

        pageLength: 10,
        initComplete: function () {
            console.log("✅ DataTables initialisé avec succès");
        }
    });

    // --- Fermeture auto des alertes (inchangé) ---
    setTimeout(() => $('.alert').alert('close'), 5000);

    // --- Ouverture du modal d’ajout (inchangé) ---
    $('#addEventBtn').on('click', function () {
        $('#eventForm')[0].reset();
        $('#event_id').val('');
        $('#formMethod').val('POST');
        $('#eventModalLabel').html('<i class="material-icons align-middle me-2">event_available</i> Ajouter un événement');
        // Vider le select2
        $('#type_event').val(null).trigger('change');
        $('#eventModal').modal('show');
    });

    // --- Édition d’un événement (inchangé, mais gère les nouvelles dates) ---
    $('#eventsTable').on('click', '.editBtn', function () {
        let id = $(this).data('id');
        let url = window.eventRoutes.show.replace(':id', id);

        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
                $('#event_id').val(data.id);
                $('#titre').val(data.titre);
                $('#lieu').val(data.lieu);
                $('#celebrant').val(data.celebrant);
                $('#description').val(data.description);
                $('#participation_frais').val(data.participation_frais ? parseFloat(data.participation_frais) : '');

                // Gère la sélection dans Select2
                // Si le type existe dans la liste, on le sélectionne, sinon on crée une nouvelle option
                if ($('#type_event option[value="' + data.type_event + '"]').length) {
                    $('#type_event').val(data.type_event).trigger('change');
                } else {
                    var newOption = new Option(data.type_event, data.type_event, true, true);
                    $('#type_event').append(newOption).trigger('change');
                }

                if (data.date_debut) {
                    $('#date_debut').val(new Date(data.date_debut).toISOString().slice(0, 16));
                }
                if (data.date_fin) {
                    $('#date_fin').val(new Date(data.date_fin).toISOString().slice(0, 16));
                }

                $('#formMethod').val('PUT');
                $('#eventModalLabel').html('<i class="material-icons align-middle me-2">edit_calendar</i> Modifier l\'événement');
                $('#eventModal').modal('show');
            },
            error: function (xhr) {
                showAlert('danger', 'Erreur lors du chargement : ' + xhr.responseText);
            }
        });
    });

    // --- Soumission du formulaire (inchangé) ---
    $('#eventForm').on('submit', function (e) { /* ... (code inchangé) ... */ });

    // --- Suppression d’un événement (inchangé) ---
    $('#eventsTable').on('click', '.deleteBtn', function () { /* ... (code inchangé) ... */ });

    // --- Fonction utilitaire pour les alertes (inchangé) ---
    function showAlert(type, message) { /* ... (code inchangé) ... */ }

    // --- Initialisation de Select2 (inchangé) ---
    $('#type_event').select2({
        dropdownParent: $('#eventModal'),
        placeholder: "Sélectionner ou saisir un type",
        allowClear: true,
        width: '100%',
        tags: true,
        createTag: function(params) {
            return {
                id: params.term,
                text: params.term,
                newOption: true
            };
        }
    });
});