$(document).ready(function () {
    // --- Vérification des dépendances ---
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
            data: function (d) {
                d.filter = $('.btn-filter.active').data('filter');
            },
            error: function (xhr, error, thrown) {
                console.error('Erreur DataTables AJAX:', error, thrown);
                showAlert('danger', 'Erreur lors du chargement des données: ' + error);
            }
        },
        columns: [
            { 
                data: 'checkbox', 
                name: 'checkbox', 
                orderable: false, 
                searchable: false, 
                className: 'text-center',
                render: function(data, type, row) {
                    return `<input type="checkbox" class="event-checkbox form-check-input" value="${row.id}">`;
                }
            },
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
                render: function (data, type, row) {
                    const isTermine = row.statut === 'Terminé';
                    const editButtonDisabled = isTermine ? 'disabled' : '';
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
        language: {
            processing: "Traitement en cours...",
            search: "Rechercher&nbsp;:",
            lengthMenu: "Afficher _MENU_ éléments",
            info: "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
            infoEmpty: "Affichage de l'élément 0 à 0 sur 0 élément",
            infoFiltered: "(filtré de _MAX_ éléments au total)",
            infoPostFix: "",
            loadingRecords: "Chargement en cours...",
            zeroRecords: "Aucun élément à afficher",
            emptyTable: "Aucune donnée disponible dans le tableau",
            paginate: {
                first: "Premier",
                previous: "Précédent",
                next: "Suivant",
                last: "Dernier"
            },
            aria: {
                sortAscending: ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre décroissant"
            }
        },
        pageLength: 10,
        initComplete: function () {
            console.log("✅ DataTables initialisé avec succès");
        }
    });

    // --- Gestion des filtres ---
    $('.btn-filter').on('click', function() {
        $('.btn-filter').removeClass('active');
        $(this).addClass('active');
        table.ajax.reload();
    });

    // --- Fermeture auto des alertes ---
    setTimeout(() => $('.alert').alert('close'), 5000);

    // --- Ouverture du modal d’ajout ---
    $('#addEventBtn').on('click', function () {
        $('#eventForm')[0].reset();
        $('#event_id').val('');
        $('#formMethod').val('POST');
        $('#eventModalLabel').html('<i class="material-icons align-middle me-2">event_available</i> Ajouter un événement');
        // Vider le select2
        $('#type_event').val(null).trigger('change');
        
        // Set form action for store
        $('#eventForm').attr('action', window.eventRoutes.store);

        $('#eventModal').modal('show');
    });

    // --- Édition d’un événement ---
    $('#eventsTable').on('click', '.editBtn', function () {
        var btn = $(this);
        if(btn.attr('disabled')) return; // Prevent if disabled

        let id = btn.data('id');
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

                // IMPORTANT: Set method to PUT for update
                $('#formMethod').val('PUT');
                
                // IMPORTANT: Set form action to the correct update URL
                $('#eventForm').attr('action', window.eventRoutes.update.replace(':id', id));

                $('#eventModalLabel').html('<i class="material-icons align-middle me-2">edit_calendar</i> Modifier l\'événement');
                $('#eventModal').modal('show');
            },
            error: function (xhr) {
                showAlert('danger', 'Erreur lors du chargement : ' + xhr.responseText);
            }
        });
    });

    // --- Soumission du formulaire ---
    $('#eventForm').on('submit', function (e) {
        e.preventDefault();
        
        var form = this;
        var formData = new FormData(form);
        var url = $(form).attr('action');
        var method = $('#formMethod').val(); // POST or PUT

        // Laravel requires _method field for PUT/PATCH/DELETE in FormData/POST requests
        if (method === 'PUT') {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST', // Always POST, let Laravel handle _method
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#eventModal').modal('hide');
                table.ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: response.message || 'Opération réussie !',
                    timer: 3000,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                var errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                var msg = 'Une erreur est survenue.';
                if (errors) {
                     msg = Object.values(errors).flat().join('\n');
                } else if(xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: msg
                });
            }
        });
    });

    // --- Suppression d’un événement ---
    $('#eventsTable').on('click', '.deleteBtn', function () {
        let id = $(this).data('id');
        let url = window.eventRoutes.destroy.replace(':id', id);

        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Cette action est irréversible !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST', // POST with _method=DELETE
                    data: {
                        _method: 'DELETE',
                        _token: window.eventRoutes.csrf
                    },
                    success: function (response) {
                        table.ajax.reload();
                        Swal.fire(
                            'Supprimé !',
                            response.message || 'L\'événement a été supprimé.',
                            'success'
                        );
                        // Hide selectAll checked state
                        $('#selectAll').prop('checked', false);
                         $('#bulkDeleteBtn').hide();
                    },
                    error: function (xhr) {
                        Swal.fire(
                            'Erreur !',
                            'Une erreur est survenue lors de la suppression.',
                            'error'
                        );
                    }
                });
            }
        });
    });
    
    // --- Gestion de la sélection multiple (Bulk Delete) ---
    
    // Select All
    $('#selectAll').on('click', function() {
        var checked = this.checked;
        $('.event-checkbox').each(function() {
            this.checked = checked;
        });
        toggleBulkDeleteBtn();
    });

    // Individual Checkbox
    $('#eventsTable').on('change', '.event-checkbox', function() {
        if(!this.checked) {
            $('#selectAll').prop('checked', false);
        }
        // If all are checked, check selectAll
        if ($('.event-checkbox:checked').length === $('.event-checkbox').length && $('.event-checkbox').length > 0) {
            $('#selectAll').prop('checked', true);
        }
        toggleBulkDeleteBtn();
    });

    function toggleBulkDeleteBtn() {
        var count = $('.event-checkbox:checked').length;
        if(count > 0) {
            $('#bulkDeleteBtn').show().text('Supprimer (' + count + ')');
        } else {
            $('#bulkDeleteBtn').hide();
        }
    }

    // Bulk Delete Action
    $('#bulkDeleteBtn').on('click', function() {
        var ids = [];
        $('.event-checkbox:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length === 0) return;

        Swal.fire({
            title: 'Confirmation de suppression',
            text: "Voulez-vous vraiment supprimer les " + ids.length + " événements sélectionnés ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer tout !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: window.eventRoutes.bulkDestroy,
                    type: 'POST',
                    data: {
                        ids: ids,
                        _token: window.eventRoutes.csrf
                    },
                    success: function (response) {
                        table.ajax.reload();
                        $('#selectAll').prop('checked', false);
                        $('#bulkDeleteBtn').hide();
                        Swal.fire(
                            'Supprimé !',
                            response.message || 'Les événements ont été supprimés.',
                            'success'
                        );
                    },
                    error: function (xhr) {
                        Swal.fire(
                            'Erreur !',
                            xhr.responseJSON.message || 'Une erreur est survenue.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Fonction utilitaire pour les alertes
    function showAlert(type, message) {
        // Implementation simple de l'alerte bootstrap si non existante
        // Ou utiliser SweetAlert
       Swal.fire({
            icon: type === 'danger' ? 'error' : type,
            title: type === 'danger' ? 'Erreur' : 'Info',
            text: message
        });
    }

    // --- Initialisation de Select2 ---
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