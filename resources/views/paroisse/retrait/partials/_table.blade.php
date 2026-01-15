<div class="table-responsive">
    <table class="table-modern">
        <thead>
            <tr>
                <th class="text-center">Référence</th>
                <th class="text-center">Date</th>
                <th class="text-center">Heure</th>
                <th class="text-center">Montant</th>
                <th class="text-center">Méthode</th>
                <th class="text-center">Statut</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($retraits as $retrait)
                <tr>
                    <td class="text-center">{{ $retrait->reference }}</td>
                    <td class="text-center">{{ $retrait->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $retrait->created_at->format('H:i') }}</td>
                    <td class="text-center">{{ number_format($retrait->montant, 0, ',', ' ') }} FCFA</td>
                    <td class="text-center">
                        <span class="method-badge">
                            @if ($retrait->methode == 'virement_bancaire')
                                Virement Bancaire
                            @elseif($retrait->methode == 'orange_money')
                                Orange Money
                            @elseif($retrait->methode == 'mtn_money')
                                MTN Money
                            @else
                                {{ ucfirst($retrait->methode) }}
                            @endif
                        </span>
                    </td>
                    <td class="text-center">
                        @if ($retrait->statut == 'en_attente')
                            <span class="badge-statut badge-en-attente">
                                <i class="fas fa-clock me-1"></i>En attente
                            </span>
                        @elseif($retrait->statut == 'traite')
                            <span class="badge-statut badge-traite">
                                <i class="fas fa-cog me-1"></i>Traité
                            </span>
                        @elseif($retrait->statut == 'complete')
                            <span class="badge-statut badge-complete">
                                <i class="fas fa-check-circle me-1"></i>Complété
                            </span>
                        @else
                            <span class="badge-statut badge-rejete">
                                <i class="fas fa-times-circle me-1"></i>Rejeté
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-action"
                            onclick="showRetraitDetails({{ $retrait }})">
                            <i class="fas fa-eye"></i>
                        </button>
                        @if ($retrait->statut == 'en_attente')
                            <form id="delete-form-{{ $retrait->id }}"
                                action="{{ route('paroisse.retrait.annuler', $retrait->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger btn-action"
                                    onclick="confirmAnnulation({{ $retrait->id }})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <p>Aucune demande de retrait pour le moment</p>
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
