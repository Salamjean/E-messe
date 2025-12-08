@extends('admin.layouts.template')

@section('content')
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
                    <div class="card-header">
                        <h3 class="card-title">Statistiques de la page d'accueil</h3>
                    </div>
                    <form action="{{ route('content.home-statistics.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="parishes_count">Nombre de paroisses</label>
                                <input type="number" class="form-control @error('parishes_count') is-invalid @enderror"
                                    id="parishes_count" name="parishes_count"
                                    value="{{ old('parishes_count', $statistics->parishes_count ?? '') }}" required>
                                @error('parishes_count')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="users_count">Nombre d'utilisateurs</label>
                                <input type="text" class="form-control @error('users_count') is-invalid @enderror"
                                    id="users_count" name="users_count"
                                    value="{{ old('users_count', $statistics->users_count ?? '') }}"
                                    placeholder="Ex: 50,000+" required>
                                @error('users_count')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="availability">Disponibilité</label>
                                <input type="text" class="form-control @error('availability') is-invalid @enderror"
                                    id="availability" name="availability"
                                    value="{{ old('availability', $statistics->availability ?? '') }}"
                                    placeholder="Ex: 24/7" required>
                                @error('availability')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" style="color: #ffffffff !important;">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#homeStatisticsTable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
                }
            });
        });
    </script>
@endsection
