@extends('admin.layouts.template')
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
    
    .btn-confirmer {
        background-color: #28a745;
        color: white;
    }
    
    .btn-confirmer:hover {
        background-color: #218838;
        color: white;
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
    
    .preuve-upload {
        border: 2px dashed #ddd;
        padding: 20px;
        text-align: center;
        border-radius: 10px;
        margin: 15px 0;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .preuve-upload:hover {
        border-color: #f35525;
        background-color: #f9f9f9;
    }
    
    .preview-image {
        max-width: 100%;
        max-height: 200px;
        margin-top: 10px;
        display: none;
        border-radius: 5px;
    }
</style>

<div class="retraits-container">
    <!-- En-tête -->
    <div class="retraits-header">
        <div>
            <h1><i class="fas fa-history me-2"></i>Les demandes de retrait</h1>
            <p>Consultez la liste des demandes de retrait initiées par les paroisses !</p>
        </div>
        <div class="user-profile">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('admin')->user()->name) }}&background=f35525&color=fff" alt="Profile">
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Carte de statistiques -->
            <div class="card-modern">
                <div class="card-body-modern">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Les demandes de retraits des paroisses</h5>
                        <a href="{{route('admin.paroisse.history')}}" class="btn-nouveau">
                            <i class="fas fa-history me-2"></i>Historiques
                        </a>
                    </div>
                    
                    <!-- Tableau des retraits -->
                    <div class="table-responsive">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th class="text-center">Référence</th>
                                    <th class="text-center">Paroisse</th>
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
                                    <td class="text-center">{{ $retrait->paroisse->name }}</td>
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
                                            <button type="button" class="btn btn-sm btn-confirmer btn-action" onclick="confirmRetrait({{ $retrait->id }}, '{{ $retrait->methode }}')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-action" onclick="rejectRetrait({{ $retrait->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8">
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
                <div class="detail-label">Paroisse:</div>
                <div class="detail-value">${retrait.paroisse.name}</div>
            </div>
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
    
    // Fonction pour confirmer un retrait
    function confirmRetrait(retraitId, methode) {
        if (methode === 'virement_bancaire') {
            // Pour virement bancaire, demander une preuve
            Swal.fire({
                title: 'Confirmer le virement bancaire',
                html: `
                    <p>Veuillez uploader une preuve du virement bancaire</p>
                    <div class="preuve-upload" id="preuveUpload">
                        <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                        <p>Cliquez pour uploader une image</p>
                        <input type="file" id="preuveFile" accept="image/*" style="display: none;">
                        <img id="previewImage" class="preview-image" src="" alt="Aperçu">
                    </div>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Confirmer le virement',
                cancelButtonText: 'Annuler',
                preConfirm: () => {
                    const fileInput = document.getElementById('preuveFile');
                    if (!fileInput.files || fileInput.files.length === 0) {
                        Swal.showValidationMessage('Veuillez uploader une preuve de virement');
                        return false;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Créer un formulaire et soumettre
                    const formData = new FormData();
                    const fileInput = document.getElementById('preuveFile');
                    formData.append('preuve_virement', fileInput.files[0]);
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    fetch(`/admin/withdrawal/${retraitId}/confirmer`, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Succès!', 'Le retrait a été confirmé avec succès', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Erreur!', data.message || 'Une erreur est survenue', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Erreur!', 'Une erreur est survenue lors de la confirmation', 'error');
                    });
                }
            });
            
            // Gérer l'upload d'image
            document.getElementById('preuveUpload').addEventListener('click', function() {
                document.getElementById('preuveFile').click();
            });
            
            document.getElementById('preuveFile').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('previewImage');
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            // Pour les autres méthodes, simple confirmation
            Swal.fire({
                title: 'Confirmer le retrait',
                text: `Êtes-vous sûr de vouloir confirmer ce retrait ${formatMethod(methode)} ?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, confirmer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Soumettre la confirmation
                    fetch(`/admin/withdrawal/${retraitId}/confirmer`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Succès!', 'Le retrait a été confirmé avec succès', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Erreur!', data.message || 'Une erreur est survenue', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Erreur!', 'Une erreur est survenue lors de la confirmation', 'error');
                    });
                }
            });
        }
    }
    
    // Fonction pour rejeter un retrait
    function rejectRetrait(retraitId) {
        Swal.fire({
            title: 'Rejeter la demande',
            input: 'text',
            inputLabel: 'Raison du rejet (optionnel)',
            inputPlaceholder: 'Entrez la raison du rejet...',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, rejeter',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                const raison = result.value || 'Raison non spécifiée';
                
                // Soumettre le rejet
                fetch(`/admin/withdrawal/${retraitId}/rejeter`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ raison: raison })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Succès!', 'La demande a été rejetée avec succès', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Erreur!', data.message || 'Une erreur est survenue', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Erreur!', 'Une erreur est survenue lors du rejet', 'error');
                });
            }
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