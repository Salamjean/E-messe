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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">FAQs des Paroisses</h3>
                        <a href="{{ route('content.parish-faqs.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter une FAQ
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

                        @if ($faqs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="parishFaqsTable">
                                    <thead>
                                        <tr>
                                            <th>Ordre</th>
                                            <th>Question</th>
                                            <th>Réponse</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($faqs as $faq)
                                            <tr>
                                                <td>{{ $faq->order }}</td>
                                                <td>{{ Str::limit($faq->question, 50) }}</td>
                                                <td>{{ Str::limit($faq->answer, 80) }}</td>
                                                <td>
                                                    @if ($faq->is_active)
                                                        <span class="badge badge-success">Actif</span>
                                                    @else
                                                        <span class="badge badge-secondary">Inactif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('content.parish-faqs.edit', $faq) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('content.parish-faqs.destroy', $faq) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette FAQ ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Aucune FAQ trouvée.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#parishFaqsTable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
                }
            });
        });
    </script>
@endsection
