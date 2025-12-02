<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User - Sing up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #f35525;
            --primary-light: rgba(243, 85, 37, 0.1);
            --black: #000000;
            --white: #ffffff;
            --gray-light: #f8f9fa;
            --gray: #6c757d;
            --gray-dark: #343a40;
            --border-radius: 16px;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 15px 40px rgba(0, 0, 0, 0.12);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Segoe UI', sans-serif;
        }
        
        body {
            background: 
                linear-gradient(rgba(238, 206, 0, 0.1), rgba(248,100,53, 0.975)),
                url('{{ asset('assets/assets/images/bggg.jpg') }}');
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
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
            flex-wrap: wrap;
            max-width: 1000px;
            width: 100%;
            min-height: 400px;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            background: var(--white);
            position: relative;
        }
        
        .back-button {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--white);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
            z-index: 10;
        }
        
        .back-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
            color: var(--primary);
        }
        
        .left-panel {
            flex: 1;
            min-width: 300px;
            background: linear-gradient(135deg, var(--primary) 0%, #ff7b4e 100%);
            color: var(--white);
            padding: 40px 30px;
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
        
        .left-panel::after {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            bottom: -50px;
            right: -50px;
        }
        
        .left-content {
            position: relative;
            z-index: 1;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .logo-circle {
            width: 36px;
            height: 36px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            color: var(--primary);
        }
        
        .left-panel h2 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        
        .left-panel p {
            font-size: 15px;
            line-height: 1.5;
            margin-bottom: 25px;
            opacity: 0.9;
        }
        
        .features {
            list-style: none;
            margin-top: 30px;
        }
        
        .features li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .features i {
            background: rgba(255, 255, 255, 0.2);
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 13px;
        }
        
        .right-panel {
            flex: 1;
            min-width: 300px;
            padding: 30px 25px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: var(--white);
        }
        
        .right-panel h2 {
            font-size: 24px;
            color: var(--black);
            font-weight: 700;
            text-align: center;
            margin-bottom: 10px;
        }
        
        .welcome-text {
            color: var(--gray);
            margin-bottom: 25px;
            font-size: 14px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 15px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            color: var(--gray-dark);
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 500;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 15px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 2px solid #e6e6e6;
            border-radius: var(--border-radius);
            font-size: 14px;
            transition: var(--transition);
            background-color: var(--gray-light);
        }
        
        .form-group input:focus {
            border-color: var(--primary);
            outline: none;
            background-color: var(--white);
            box-shadow: 0 0 0 3px rgba(243, 85, 37, 0.15);
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            cursor: pointer;
            font-size: 15px;
        }
        
        .signup-button {
            background-color: var(--primary);
            color: var(--white);
            border: none;
            padding: 14px;
            border-radius: var(--border-radius);
            width: 100%;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-bottom: 20px;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(243, 85, 37, 0.3);
        }
        
        .signup-button:hover {
            background-color: #e04a1b;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(243, 85, 37, 0.4);
        }
        
        .signup-button:active {
            transform: translateY(0);
        }
        
        .signup-link {
            text-align: center;
            font-size: 14px;
            color: var(--gray-dark);
        }
        
        .signup-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }
        
        .signup-link a:hover {
            color: var(--primary);
            text-decoration: underline;
        }
        
        /* Nouvelles règles pour la disposition en deux colonnes */
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 10px;
        }
        
        .form-col {
            flex: 1;
            min-width: 200px;
        }
        
        /* Style pour les messages d'erreur */
        .error-message {
            color: rgb(184, 8, 8);
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
        }
        
        .error-message i {
            margin-right: 5px;
            font-size: 11px;
        }
        
        /* Style pour le champ fichier */
        .form-group input[type="file"] {
            padding-left: 40px;
            cursor: pointer;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .container {
                flex-direction: column;
                border-radius: 12px;
            }
            
            .left-panel, .right-panel {
                padding: 25px 20px;
            }
            
            .left-panel {
                order: 2;
            }
            
            .right-panel {
                order: 1;
            }
            
            .back-button {
                top: 10px;
                left: 10px;
                width: 35px;
                height: 35px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .form-col {
                min-width: 100%;
            }
            
            .left-panel h2 {
                font-size: 22px;
            }
            
            .features {
                margin-top: 20px;
            }
        }
        
        @media (max-width: 480px) {
            body::before {
                width: 200px;
                height: 200px;
                top: -100px;
                right: -100px;
            }
            
            body::after {
                width: 150px;
                height: 150px;
                bottom: -75px;
                left: -75px;
            }
            
            .left-panel::before {
                width: 150px;
                height: 150px;
            }
            
            .left-panel::after {
                width: 100px;
                height: 100px;
            }
            
            .logo {
                font-size: 20px;
            }
            
            .logo-circle {
                width: 32px;
                height: 32px;
            }
            
            .right-panel h2 {
                font-size: 22px;
            }
            
            .form-group input {
                padding: 10px 10px 10px 35px;
            }
            
            .input-icon {
                left: 10px;
                font-size: 14px;
            }
            
            .password-toggle {
                right: 10px;
                font-size: 14px;
            }
        }
        
        /* Pour les très petits écrans */
        @media (max-width: 320px) {
            .left-panel, .right-panel {
                padding: 20px 15px;
            }
            
            .left-panel h2 {
                font-size: 20px;
            }
            
            .right-panel h2 {
                font-size: 20px;
            }
        }
        
        /* Animation d'entrée */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
        <a href="/" class="back-button" title="Retour à l'accueil">
            <i class="fas fa-arrow-left"></i>
        </a>
        
        <div class="left-panel">
            <div class="left-content">
                <div class="logo">
                    <div class="logo-circle">
                        <i class="fas fa-fire"></i>
                    </div>
                    BrandName
                </div>
                <h2>Rejoignez-nous dès aujourd'hui</h2>
                <p>Inscrivez-vous pour accéder à votre espace personnel et découvrir toutes les fonctionnalités de notre plateforme.</p>
                
                <ul class="features">
                    <li><i class="fas fa-check"></i> Interface intuitive et moderne</li>
                    <li><i class="fas fa-check"></i> Sécurité avancée de vos données</li>
                    <li><i class="fas fa-check"></i> Accès à toutes vos fonctionnalités</li>
                </ul>
            </div>
        </div>
        
        <div class="right-panel">
            <h2>Inscription</h2>
            <p class="welcome-text">Remplissez le formulaire ci-dessous pour créer un compte.</p>
            
            <form method="POST" action="{{route('handleRegister')}}" enctype="multipart/form-data">
                @csrf
                <!-- Première ligne: Nom complet et Nom d'utilisateur -->
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="name">Nom complet</label>
                            <div class="input-with-icon">
                                <i class="input-icon fas fa-user"></i>
                                <input type="text" id="name" name="name" placeholder="Entrez votre nom complet" value="{{ old('name') }}" >
                                 @error('name')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="user_name">Nom d'utilisateur</label>
                            <div class="input-with-icon">
                                <i class="input-icon fas fa-at"></i>
                                <input type="text" id="user_name" name="user_name" placeholder="Entrez votre nom d'utilisateur" value="{{ old('user_name') }}" >
                                 @error('user_name')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deuxième ligne: Email -->
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="email">Adresse email</label>
                            <div class="input-with-icon">
                                <i class="input-icon fas fa-envelope"></i>
                                <input type="email" id="email" name="email" placeholder="Entrez votre adresse email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Troisième ligne: Commune et Contact -->
                <div class="form-row">
                    {{-- <div class="form-col">
                        <div class="form-group">
                            <label for="commune">Commune</label>
                            <div class="input-with-icon">
                                <i class="input-icon fas fa-map-marker-alt"></i>
                                <input type="text" id="commune" name="commune" placeholder="Entrez votre commune" >
                                @error('commune')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div> --}}
                    <div class="form-col">
                        <div class="form-group">
                            <label for="contact">Contact</label>
                            <div class="input-with-icon">
                                <i class="input-icon fas fa-mobile-alt"></i>
                                <input type="text" id="contact" name="contact" placeholder="Entrez votre numéro de téléphone" value="{{ old('contact') }}">
                                @error('contact')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mot de passe et confirmation -->
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <div class="input-with-icon">
                                <i class="input-icon fas fa-lock"></i>
                                <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" value="{{ old('password') }}">
                                @error('password')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                                <i class="password-toggle fas fa-eye" onclick="togglePassword('password', this)"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-col">
                       <div class="form-group">
                            <label for="password_confirmation">Confirmation</label>
                            <div class="input-with-icon">
                                <i class="input-icon fas fa-lock"></i>
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirmer" />
                                <i class="password-toggle fas fa-eye" onclick="togglePassword('password_confirmation', this)"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Photo de profil -->
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="profile_picture">Photo de profil (facultatif)</label>
                            <div class="input-with-icon">
                                <i class="input-icon fas fa-camera"></i>
                                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" value="{{ old('profile_picture') }}">
                                @error('profile_picture')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bouton d'inscription -->
                <button type="submit" class="signup-button">S'inscrire</button>
            </form>
            
            <div class="signup-link">
                Vous avez déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, element) {
            const passwordInput = document.getElementById(inputId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                element.classList.remove('fa-eye');
                element.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                element.classList.remove('fa-eye-slash');
                element.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>