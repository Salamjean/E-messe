@extends('admin.layouts.template')

@section('content')
    <style>
        .btn-primary {
            background-color: #d4bc8a !important;
            border-color: #d4bc8a !important;
            color: #ffffffff !important;
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
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Succès des Paroisses</h6>
                            <a href="{{ route('content.parish-success.create') }}" class="btn btn-primary btn-sm"
                                style="color: white !important">
                                <i class="fas fa-plus"></i> Ajouter un succès
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if (session('success'))
                            <div class="alert alert-success mx-4 mt-3" role="alert">
                                <span class="text-white">{{ session('success') }}</span>
                            </div>
                        @endif

                        <div class="table-responsive p-0">
                            <table class="table table-bordered table-striped" id="parishSuccessTable">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase Text-second text-xxs font-weight-bolder opacity-7">Nom
                                            de la Paroisse</th>
                                        <th class="text-uppercase Text-second text-xxs font-weight-bolder opacity-7 ps-2">
                                            Localisation</th>
                                        <th
                                            class="text-center text-uppercase Text-second text-xxs font-weight-bolder opacity-7">
                                            Utilisateurs Actifs</th>
                                        <th
                                            class="text-center text-uppercase Text-second text-xxs font-weight-bolder opacity-7">
                                            Messes Réservées</th>
                                        <th
                                            class="text-center text-uppercase Text-second text-xxs font-weight-bolder opacity-7">
                                            Augmentation</th>
                                        <th
                                            class="text-center text-uppercase Text-second text-xxs font-weight-bolder opacity-7">
                                            Statut</th>
                                        <th class="Text-second opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stories as $story)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $story->name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $story->location }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="Text-second text-xs font-weight-bold">{{ $story->active_users }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="Text-second text-xs font-weight-bold">{{ $story->masses_reserved }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="badge bg-gradient-success text-dark">{{ $story->participation_increase ?? 'N/A' }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                @if ($story->is_active)
                                                    <span class="badge bg-gradient-success text-dark">Actif</span>
                                                @else
                                                    <span class="badge bg-gradient-secondary text-dark">Inactif</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('content.parish-success.edit', $story) }}"
                                                    class="btn btn-secondary btn-sm" data-toggle="tooltip"
                                                    data-original-title="Éditer">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('content.parish-success.destroy', $story) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce succès?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-primary btn-sm"
                                                        data-toggle="tooltip" data-original-title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty

                                        <p class="text-center py-4"> Aucun succès de paroisse trouvé.</p>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#parishSuccessTable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
                }
            });
        });
    </script>
@endsection
