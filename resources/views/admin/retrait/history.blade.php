@extends('admin.layouts.template')

@section('content')
    @include('admin.retrait.partials._styles')

    <div class="retraits-container">
        <!-- En-tête -->
        <div class="retraits-header">
            <div>
                <h1><i class="fas fa-history me-2"></i>Historique des retraits</h1>
                <p>Consultez l'historique complet des demandes de retrait traitées et rejetées.</p>
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
                            <h5 class="mb-0 font-weight-bold">Historique des transactions</h5>
                            <a href="{{ route('admin.paroisse.index') }}" class="btn-nouveau">
                                <i class="fas fa-arrow-left me-2"></i>Retour aux demandes
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
                                            <td class="text-center font-weight-bold">#{{ $retrait->reference }}</td>
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
                                                @if ($retrait->statut == 'traite' || $retrait->statut == 'complete')
                                                    <span class="badge-statut badge-complete">
                                                        <i class="fas fa-check-circle me-1"></i>Payé
                                                    </span>
                                                @elseif($retrait->statut == 'rejete')
                                                    <span class="badge-statut badge-rejete">
                                                        <i class="fas fa-times-circle me-1"></i>Rejeté
                                                    </span>
                                                @else
                                                    <span class="badge-statut badge-traite">
                                                        <i class="fas fa-cog me-1"></i>{{ ucfirst($retrait->statut) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button title="Détails" class="btn-action btn-voir"
                                                    onclick="showRetraitDetails({{ $retrait }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if ($retrait->statut == 'rejete')
                                                    <button title="Raison du rejet" class="btn-action btn-rejeter"
                                                        onclick="Swal.fire('Raison du rejet', '{{ addslashes($retrait->raison_rejet) }}', 'info')">
                                                        <i class="fas fa-comment-alt"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                <div class="empty-state">
                                                    <i class="fas fa-folder-open"></i>
                                                    <p>Aucun historique trouvé</p>
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
