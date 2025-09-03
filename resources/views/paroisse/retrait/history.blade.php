@extends('paroisse.layouts.template')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Ajout de SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    :root {
        --primary: #f35525;
        --dark: #181824;
        --light: #ffffff;
        --gray: #f8f9fa;
        --border-radius: 12px;
        --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }
    
    body {
        background-color: #f9fafb;
        color: var(--dark);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .retraits-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .retraits-header {
        background: linear-gradient(135deg, var(--dark) 0%, #2d2d42 100%);
        color: var(--light);
        border-radius: var(--border-radius);
        padding: 25px 30px;
        margin-bottom: 30px;
        box-shadow: var(--box-shadow);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .retraits-header h1 {
        font-weight: 700;
        margin: 0;
        font-size: 28px;
    }
    
    .retraits-header p {
        margin: 5px 0 0;
        opacity: 0.9;
    }
    
    .user-profile {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid rgba(255, 255, 255, 0.2);
    }
    
    .user-profile img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .card-modern {
        background: var(--light);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        border: none;
        margin-bottom: 25px;
        overflow: hidden;
        transition: var(--transition);
    }
    
    .card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    
    .card-header-modern {
        background: linear-gradient(135deg, var(--primary) 0%, #ff774c 100%);
        color: white;
        padding: 18px 25px;
        font-weight: 600;
        font-size: 18px;
        border: none;
    }
    
    .card-body-modern {
        padding: 30px;
    }
    
    .table-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table-modern th {
        background-color: #f8f9fa;
        padding: 15px;
        font-weight: 600;
        text-align: left;
        border-bottom: 2px solid #eef2f6;
    }
    
    .table-modern td {
        padding: 15px;
        border-bottom: 1px solid #eef2f6;
        vertical-align: middle;
    }
    
    .table-modern tbody tr {
        transition: var(--transition);
    }
    
    .table-modern tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .badge-statut {
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-en-attente {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .badge-traite {
        background-color: #d1ecf1;
        color: #0c5460;
    }
    
    .badge-complete {
        background-color: #d4edda;
        color: #155724;
    }
    
    .badge-rejete {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .solde-card {
        background: linear-gradient(135deg, #2d2d42 0%, var(--dark) 100%);
        color: white;
        border-radius: var(--border-radius);
        padding: 20px;
        display: flex;
        align-items: center;
        margin-bottom: 25px;
    }
    
    .solde-icon {
        background: rgba(255, 255, 255, 0.15);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        font-size: 24px;
    }
    
    .solde-info h3 {
        font-size: 16px;
        margin: 0 0 5px;
        opacity: 0.9;
        font-weight: 500;
    }
    
    .solde-info .montant {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
    }
    
    .solde-info .texte {
        font-size: 13px;
        opacity: 0.8;
        margin: 5px 0 0;
    }
    
    .btn-nouveau {
        background: linear-gradient(135deg, var(--primary) 0%, #ff774c 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 25px;
        font-weight: 600;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-nouveau:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(243, 85, 37, 0.3);
        color: white;
    }
    
    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 14px;
        margin-right: 5px;
    }
    
    .method-badge {
        display: inline-block;
        background: rgba(243, 85, 37, 0.1);
        color: var(--primary);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 0;
    }
    
    .empty-state i {
        font-size: 64px;
        color: #dee2e6;
        margin-bottom: 20px;
    }
    
    .empty-state p {
        color: #6c757d;
        font-size: 18px;
    }
    
    @media (max-width: 768px) {
        .retraits-header {
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }
        
        .user-profile {
            margin-top: 15px;
        }
        
        .solde-card {
            flex-direction: column;
            text-align: center;
        }
        
        .solde-icon {
            margin-right: 0;
            margin-bottom: 15px;
        }
        
        .table-modern {
            display: block;
            overflow-x: auto;
        }
    }
    
    /* Style personnalisé pour le contenu HTML dans SweetAlert2 */
    .swal2-html-container {
        text-align: left !important;
    }
    
    .detail-row {
        display: flex;
        margin-bottom: 10px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    
    .detail-label {
        flex: 1;
        font-weight: 600;
        color: #555;
    }
    
    .detail-value {
        flex: 2;
    }
</style>

<div class="retraits-container">
    <!-- En-tête -->
    <div class="retraits-header">
        <div>
            <h1><i class="fas fa-history me-2"></i>Historique des Retraits</h1>
            <p>Consultez l'historique de vos demandes de retrait, de la paroisse {{ Auth::guard('paroisse')->user()->name }}!</p>
        </div>
        <div class="user-profile">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('paroisse')->user()->name) }}&background=f35525&color=fff" alt="Profile">
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Carte de statistiques -->
            <div class="card-modern">
                <div class="card-body-modern">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Vos demandes de retrait</h5>
                        <a href="{{ route('paroisse.retrait.create') }}" class="btn-nouveau">
                            <i class="fas fa-plus me-2"></i>Nouvelle demande
                        </a>
                    </div>
                    
                    <!-- Carte d'information sur le solde -->
                    <div class="solde-card">
                        <div class="solde-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="solde-info">
                            <h3>Solde disponible</h3>
                            <p class="montant">{{ number_format($soldeDisponible, 0, ',', ' ') }} FCFA</p>
                            <p class="texte">Montant maximum que vous pouvez retirer</p>
                        </div>
                    </div>
                    
                    <!-- Tableau des retraits -->
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
                                            @if($retrait->methode == 'virement_bancaire')
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
                                        @if($retrait->statut == 'en_attente')
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
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-action" onclick="showRetraitDetails({{ $retrait }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($retrait->statut == 'en_attente')
                                            <form action="{{ route('paroisse.retrait.annuler', $retrait->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-action" onclick="confirmAnnulation({{ $retrait->id }})">
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
                    
                    <!-- Pagination -->
                    @if($retraits->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $retraits->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ajout de Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Ajout de SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Fonction pour formater la date
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    // Fonction pour afficher les détails d'un retrait avec SweetAlert2
    function showRetraitDetails(retrait) {
        // Formater le HTML pour les détails
        let detailsHtml = `
            <div class="detail-row">
                <div class="detail-label">Date de demande:</div>
                <div class="detail-value">${formatDate(retrait.created_at)}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Statut:</div>
                <div class="detail-value">${getStatusBadge(retrait.statut)}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Montant:</div>
                <div class="detail-value">${formatCurrency(retrait.montant)} FCFA</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Méthode:</div>
                <div class="detail-value">${formatMethod(retrait.methode)}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Numéro de compte/téléphone:</div>
                <div class="detail-value">${retrait.numero_compte}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Nom du titulaire:</div>
                <div class="detail-value">${retrait.nom_titulaire}</div>
            </div>
        `;
        
        // Ajouter le nom de la banque si disponible
        if (retrait.nom_banque) {
            detailsHtml += `
                <div class="detail-row">
                    <div class="detail-label">Nom de la banque:</div>
                    <div class="detail-value">${retrait.nom_banque}</div>
                </div>
            `;
        }
        
        // Ajouter la date de traitement si disponible
        if (retrait.traite_le) {
            detailsHtml += `
                <div class="detail-row">
                    <div class="detail-label">Traîté le:</div>
                    <div class="detail-value">${formatDate(retrait.traite_le)}</div>
                </div>
            `;
        }
        
        // Afficher le pop-up SweetAlert2
        Swal.fire({
            title: `Détails du retrait #${retrait.reference}`,
            html: detailsHtml,
            icon: 'info',
            width: '600px',
            confirmButtonColor: '#f35525',
            confirmButtonText: 'Fermer'
        });
    }
    
    // Fonction pour formater le badge de statut
    function getStatusBadge(status) {
        const statusText = {
            'en_attente': 'En attente',
            'traite': 'Traité',
            'complete': 'Complété',
            'rejete': 'Rejeté'
        };
        
        const statusClass = {
            'en_attente': 'badge-en-attente',
            'traite': 'badge-traite',
            'complete': 'badge-complete',
            'rejete': 'badge-rejete'
        };
        
        const statusIcon = {
            'en_attente': 'clock',
            'traite': 'cog',
            'complete': 'check-circle',
            'rejete': 'times-circle'
        };
        
        return `<span class="badge-statut ${statusClass[status]}">
            <i class="fas fa-${statusIcon[status]} me-1"></i>${statusText[status]}
        </span>`;
    }
    
    // Fonction pour formater la méthode de retrait
    function formatMethod(method) {
        const methods = {
            'virement_bancaire': 'Virement Bancaire',
            'orange_money': 'Orange Money',
            'mtn_money': 'MTN Money',
            'wave': 'Wave'
        };
        
        return methods[method] || method.charAt(0).toUpperCase() + method.slice(1);
    }
    
    // Fonction pour formater la monnaie
    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-FR').format(amount);
    }
    
    // Fonction pour confirmer l'annulation
    function confirmAnnulation(retraitId) {
        Swal.fire({
            title: 'Confirmer l\'annulation',
            text: "Êtes-vous sûr de vouloir annuler cette demande de retrait ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f35525',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, annuler',
            cancelButtonText: 'Non, garder'
        }).then((result) => {
            if (result.isConfirmed) {
                // Soumettre le formulaire d'annulation
                document.querySelector(`form[action*="${retraitId}"]`).submit();
            }
        });
    }
    
    // Gérer les messages de session (succès/erreur)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#f35525'
        });
    @endif
    
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Erreur!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#f35525'
        });
    @endif
</script>

@endsection