@extends('admin.layouts.template')

@section('content')
    @include('admin.retrait.partials._styles')

    <div class="container-fluid mt-4">
        <!-- En-tête -->
        <div class="retraits-header">
            <div>
                <h1><i class="fas fa-wallet me-2"></i>Demandes de retrait</h1>
                <p>Gérez les demandes de retrait de fonds initiées par les paroisses.</p>
            </div>
            <div class="user-profile">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('admin')->user()->name) }}&background=f35525&color=fff"
                    alt="Profile">
            </div>
        </div>

        <!-- Statistiques -->
        @include('admin.retrait.partials._kpis')

        <div class="row">
            <div class="col-12">
                <div class="card-modern">
                    <div class="card-body-modern">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0 font-weight-bold">Demandes en attente de traitement</h5>
                            <a href="{{ route('admin.paroisse.history') }}" class="btn-outline-custom">
                                <i class="fas fa-history me-2"></i>Voir l'historique
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th class="text-center">Référence</th>
                                        <th class="text-center">Paroisse</th>
                                        <th class="text-center">Date & Heure</th>
                                        <th class="text-center">Montant</th>
                                        <th class="text-center">Méthode</th>
                                        <th class="text-center">Statut</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($retraits as $retrait)
                                        <tr>
                                            <td class="text-center font-weight-bold text-primary">#{{ $retrait->reference }}
                                            </td>
                                            <td class="text-center">
                                                {{ optional($retrait->paroisse)->name ?? 'Paroisse supprimée' }}</td>
                                            <td class="text-center">
                                                <div class="small">{{ $retrait->created_at->format('d/m/Y') }}</div>
                                                <div class="text-muted small">{{ $retrait->created_at->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ number_format($retrait->montant, 0, ',', ' ') }}</strong>
                                                <small>FCFA</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="method-badge">
                                                    @if ($retrait->methode == 'virement_bancaire')
                                                        <i class="fas fa-university me-1"></i> Virement
                                                    @elseif(in_array($retrait->methode, ['orange_money', 'mtn_money', 'wave']))
                                                        <i class="fas fa-mobile-alt me-1"></i> Mobile
                                                    @else
                                                        {{ ucfirst($retrait->methode) }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge-statut badge-en-attente">
                                                    <i class="fas fa-clock me-1"></i>En attente
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button title="Détails" class="btn-action btn-voir"
                                                    onclick="showRetraitDetails({{ $retrait }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button title="Confirmer" class="btn-action btn-confirmer"
                                                    onclick="confirmRetrait({{ $retrait->id }}, '{{ $retrait->methode }}')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button title="Rejeter" class="btn-action btn-rejeter"
                                                    onclick="rejectRetrait({{ $retrait->id }})">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                <div class="empty-state">
                                                    <i class="fas fa-inbox"></i>
                                                    <p>Aucune demande de retrait en attente</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($retraits->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $retraits->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.retrait.partials._scripts')
@endsection
