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
    
    .retrait-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .retrait-header {
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
    
    .retrait-header h1 {
        font-weight: 700;
        margin: 0;
        font-size: 28px;
    }
    
    .retrait-header p {
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
    
    .form-label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
    }
    
    .form-control-modern {
        border: 2px solid #eef2f6;
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 16px;
        transition: var(--transition);
    }
    
    .form-control-modern:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.25rem rgba(243, 85, 37, 0.25);
    }
    
    .info-additionnelle {
        background: #f8f9fa;
        border-left: 4px solid var(--primary);
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
    }
    
    .info-additionnelle h6 {
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .btn-retour {
        background: #f8f9fa;
        color: var(--dark);
        border: 2px solid #eef2f6;
        border-radius: 10px;
        padding: 12px 25px;
        font-weight: 600;
        transition: var(--transition);
    }
    
    .btn-retour:hover {
        background: #e9ecef;
        color: var(--dark);
    }
    
    .btn-soumettre {
        background: linear-gradient(135deg, var(--primary) 0%, #ff774c 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 25px;
        font-weight: 600;
        transition: var(--transition);
    }
    
    .btn-soumettre:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(243, 85, 37, 0.3);
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
        margin-top: 10px;
    }
    
    @media (max-width: 768px) {
        .retrait-header {
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
    }
</style>

<div class="retrait-container">
    <!-- En-tête -->
    <div class="retrait-header">
        <div>
            <h1><i class="fas fa-money-bill-wave me-2"></i>Demande de Retrait</h1>
            <p>Gérez vos retraits de fonds, {{ Auth::guard('paroisse')->user()->nom }}!</p>
        </div>
        <div class="user-profile">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('paroisse')->user()->name) }}&background=f35525&color=fff" alt="Profile">
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Carte de formulaire -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <i class="fas fa-pen-to-square me-2"></i>Formulaire de Demande de Retrait
                </div>
                <div class="card-body-modern">
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

                    <!-- Formulaire de retrait -->
                    <form id="retraitForm" method="POST" action="{{ route('paroisse.retrait.request') }}">
                        @csrf
                        
                        <!-- Montant à retirer -->
                        <div class="mb-4">
                            <label for="montant" class="form-label">Montant à retirer (FCFA) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-modern" id="montant" name="montant" 
                                   required min="1000" max="{{ $soldeDisponible  }}" 
                                   placeholder="Entrez le montant que vous souhaitez retirer">
                            <div class="form-text text-muted mt-2">Le montant minimum de retrait est de 1 000 FCFA</div>
                        </div>
                        
                        <!-- Méthode de retrait -->
                        <div class="mb-4">
                            <label for="methode" class="form-label">Méthode de retrait <span class="text-danger">*</span></label>
                            <select class="form-select form-control-modern" id="methode" name="methode" required>
                                <option value="">Sélectionnez une méthode de retrait</option>
                                <option value="wave">Wave</option>
                                <option value="orange_money">Orange Money</option>
                                <option value="mtn_money">MTN Money</option>
                                <option value="virement_bancaire">Virement Bancaire</option>
                            </select>
                        </div>
                        
                        <!-- Numéro de compte/téléphone -->
                        <div class="mb-4">
                            <label for="numero_compte" class="form-label">Numéro de compte / Téléphone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-modern" id="numero_compte" name="numero_compte" required placeholder="Entrez le numéro...">
                        </div>
                        
                        <!-- Nom du titulaire -->
                        <div class="mb-4">
                            <label for="nom_titulaire" class="form-label">Nom du titulaire du compte <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-modern" id="nom_titulaire" name="nom_titulaire" 
                                   required placeholder="Veuillez entrez votre nom...">
                        </div>
                        
                        <!-- Nom de la banque (conditionnel) -->
                        <div id="nom-banque-container" class="mb-4 d-none">
                            <label for="nom_banque" class="form-label">Nom de la banque <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-modern" id="nom_banque" name="nom_banque" 
                                   placeholder="Entrez le nom de votre banque...">
                        </div>
                        
                        <!-- Informations supplémentaires selon la méthode -->
                        <div id="additional-info" class="info-additionnelle d-none">
                            <h6><i class="fas fa-info-circle me-1"></i> Informations importantes</h6>
                            <p id="info-text" class="mb-0 small"></p>
                        </div>
                        
                        <!-- Boutons d'action -->
                        <div class="d-flex gap-3 justify-content-end mt-4">
                            <a href="{{ url()->previous() }}" class="btn btn-retour">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-soumettre">
                                <i class="fas fa-paper-plane me-1"></i> Demander le retrait
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Carte d'informations -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <i class="fas fa-lightbulb me-2"></i>Informations importantes
                </div>
                <div class="card-body-modern">
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3 text-primary">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Délais de traitement</h6>
                            <p class="small mb-0">Les retraits sont traités sous 24 à 48 heures ouvrées.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3 text-primary">
                            <i class="fas fa-exclamation-circle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Vérification des informations</h6>
                            <p class="small mb-0">Assurez-vous que vos coordonnées sont correctes pour éviter tout retard.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start">
                        <div class="me-3 text-primary">
                            <i class="fas fa-shield-alt fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Sécurité</h6>
                            <p class="small mb-0">Toutes vos transactions sont cryptées et sécurisées.</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6 class="mb-3">Méthodes disponibles</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="method-badge">Wave</span>
                        <span class="method-badge">Orange Money</span>
                        <span class="method-badge">MTN Money</span>
                        <span class="method-badge">Virement Bancaire</span>
                    </div>
                </div>
            </div>
            
            <!-- Carte de contact -->
            <div class="card-modern mt-4">
                <div class="card-header-modern">
                    <i class="fas fa-headset me-2"></i>Besoin d'aide?
                </div>
                <div class="card-body-modern">
                    <p class="small">Si vous rencontrez des difficultés avec votre demande de retrait, contactez notre support.</p>
                    <div class="d-grid">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-phone me-1"></i>+225 {{Auth::guard('paroisse')->user()->contact}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ajout de SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('retraitForm');
        const methodeSelect = document.getElementById('methode');
        const additionalInfo = document.getElementById('additional-info');
        const infoText = document.getElementById('info-text');
        const nomBanqueContainer = document.getElementById('nom-banque-container');
        const nomBanqueInput = document.getElementById('nom_banque');
        
        // Informations selon la méthode de retrait
        const methodInfo = {
            'wave': 'Les retraits via Wave sont traités dans un délai de 24 à 48 heures. Assurez-vous que votre numéro Wave est correct et actif.',
            'orange_money': 'Les retraits via Orange Money sont traités dans un délai de 24 à 48 heures. Vérifiez que votre numéro est correct et que votre compte est activé.',
            'mtn_money': 'Les retraits via MTN Money sont traités dans un délai de 24 à 48 heures. Vérifiez que votre numéro est correct et que votre compte est activé.',
            'virement_bancaire': 'Les virements bancaires peuvent prendre 2 à 3 jours ouvrés. Assurez-vous d\'avoir fourni les bons détails bancaires (nom de la banque, etc.).'
        };
        
        // Afficher les informations supplémentaires selon la méthode sélectionnée
        methodeSelect.addEventListener('change', function() {
            const selectedMethod = this.value;
            
            // Afficher/masquer le champ nom de la banque
            if (selectedMethod === 'virement_bancaire') {
                nomBanqueContainer.classList.remove('d-none');
                nomBanqueInput.setAttribute('required', 'required');
            } else {
                nomBanqueContainer.classList.add('d-none');
                nomBanqueInput.removeAttribute('required');
                nomBanqueInput.value = '';
            }
            
            if (selectedMethod && methodInfo[selectedMethod]) {
                infoText.textContent = methodInfo[selectedMethod];
                additionalInfo.classList.remove('d-none');
            } else {
                additionalInfo.classList.add('d-none');
            }
        });
        
        // Validation du formulaire
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const montant = parseFloat(document.getElementById('montant').value);
            const methode = document.getElementById('methode').value;
            const numeroCompte = document.getElementById('numero_compte').value;
            const nomTitulaire = document.getElementById('nom_titulaire').value;
            const nomBanque = document.getElementById('nom_banque').value;
            
            // Validation côté client
            if (!montant || montant < 1000 || montant > {{ $soldeDisponible }}) {
                Swal.fire({
                    icon: 'error',
                    title: 'Montant invalide',
                    text: 'Le montant doit être entre 1 000 et {{ number_format($soldeDisponible, 0, ',', ' ') }} FCFA',
                    confirmButtonColor: '#f35525'
                });
                return;
            }
            
            if (!methode) {
                Swal.fire({
                    icon: 'error',
                    title: 'Méthode manquante',
                    text: 'Veuillez sélectionner une méthode de retrait',
                    confirmButtonColor: '#f35525'
                });
                return;
            }
            
            // Validation spécifique pour virement bancaire
            if (methode === 'virement_bancaire' && !nomBanque) {
                Swal.fire({
                    icon: 'error',
                    title: 'Nom de banque manquant',
                    text: 'Veuillez saisir le nom de votre banque pour un virement bancaire',
                    confirmButtonColor: '#f35525'
                });
                return;
            }
            
            if (!numeroCompte) {
                Swal.fire({
                    icon: 'error',
                    title: 'Numéro manquant',
                    text: 'Veuillez saisir un numéro de compte/téléphone',
                    confirmButtonColor: '#f35525'
                });
                return;
            }
            
            if (!nomTitulaire) {
                Swal.fire({
                    icon: 'error',
                    title: 'Nom manquant',
                    text: 'Veuillez saisir le nom du titulaire du compte',
                    confirmButtonColor: '#f35525'
                });
                return;
            }
            
            // Préparer le texte de confirmation
            let confirmationText = `
                <div class="text-start">
                    <p>Vous êtes sur le point de demander un retrait de <strong>${montant.toLocaleString('fr-FR')} FCFA</strong></p>
                    <p>Méthode: <strong>${methode.replace('_', ' ')}</strong></p>
                    <p>Numéro: <strong>${numeroCompte}</strong></p>
                    <p>Titulaire: <strong>${nomTitulaire}</strong></p>
            `;
            
            // Ajouter le nom de la banque si c'est un virement bancaire
            if (methode === 'virement_bancaire') {
                confirmationText += `<p>Banque: <strong>${nomBanque}</strong></p>`;
            }
            
            confirmationText += `</div>`;
            
            // Confirmation avant envoi
            Swal.fire({
                title: 'Confirmer la demande',
                html: confirmationText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f35525',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-check me-1"></i> Confirmer',
                cancelButtonText: '<i class="fas fa-times me-1"></i> Annuler',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Soumettre le formulaire
                    form.submit();
                }
            });
        });
        
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
    });
</script>

@endsection