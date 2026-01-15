<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - E-Messe</title>
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

        .auth-card {
            background: var(--light);
            width: 100%;
            max-width: 1100px;
            min-height: 700px;
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

        .auth-left::before {
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
            font-size: 38px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .left-content p {
            font-size: 17px;
            opacity: 0.9;
            max-width: 400px;
        }

        .auth-right {
            flex: 1.5;
            background: var(--light);
            padding: 50px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }

        .auth-header h2 {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .auth-header p {
            color: var(--text-muted);
            margin-bottom: 30px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 6px;
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
            padding: 12px 16px 12px 48px;
            border: 2px solid #f1f1f1;
            border-radius: 12px;
            font-size: 14.5px;
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

        .btn-auth {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .btn-auth:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(204, 164, 94, 0.2);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 20px 0;
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
            margin-bottom: 20px;
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

        .error-hint {
            color: #ff4d4d;
            font-size: 12px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        @media (max-width: 992px) {
            .auth-left {
                display: none;
            }

            .auth-card {
                max-width: 600px;
            }

            .auth-right {
                padding: 40px;
            }
        }

        @media (max-width: 576px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
    </style>
</head>

<body>
    <a href="/" class="back-home" title="Retour à l'accueil">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="auth-card">
        <div class="auth-left">
            <div class="brand-logo">
                <img src="{{ asset('assets/assets/images/logo_principal.svg') }}" alt="Logo">
                <span>E-MESSE</span>
            </div>
            <div class="left-content">
                <h1>Bienvenue dans la communauté.</h1>
                <p>Créez votre compte en quelques secondes et commencez à vivre votre foi avec E-Messe.</p>
            </div>
            <div class="left-footer">
                <p style="font-size: 14px; opacity: 0.7;">&copy; 2026 E-Messe. Tous droits réservés.</p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-header">
                <h2>Inscription</h2>
                <p>Commençons l'aventure ensemble !</p>
            </div>

            <form method="POST" action="{{ route('handleRegister') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nom complet</label>
                        <div class="input-group-modern">
                            <i class="fas fa-user"></i>
                            <input type="text" name="name" class="form-control-modern" value="{{ old('name') }}"
                                placeholder="Jean Dupont">
                        </div>
                        @error('name')
                            <div class="error-hint"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom d'utilisateur</label>
                        <div class="input-group-modern">
                            <i class="fas fa-at"></i>
                            <input type="text" name="user_name" class="form-control-modern"
                                value="{{ old('user_name') }}" placeholder="jdupont225">
                        </div>
                        @error('user_name')
                            <div class="error-hint"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <div class="input-group-modern">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" class="form-control-modern" value="{{ old('email') }}"
                                placeholder="jean.dupont@email.com">
                        </div>
                        @error('email')
                            <div class="error-hint"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contact (Téléphone)</label>
                        <div class="input-group-modern">
                            <i class="fas fa-mobile-alt"></i>
                            <input type="text" name="contact" class="form-control-modern"
                                value="{{ old('contact') }}" placeholder="+225 0707070707">
                        </div>
                        @error('contact')
                            <div class="error-hint"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Mot de passe</label>
                        <div class="input-group-modern">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" class="form-control-modern"
                                placeholder="••••••••">
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('password', this)"></i>
                        </div>
                        @error('password')
                            <div class="error-hint"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirmation</label>
                        <div class="input-group-modern">
                            <i class="fas fa-shield-alt"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control-modern" placeholder="••••••••">
                            <i class="fas fa-eye password-toggle"
                                onclick="togglePassword('password_confirmation', this)"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Photo de profil (facultatif)</label>
                    <div class="input-group-modern">
                        <i class="fas fa-camera"></i>
                        <input type="file" name="profile_picture" class="form-control-modern" accept="image/*"
                            onchange="checkFileSize(this)">
                    </div>
                    @error('profile_picture')
                        <div class="error-hint"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-auth">Créer mon compte</button>
            </form>

            <div class="divider">
                <span>OU</span>
            </div>

            <div class="social-buttons">
                <a href="{{ route('google.login') }}" class="btn-social">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
                    <span>S'inscrire avec Google</span>
                </a>
            </div>

            <p class="footer-text">
                Vous avez déjà un compte ? <a href="{{ route('login') }}">Connectez-vous</a>
            </p>
        </div>
    </div>

    <script>
        function togglePassword(inputId, element) {
            const passwordInput = document.getElementById(inputId);
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                element.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                element.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function checkFileSize(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                if (file.size > 2048 * 1024) {
                    alert('L\'image est trop volumineuse. La taille maximale est de 2 Mo.');
                    input.value = '';
                }
            }
        }
    </script>
</body>

</html>
