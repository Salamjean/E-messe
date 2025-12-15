@extends('admin.layouts.template')

@section('content')
    <!-- CSS DataTables & SweetAlert -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        .btn-primary {
            background-color: #d4bc8a !important;
            border-color: #d4bc8a !important;
            color: #000 !important;
        }

        .btn-primary:hover {
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

        .card-header {
            background-color: #5ea7b5 !important;
            color: white !important;
        }

        thead th {
            background-color: #5ea7b5 !important;
            color: white !important;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Témoignages</h3>
                        <a href="{{ route('content.testimonials.create') }}" class="btn btn-primary"
                            style="background-color: #d4bc8a !important; border-color: #d4bc8a !important; color: #ffffffff !important;">
                            <i class="fas fa-plus"></i> Ajouter un témoignage
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped dt-responsive nowrap" id="testimonialsTable"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Localisation</th>
                                        <th>Message</th>
                                        <th>Note</th>
                                        <th>Affichage</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Les données seront chargées ici par Ajax -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

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
            $('#testimonialsTable').DataTable({
                processing: true,
                serverSide: false, // Client-side pagination for this example since we return full collection
                ajax: {
                    url: "{{ route('content.testimonials.index') }}",
                    type: 'GET',
                    error: function(xhr, error, code) {
                        console.error(xhr);
                        Swal.fire('Erreur', 'Impossible de charger les données.', 'error');
                    }
                },
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
                },
                columns: [{
                        data: 'name'
                    },
                    {
                        data: 'location'
                    },
                    {
                        data: 'message',
                        render: function(data, type, row) {
                            return data && data.length > 50 ? data.substr(0, 50) + '...' : data;
                        }
                    },
                    {
                        data: 'rating',
                        render: function(data, type, row) {
                            let stars = '';
                            for (let i = 1; i <= 5; i++) {
                                stars +=
                                    `<i class="fas fa-star ${i <= data ? 'text-warning' : 'text-muted'}"></i>`;
                            }
                            return stars;
                        }
                    },
                    {
                        data: 'display_on',
                        render: function(data, type, row) {
                            if (data === 'home')
                                return '<span class="badge badge-primary">Accueil</span>';
                            if (data === 'avantages')
                                return '<span class="badge badge-info">Avantages</span>';
                            return '<span class="badge badge-success">Les deux</span>';
                        }
                    },
                    {
                        data: 'is_active',
                        render: function(data, type, row) {
                            return data ? '<span class="badge badge-success">Actif</span>' :
                                '<span class="badge badge-secondary">Inactif</span>';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('content.testimonials.edit', ':id') }}".replace(
                                ':id', row.id);
                            let deleteUrl = "{{ route('content.testimonials.destroy', ':id') }}"
                                .replace(':id', row.id);
                            let csrf = "{{ csrf_token() }}";

                            return `
                                <a href="${editUrl}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="${deleteUrl}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce témoignage ?');">
                                    <input type="hidden" name="_token" value="${csrf}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            `;
                        }
                    }
                ],
                order: [
                    [0, 'desc']
                ] // Optional: Order by Name or Created At if available (row 0 is Name here)
            });
        });
    </script>
@endsection
