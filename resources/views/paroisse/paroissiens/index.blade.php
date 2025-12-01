@extends('paroisse.layouts.template')

@section('content')
<div class="container mt-4">
    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Gestion des Paroissiens</h5>
            <div>
                <!-- Boutons d'export HORS du DataTable -->
                <a href="{{ route('paroissien.export.pdf') }}" class="btn btn-danger"><i class="fas fa-file-pdf"></i> PDF</a>
                <a href="{{ route('paroissien.export.excel') }}" class="btn btn-success"><i class="fas fa-file-excel"></i> Excel</a>
                <a href="{{ route('paroissien.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nouveau</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="paroissienTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom & Prénoms</th>
                        <th>Téléphone</th>
                        <th>Situation Matri.</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- <script src="{{ asset('DataTables/dataTables.min.js') }}"></script> --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#paroissienTable').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
                },
                ajax: "{{ route('paroissien.data') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'nom_prenom', name: 'nom_prenom' },
                    { data: 'telephone', name: 'telephone' },
                    { data: 'situation_matrimoniale', name: 'situation_matrimoniale' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush