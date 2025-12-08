@extends('admin.layouts.template')
@section('content')
    <!-- CSS DataTables & SweetAlert -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary-color: #ffffffff;
            --primary-dark: #f35525;
            --border-radius: 12px;
        }

        .content-header {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-dark));
            color: white;
            padding: 25px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(243, 85, 37, 0.2);
        }

        .content-table {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .table thead {
            background: #f8f9fa;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
        }

        .btn-action {
            margin: 0 3px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.875rem;
        }

        /* Custom color scheme overrides */
        .btn-primary,
        .btn-light {
            background-color: #d4bc8a !important;
            border-color: #d4bc8a !important;
            color: #fffefeff !important;
        }

        .btn-primary:hover,
        .btn-light:hover {
            background-color: #c5ad7b !important;
            border-color: #c5ad7b !important;
        }

        .btn-secondary {
            background-color: #d9d9d9 !important;
            border-color: #d9d9d9 !important;
            color: #000 !important;
        }

        .btn-secondary:hover {
            background-color: #cacaca !important;
            border-color: #cacaca !important;
        }

        .content-header {
            background: #5ea7b5 !important;
        }

        thead th {
            background-color: #5ea7b5 !important;
            
            color: white !important;
        }
    </style>

    <div class="container-fluid">
        <div class="content-header">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-chart-line me-2"></i> Impacts des Avantages</h2>
                <a href="{{ route('content.advantage-impacts.create') }}" class="btn btn-light">
                    <i class="fas fa-plus me-2"></i> Ajouter un impact
                </a>
            </div>
        </div>

        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif

        <div class="content-table p-3">
            <table class="table table-bordered table-striped dt-responsive nowrap" id="advantageImpactsTable"
                style="width:100%">
                <thead>
                    <tr>
                        <th>Ordre</th>
                        <th>Valeur</th>
                        <th>Label</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Les données seront chargées ici par Ajax -->
                </tbody>
            </table>
        </div>
    </div>

@section('js')
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            @if (Session::has('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: '{{ Session::get('success') }}',
                    confirmButtonColor: '#f35525',
                    timer: 3000
                });
            @endif

            $('#advantageImpactsTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('content.advantage-impacts.index') }}",
                    type: 'GET',
                    error: function(xhr, error, code) {
                        console.error(xhr);
                        Swal.fire('Erreur', 'Impossible de charger les données.', 'error');
                    }
                },
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
                },
                columns: [{
                        data: 'order',
                        render: function(data, type, row) {
                            return `<span class="badge bg-primary">${data}</span>`;
                        }
                    },
                    {
                        data: 'value',
                        render: function(data, type, row) {
                            return `<strong>${data}</strong>`;
                        }
                    },
                    {
                        data: 'label'
                    },
                    {
                        data: 'is_active',
                        render: function(data, type, row) {
                            return data ? '<span class="badge bg-success">Actif</span>' :
                                '<span class="badge bg-secondary">Inactif</span>';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        className: 'text-end',
                        render: function(data, type, row) {
                            let editUrl = "{{ route('content.advantage-impacts.edit', ':id') }}"
                                .replace(':id', row.id);
                            let deleteUrl =
                                "{{ route('content.advantage-impacts.destroy', ':id') }}".replace(
                                    ':id', row.id);
                            let csrf = "{{ csrf_token() }}";

                            return `
                                <a href="${editUrl}" class="btn btn-sm btn-warning btn-action">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="${deleteUrl}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr?');">
                                    <input type="hidden" name="_token" value="${csrf}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-danger btn-action">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            `;
                        }
                    }
                ],
                order: [
                    [0, 'asc']
                ] // Order by 'order' column asc
            });
        });
    </script>
@endsection
@endsection
