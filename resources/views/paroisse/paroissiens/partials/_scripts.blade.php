<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialisation de DataTables
        var table = $('#paroissienTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
            },
            ajax: {
                url: "{{ route('paroissien.data') }}",
                data: function(d) {
                    d.sexe = $('#filter_sexe').val();
                    d.situation_matrimoniale = $('#filter_situation').val();
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id',
                    className: 'text-center'
                },
                {
                    data: 'nom_prenom',
                    name: 'nom_prenom',
                    className: 'text-center'
                },
                {
                    data: 'telephone',
                    name: 'telephone',
                    className: 'text-center'
                },
                {
                    data: 'sexe',
                    name: 'sexe',
                    className: 'text-center'
                },
                {
                    data: 'situation_matrimoniale',
                    name: 'situation_matrimoniale',
                    className: 'text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            order: [
                [0, 'desc']
            ],
            drawCallback: function() {
                $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
            }
        });

        // Gestionnaire d'événements pour le bouton Filtrer
        $('#btn_filter').click(function() {
            table.draw();
            updateExportLinks();
        });

        // Gestionnaire pour le bouton Reset
        $('#btn_reset').click(function() {
            $('#filter_sexe').val('');
            $('#filter_situation').val('');
            table.search('').draw();
            updateExportLinks();
        });

        // Fonction pour mettre à jour les liens d'export
        function updateExportLinks() {
            var sexe = $('#filter_sexe').val();
            var sit = $('#filter_situation').val();
            var search = table.search();

            var params = new URLSearchParams({
                sexe: sexe,
                situation_matrimoniale: sit,
                search_term: search
            }).toString();

            var pdfBase = "{{ route('paroissien.export.pdf') }}";
            var excelBase = "{{ route('paroissien.export.excel') }}";

            $('#btn-export-pdf').attr('href', pdfBase + '?' + params);
            $('#btn-export-excel').attr('href', excelBase + '?' + params);
        }

        table.on('search.dt', function() {
            updateExportLinks();
        });
    });
</script>
