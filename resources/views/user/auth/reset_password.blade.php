<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe - E-messe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('assets/assets/images/logo_principal.svg') }}" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            --primary: #cda45e;
            --primary-light: rgba(205, 164, 94, 0.1);
            --black: #000000;
            --white: #ffffff;
            --gray-light: #f8f9fa;
            --gray: #6c757d;
            --gray-dark: #343a40;
            --border-radius: 16px;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 15px 40px rgba(0, 0, 0, 0.12);
            --success: #198754;
            --warning: #fd7e14;
            --danger: #dc3545;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Segoe UI', sans-serif;
        }

        body {
            background: linear-gradient(#c7a663, #ffffffff),
                url('{{ asset('assets/assets/images/bggg.jpg') }}');
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: var(--primary-light);
            border-radius: 50%;
            top: -150px;
            right: -150px;
            z-index: -1;
        }

        body::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: var(--primary-light);
            border-radius: 50%;
            bottom: -100px;
            left: -100px;
            z-index: -1;
        }

        .container {
            display: flex;
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            background: var(--white);
            position: relative;
        }

        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, var(--primary) 0%, #bea56aff 100%);
            color: var(--white);
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            top: -50px;
            left: -50px;
        }

        .left-content {
            position: relative;
            z-index: 1;
        }

        .left-panel h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.3;
        }

        .left-panel p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .requirement-list {
            list-style: none;
            margin-top: 20px;
        }

        .requirement-list li {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .requirement-list i {
            margin-right: 10px;
            font-size: 14px;
            width: 20px;
            text-align: center;
        }

        .right-panel {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: var(--white);
        }

        .right-panel h2 {
            font-size: 28px;
            color: var(--black);
            margin-bottom: 10px;
            font-weight: 700;
        }

        .welcome-text {
            color: var(--gray);
            margin-bottom: 40px;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            color: var(--gray-dark);
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
        }

        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 16px;
        }

        .form-group input {
            width: 100%;
            padding: 15px 45px 15px 45px;
            border: 2px solid #e6e6e6;
            border-radius: var(--border-radius);
            font-size: 15px;
            transition: var(--transition);
            background-color: var(--gray-light);
        }

        .form-group input:focus {
            border-color: var(--primary);
            outline: none;
            background-color: var(--white);
            box-shadow: 0 0 0 3px rgba(205, 164, 94, 0.15);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            cursor: pointer;
            font-size: 16px;
            z-index: 5;
        }

        .password-strength {
            margin-top: 6px;
            font-size: 12px;
            font-weight: 500;
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

        .submit-button {
            background-color: var(--primary);
            color: var(--white);
            border: none;
            padding: 16px;
            border-radius: var(--border-radius);
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 10px;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }

        .submit-button:hover {
            color: var(--primary);
            background-color: var(--white);
            transform: translateY(-2px);
            border: 2px solid var(--primary);
        }

        .submit-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        @media (max-width: 900px) {
            .container {
                flex-direction: column;
                max-width: 500px;
                min-height: auto;
            }

            .left-panel {
                padding: 30px;
                text-align: center;
            }

            .left-panel::before {
                display: none;
            }

            .right-panel {
                padding: 40px 30px;
            }

            .requirement-list {
                display: inline-block;
                text-align: left;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 15px;
            }

            .right-panel {
                padding: 30px 20px;
            }

            .right-panel h2 {
                font-size: 24px;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .left-panel {
            animation: fadeIn 0.6s ease-out 0.2s both;
        }

        .right-panel {
            animation: fadeIn 0.6s ease-out 0.4s both;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left-panel">
            <div class="left-content">
                <h2>Nouveau mot de passe</h2>
                <p>Pour assurer la sécurité de votre compte, veuillez choisir un mot de passe robuste.</p>

                <ul class="requirement-list">
                    <li><i class="fas fa-check-circle" id="req-length"></i> Au moins 8 caractères</li>
                    <li><i class="fas fa-check-circle" id="req-case"></i> Majuscule et minuscule</li>
                    <li><i class="fas fa-check-circle" id="req-number"></i> Au moins un chiffre</li>
                    <li><i class="fas fa-check-circle" id="req-special"></i> Caractère spécial (@$!%*#?&.)</li>
                </ul>
            </div>
        </div>

        <div class="right-panel">
            <h2>Réinitialisation</h2>
            <p class="welcome-text">Créez votre nouveau mot de passe pour vous reconnecter.</p>

            <form method="POST" action="{{ route('reset-password.update') }}" id="resetPasswordForm">
                @csrf
                <input type="hidden" name="email" value="{{ session('email') }}">

                <div class="form-group">
                    <label for="password">Nouveau mot de passe</label>
                    <div class="input-with-icon">
                        <i class="input-icon fas fa-lock"></i>
                        <input type="password" id="password" name="password" required oninput="checkPasswordStrength()"
                            placeholder="••••••••">
                        <i class="password-toggle fas fa-eye" onclick="togglePassword('password', this)"></i>
                    </div>
                    <div class="password-strength" id="passwordStrength"></div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <div class="input-with-icon">
                        <i class="input-icon fas fa-check-double"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            oninput="checkPasswordMatch()" placeholder="••••••••">
                        <i class="password-toggle fas fa-eye"
                            onclick="togglePassword('password_confirmation', this)"></i>
                    </div>
                    <div class="password-strength" id="passwordMatch"></div>
                </div>

                <button type="submit" class="submit-button" id="submitBtn">
                    <i class="fas fa-shield-alt me-2"></i>Réinitialiser le mot de passe
                </button>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
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
                [reqLength, reqCase, reqNumber, reqSpecial].forEach(el => el.style.color = 'white');
                return;
            }

            let strength = 0;

            if (password.length >= 8) {
                strength++;
                reqLength.style.color = '#2ecc71';
            } else {
                reqLength.style.color = 'rgba(255,255,255,0.5)';
            }
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
                strength++;
                reqCase.style.color = '#2ecc71';
            } else {
                reqCase.style.color = 'rgba(255,255,255,0.5)';
            }
            if (/[0-9]/.test(password)) {
                strength++;
                reqNumber.style.color = '#2ecc71';
            } else {
                reqNumber.style.color = 'rgba(255,255,255,0.5)';
            }
            if (/[@$!%*#?&.]/.test(password)) {
                strength++;
                reqSpecial.style.color = '#2ecc71';
            } else {
                reqSpecial.style.color = 'rgba(255,255,255,0.5)';
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
                    confirmButtonColor: '#cda45e'
                });
                return;
            }

            if (password.length < 8 || !/[a-z]/.test(password) || !/[A-Z]/.test(password) || !/[0-9]/.test(
                password)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sécurité insuffisante',
                    text: 'Veuillez respecter tous les critères de sécurité.',
                    confirmButtonColor: '#cda45e'
                });
                return;
            }

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement...';
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
                            confirmButtonColor: '#cda45e',
                            confirmButtonText: 'Se connecter'
                        }).then(() => {
                            window.location.href = data.redirect_url;
                        });
                    } else {
                        throw new Error(data.message || 'Une erreur est survenue');
                    }
                })
                .catch(error => {
                    submitBtn.innerHTML = '<i class="fas fa-shield-alt me-2"></i>Réinitialiser le mot de passe';
                    submitBtn.disabled = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: error.message,
                        confirmButtonColor: '#cda45e'
                    });
                });
        });
    </script>
</body>

</html>
