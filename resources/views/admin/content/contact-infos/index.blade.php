@extends('admin.layouts.template')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #181824;
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
            color: #000 !important;
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
                <h2><i class="fas fa-address-card me-2"></i> Informations de Contact</h2>
                <a href="{{ route('content.contact-infos.create') }}" class="btn btn-light">
                    <i class="fas fa-plus me-2"></i> Ajouter une information
                </a>
            </div>
        </div>

        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif

        <div class="content-table">
            <table class="table table-bordered table-striped" id="contactInfosTable">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Icône</th>
                        <th>Titre</th>
                        <th>Valeur</th>
                        <th>Sous-titre</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($infos as $info)
                        <tr>
                            <td>
                                @if ($info->type == 'email')
                                    <i class="fas fa-envelope text-primary"></i> Email
                                @elseif($info->type == 'phone')
                                    <i class="fas fa-phone text-success"></i> Téléphone
                                @else
                                    <i class="fas fa-map-marker-alt text-danger"></i> Adresse
                                @endif
                            </td>
                            <td><i class="{{ $info->icon }}"></i></td>
                            <td>{{ $info->title }}</td>
                            <td>{{ $info->value }}</td>
                            <td>{{ $info->subtitle ?? '-' }}</td>
                            <td>
                                @if ($info->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('content.contact-infos.edit', $info->id) }}"
                                    class="btn btn-sm btn-warning btn-action">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('content.contact-infos.destroy', $info->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-action"
                                        onclick="return confirm('Êtes-vous sûr?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune information de contact trouvée</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    @push('js')
        <script>
            @if (Session::has('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: '{{ Session::get('success') }}',
                    confirmButtonColor: '#f35525',
                    timer: 3000
                });
            @endif
        </script>
        <script>
            $(document).ready(function() {
                $('#contactInfosTable').DataTable({
                    "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
                }
                });
            });
        </script>
    @endpush
@endsection
