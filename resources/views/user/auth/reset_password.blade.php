<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .auth-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .password-requirements {
            font-size: 0.875rem;
            color: #6c757d;
        }
        .password-strength {
            margin-top: 5px;
            font-size: 0.8rem;
        }
        .strength-weak { color: #dc3545; }
        .strength-medium { color: #fd7e14; }
        .strength-strong { color: #198754; }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-container">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Nouveau mot de passe</h2>
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reset-password.update') }}" id="resetPasswordForm">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('email') }}">
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required oninput="checkPasswordStrength()">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="password-strength" id="passwordStrength"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required oninput="checkPasswordMatch()">
                            <div class="password-strength" id="passwordMatch"></div>
                        </div>
                        
                        <div class="password-requirements mb-3">
                            <small>Le mot de passe doit contenir :</small>
                            <ul class="mb-0">
                                <li>Au moins 8 caractères</li>
                                <li>Une majuscule et une minuscule</li>
                                <li>Un chiffre</li>
                                <li>Un caractère spécial (@$!%*#?&.)</li>
                            </ul>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100" id="submitBtn">
                            <i class="fas fa-lock me-2"></i>Réinitialiser le mot de passe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    
    <script>
        // Vérification de la force du mot de passe en temps réel
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthText = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthText.innerHTML = '';
                return;
            }

            let strength = 0;
            let feedback = '';

            // Longueur minimale
            if (password.length >= 8) strength++;
            
            // Contient des minuscules
            if (/[a-z]/.test(password)) strength++;
            
            // Contient des majuscules
            if (/[A-Z]/.test(password)) strength++;
            
            // Contient des chiffres
            if (/[0-9]/.test(password)) strength++;
            
            // Contient des caractères spéciaux
            if (/[@$!%*#?&.]/.test(password)) strength++;

            switch(strength) {
                case 0:
                case 1:
                case 2:
                    feedback = '<span class="strength-weak">Faible</span>';
                    break;
                case 3:
                case 4:
                    feedback = '<span class="strength-medium">Moyen</span>';
                    break;
                case 5:
                    feedback = '<span class="strength-strong">Fort</span>';
                    break;
            }

            strengthText.innerHTML = `Force du mot de passe : ${feedback}`;
        }

        // Vérification de la correspondance des mots de passe
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchText = document.getElementById('passwordMatch');
            
            if (confirmPassword.length === 0) {
                matchText.innerHTML = '';
                return;
            }

            if (password === confirmPassword) {
                matchText.innerHTML = '<span class="strength-strong">✓ Les mots de passe correspondent</span>';
            } else {
                matchText.innerHTML = '<span class="strength-weak">✗ Les mots de passe ne correspondent pas</span>';
            }
        }

        // Gestion de la soumission du formulaire avec AJAX
        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const submitBtn = document.getElementById('submitBtn');
            
            // Validation côté client
            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Les mots de passe ne correspondent pas.',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }

            // Validation des critères du mot de passe
            const hasMinLength = password.length >= 8;
            const hasLowercase = /[a-z]/.test(password);
            const hasUppercase = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecialChar = /[@$!%*#?&.]/.test(password);

            if (!hasMinLength || !hasLowercase || !hasUppercase || !hasNumber || !hasSpecialChar) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Mot de passe invalide',
                    html: `Veuillez vérifier que votre mot de passe respecte tous les critères :
                        <ul class="text-start mt-2">
                            <li>${hasMinLength ? '✓' : '✗'} Au moins 8 caractères</li>
                            <li>${hasLowercase ? '✓' : '✗'} Une lettre minuscule</li>
                            <li>${hasUppercase ? '✓' : '✗'} Une lettre majuscule</li>
                            <li>${hasNumber ? '✓' : '✗'} Un chiffre</li>
                            <li>${hasSpecialChar ? '✓' : '✗'} Un caractère spécial</li>
                        </ul>`,
                    confirmButtonColor: '#fd7e14'
                });
                return;
            }

            // Afficher le chargement
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Réinitialisation...';
            submitBtn.disabled = true;

            // Préparer les données du formulaire
            const formData = new FormData(this);

            // Envoyer la requête AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Afficher SweetAlert de succès
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès !',
                        text: data.message,
                        confirmButtonColor: '#198754',
                        confirmButtonText: 'Se connecter',
                        showCancelButton: true,
                        cancelButtonText: 'Fermer',
                        timer: 8000,
                        timerProgressBar: true
                    }).then((result) => {
                        // Rediriger vers la page de connexion
                        window.location.href = data.redirect_url;
                    });
                } else {
                    throw new Error(data.message || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                // Réactiver le bouton
                submitBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Réinitialiser le mot de passe';
                submitBtn.disabled = false;

                // Afficher l'erreur
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: error.message || 'Une erreur est survenue lors de la réinitialisation',
                    confirmButtonColor: '#dc3545'
                });
            });
        });

        // Gestion des erreurs de validation Laravel (pour les cas où JavaScript est désactivé)
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                html: `@foreach($errors->all() as $error){{ $error }}<br>@endforeach`,
                confirmButtonColor: '#dc3545'
            });
        @endif
    </script>
</body>
</html>