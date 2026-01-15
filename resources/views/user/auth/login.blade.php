<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - E-Messe</title>
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
            --border-radius: 20px;
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            --shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
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
            overflow-x: hidden;
        }

        .login-card {
            background: var(--light);
            width: 100%;
            max-width: 1100px;
            min-height: 650px;
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

        .login-left {
            flex: 1.2;
            background: linear-gradient(#c2a367, #c2a367), url('{{ asset('assets/assets/images/bggg.jpg') }}');
            background-size: cover;
            background-position: center;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: var(--light);
            position: relative;
        }

        .login-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(204, 164, 94, 0.3) 0%, transparent 100%);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 700;
            z-index: 1;
        }

        .brand-logo img {
            width: 45px;
            height: 45px;
            background: white;
            padding: 5px;
            border-radius: 12px;
        }

        .left-content {
            z-index: 1;
        }

        .left-content h1 {
            font-size: 42px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .left-content p {
            font-size: 18px;
            opacity: 0.9;
            max-width: 400px;
        }

        .login-right {
            flex: 1;
            background: var(--light);
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header h2 {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .login-header p {
            color: var(--text-muted);
            margin-bottom: 35px;
        }

        .form-group {
            margin-bottom: 20px;
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
            border-radius: 12px;
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

        .form-control-modern:focus+i {
            color: var(--primary);
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            left: auto !important;
            cursor: pointer;
            color: var(--text-muted);
            z-index: 10;
        }

        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            cursor: pointer;
        }

        .remember-me input {
            accent-color: var(--primary);
            width: 18px;
            height: 18px;
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            margin-bottom: 20px;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(204, 164, 94, 0.2);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 25px 0;
            color: #e0e0e0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #eeeeee;
        }

        .divider span {
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
        }

        .social-buttons {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }

        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 12px;
            border: 2px solid #f1f1f1;
            border-radius: 12px;
            background: white;
            color: var(--dark);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-social:hover {
            border-color: #e0e0e0;
            background: #fbfbfb;
            transform: translateY(-2px);
        }

        .btn-social img {
            width: 20px;
            height: 20px;
        }

        .footer-text {
            text-align: center;
            font-size: 15px;
            color: var(--text-muted);
        }

        .footer-text a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }

        .back-home {
            position: absolute;
            top: 25px;
            left: 25px;
            width: 45px;
            height: 45px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
            text-decoration: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            z-index: 100;
        }

        .back-home:hover {
            background: var(--primary);
            color: white;
            transform: rotate(-10deg);
        }

        @media (max-width: 992px) {
            .login-left {
                display: none;
            }

            .login-card {
                max-width: 500px;
            }

            .login-right {
                padding: 40px;
            }
        }

        .error-hint {
            color: #ff4d4d;
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>

<body>
    <a href="/" class="back-home" title="Retour à l'accueil">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="login-card">
        <div class="login-left">
            <div class="brand-logo">
                <img src="{{ asset('assets/assets/images/logo_principal.svg') }}" alt="Logo">
                <span>E-MESSE</span>
            </div>
            <div class="left-content">
                <h1>Vivez votre foi de manière moderne.</h1>
                <p>Connectez-vous pour demander vos messes, gérer vos intentions et rester proche de votre
                    communauté.</p>
            </div>
            <div class="left-footer">
                <p style="font-size: 14px; opacity: 0.7;">&copy; 2026 E-Messe. Tous droits réservés.</p>
            </div>
        </div>

        <div class="login-right">
            <div class="login-header">
                <h2>Bon retour !</h2>
                <p>Veuillez entrer vos informations de connexion.</p>
            </div>

            <form method="POST" action="{{ route('handleLogin') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email ou Nom d'utilisateur</label>
                    <div class="input-group-modern">
                        <i class="fas fa-envelope"></i>
                        <input type="text" name="login_id" class="form-control-modern" value="{{ old('login_id') }}"
                            placeholder="Ex: jean.dupont@email.com">
                    </div>
                    @error('login_id')
                        <div class="error-hint"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <div class="input-group-modern">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" class="form-control-modern"
                            placeholder="••••••••">
                        <i class="fas fa-eye password-toggle" onclick="togglePassword()"></i>
                    </div>
                    @error('password')
                        <div class="error-hint"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="options-row">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>Se souvenir de moi</span>
                    </label>
                    <a href="{{ route('forgot-password.form') }}" class="forgot-password">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn-login">Se connecter</button>
            </form>

            <div class="divider">
                <span>OU</span>
            </div>

            <div class="social-buttons">
                <a href="{{ route('google.login') }}" class="btn-social">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
                    <span>Continuer avec Google</span>
                </a>
            </div>

            <p class="footer-text">
                Vous n'avez pas de compte ? <a href="{{ route('register') }}">Inscrivez-vous</a>
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Succès !',
                text: "{{ session('success') }}",
                confirmButtonColor: '#cca45e',
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Erreur !',
                text: "{{ session('error') }}",
                confirmButtonColor: '#cca45e',
            });
        @endif
    </script>
</body>

</html>
