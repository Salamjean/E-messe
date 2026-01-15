<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe - E-Messe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        :root {
            --primary: #cca45e;
            --primary-dark: #b38d45;
            --secondary: #5ea7b5;
            --dark: #1a1a1a;
            --light: #ffffff;
            --gray: #f8f9fa;
            --text-muted: #6c757d;
            --border-radius: 24px;
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            --shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
            --success: #2ecc71;
            --warning: #f1c40f;
            --danger: #e74c3c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-image:
                radial-gradient(at 0% 0%, rgba(204, 164, 94, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(94, 167, 181, 0.15) 0px, transparent 50%);
            overflow: hidden;
        }

        .auth-card {
            background: var(--light);
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            display: flex;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            position: relative;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-left {
            flex: 1;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('{{ asset('assets/assets/images/bggg.jpg') }}');
            background-size: cover;
            background-position: center;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: var(--light);
            position: relative;
        }

        .auth-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(204, 164, 94, 0.4) 0%, transparent 100%);
        }

        .left-content {
            position: relative;
            z-index: 1;
        }

        .left-content h1 {
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .requirement-list {
            list-style: none;
            margin-top: 30px;
        }

        .requirement-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            font-size: 15px;
            color: rgba(255, 255, 255, 0.9);
            transition: var(--transition);
        }

        .requirement-list i {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.4);
        }

        .auth-right {
            flex: 1.2;
            background: var(--light);
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .auth-header p {
            color: var(--text-muted);
            margin-bottom: 35px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .input-group-modern {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group-modern i {
            position: absolute;
            left: 16px;
            color: var(--text-muted);
            transition: var(--transition);
        }

        .form-control-modern {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #f1f1f1;
            border-radius: 14px;
            font-size: 15px;
            transition: var(--transition);
            background: #fbfbfb;
        }

        .form-control-modern:focus {
            border-color: var(--primary);
            background: white;
            outline: none;
            box-shadow: 0 0 0 4px rgba(204, 164, 94, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            left: auto !important;
            cursor: pointer;
            z-index: 10;
        }

        .password-strength {
            margin-top: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-auth {
            width: 100%;
            padding: 16px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-auth:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(204, 164, 94, 0.2);
        }

        .btn-auth:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        .strength-weak {
            color: var(--danger);
        }

        .strength-medium {
            color: var(--warning);
        }

        .strength-strong {
            color: var(--success);
        }

        @media (max-width: 850px) {
            .auth-left {
                display: none;
            }

            .auth-card {
                max-width: 500px;
            }

            .auth-right {
                padding: 40px;
            }
        }
    </style>
</head>

<body>
    <div class="auth-card">
        <div class="auth-left">
            <div class="left-content">
                <h1>Protégez votre<br>espace personnel.</h1>
                <p>Un mot de passe fort garantit la sécurité de vos données et de vos échanges.</p>

                <ul class="requirement-list">
                    <li><i class="fas fa-circle" id="req-length"></i> Au moins 8 caractères</li>
                    <li><i class="fas fa-circle" id="req-case"></i> Majuscule & minuscule</li>
                    <li><i class="fas fa-circle" id="req-number"></i> Au moins un chiffre</li>
                    <li><i class="fas fa-circle" id="req-special"></i> Caractère spécial</li>
                </ul>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-header">
                <h2>Réinitialisation</h2>
                <p>Définissez votre nouveau mot de passe.</p>
            </div>

            <form method="POST" action="{{ route('reset-password.update') }}" id="resetPasswordForm">
                @csrf
                <input type="hidden" name="email" value="{{ session('email') }}">

                <div class="form-group">
                    <label class="form-label">Nouveau mot de passe</label>
                    <div class="input-group-modern">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" class="form-control-modern" required
                            oninput="checkPasswordStrength()" placeholder="••••••••">
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('password', this)"></i>
                    </div>
                    <div class="password-strength" id="passwordStrength"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <div class="input-group-modern">
                        <i class="fas fa-check-double"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control-modern" required oninput="checkPasswordMatch()" placeholder="••••••••">
                        <i class="fas fa-eye password-toggle"
                            onclick="togglePassword('password_confirmation', this)"></i>
                    </div>
                    <div class="password-strength" id="passwordMatch"></div>
                </div>

                <button type="submit" class="btn-auth" id="submitBtn">
                    <i class="fas fa-shield-alt"></i>
                    <span>Enregistrer le mot de passe</span>
                </button>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function togglePassword(inputId, element) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                element.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                element.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthText = document.getElementById('passwordStrength');

            const reqLength = document.getElementById('req-length');
            const reqCase = document.getElementById('req-case');
            const reqNumber = document.getElementById('req-number');
            const reqSpecial = document.getElementById('req-special');

            if (password.length === 0) {
                strengthText.innerHTML = '';
                [reqLength, reqCase, reqNumber, reqSpecial].forEach(el => el.style.color = 'rgba(255,255,255,0.4)');
                return;
            }

            let strength = 0;

            if (password.length >= 8) {
                strength++;
                reqLength.style.color = '#2ecc71';
            } else {
                reqLength.style.color = 'rgba(255,255,255,0.4)';
            }

            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
                strength++;
                reqCase.style.color = '#2ecc71';
            } else {
                reqCase.style.color = 'rgba(255,255,255,0.4)';
            }

            if (/[0-9]/.test(password)) {
                strength++;
                reqNumber.style.color = '#2ecc71';
            } else {
                reqNumber.style.color = 'rgba(255,255,255,0.4)';
            }

            if (/[@$!%*#?&.]/.test(password)) {
                strength++;
                reqSpecial.style.color = '#2ecc71';
            } else {
                reqSpecial.style.color = 'rgba(255,255,255,0.4)';
            }

            let feedback = '';
            if (strength <= 2) feedback = '<span class="strength-weak">Faible</span>';
            else if (strength === 3) feedback = '<span class="strength-medium">Moyen</span>';
            else feedback = '<span class="strength-strong">Excellent</span>';

            strengthText.innerHTML = `Force : ${feedback}`;
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const matchText = document.getElementById('passwordMatch');

            if (confirm.length === 0) {
                matchText.innerHTML = '';
                return;
            }

            if (password === confirm) {
                matchText.innerHTML = '<span class="strength-strong">✓ Les mots de passe correspondent</span>';
            } else {
                matchText.innerHTML = '<span class="strength-weak">✗ Les mots de passe ne correspondent pas</span>';
            }
        }

        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const submitBtn = document.getElementById('submitBtn');

            if (password !== confirm) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Les mots de passe ne correspondent pas.',
                    confirmButtonColor: '#cca45e'
                });
                return;
            }

            if (password.length < 8 || !/[a-z]/.test(password) || !/[A-Z]/.test(password) || !/[0-9]/.test(
                    password)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sécurité insuffisante',
                    text: 'Veuillez respecter tous les critères de sécurité.',
                    confirmButtonColor: '#cca45e'
                });
                return;
            }

            const btnText = submitBtn.querySelector('span');
            const btnIcon = submitBtn.querySelector('i');

            btnText.textContent = 'Traitement...';
            btnIcon.className = 'fas fa-spinner fa-spin';
            submitBtn.disabled = true;

            const formData = new FormData(this);

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
                        Swal.fire({
                            icon: 'success',
                            title: 'Réussi !',
                            text: data.message,
                            confirmButtonColor: '#cca45e',
                            confirmButtonText: 'Se connecter'
                        }).then(() => {
                            window.location.href = data.redirect_url;
                        });
                    } else {
                        throw new Error(data.message || 'Une erreur est survenue');
                    }
                })
                .catch(error => {
                    btnText.textContent = 'Enregistrer le mot de passe';
                    btnIcon.className = 'fas fa-shield-alt';
                    submitBtn.disabled = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: error.message,
                        confirmButtonColor: '#cca45e'
                    });
                });
        });
    </script>
</body>

</html>
