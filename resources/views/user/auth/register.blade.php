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
            max-width: 1500px;
            min-height: 600px;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            background: var(--white);
            position: relative;
        }
        
        .back-button {
            position: absolute;
            top: 25px;
            left: 25px;
            background: var(--white);
            width: 45px;
            height: 45px;
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
            background: linear-gradient(135deg, var(--primary) 0%, #ff7b4e 100%);
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
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
        }
        
        .logo-circle {
            width: 40px;
            height: 40px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: var(--primary);
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
        
        .features {
            list-style: none;
            margin-top: 40px;
        }
        
        .features li {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            font-size: 15px;
        }
        
        .features i {
            background: rgba(255, 255, 255, 0.2);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
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
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            color: var(--gray-dark);
            margin-bottom: 10px;
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
            padding: 15px 15px 15px 45px;
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
            box-shadow: 0 0 0 3px rgba(243, 85, 37, 0.15);
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            cursor: pointer;
            font-size: 16px;
        }
        
        .signup-button {
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
            margin-bottom: 25px;
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
            font-size: 15px;
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
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .form-col {
            flex: 1;
        }
        
        /* Style pour les icônes des champs */
        .form-group .input-icon {
            z-index: 2;
        }
        
        /* Style pour le champ fichier */
        .file-input-container {
            position: relative;
        }
        
        .file-input-container input[type="file"] {
            padding: 15px;
            border: 2px solid #e6e6e6;
            border-radius: var(--border-radius);
            background-color: var(--gray-light);
            width: 100%;
            font-size: 15px;
        }
        
        /* Responsive */
        @media (max-width: 900px) {
            .container {
                flex-direction: column;
                max-width: 500px;
            }
            
            .left-panel {
                padding: 30px;
            }
            
            .right-panel {
                padding: 40px 30px;
            }
            
            .back-button {
                top: 15px;
                left: 15px;
                width: 40px;
                height: 40px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
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
                    <img src="{{asset('assets/assets/images/pape.jpg')}}" style="width: 400px; border-radius:100px" alt="">
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
                                <i class="input-icon fas fa-user text-danger"></i>
                                <input type="text" id="name" name="name" placeholder="Entrez votre nom complet" >
                                 @error('name')
                                    <div class="error-message" style="color: rgb(184, 8, 8)">
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
                                <input type="text" id="user_name" name="user_name" placeholder="Entrez votre nom d'utilisateur" >
                                 @error('user_name')
                                    <div class="error-message" style="color: rgb(184, 8, 8)">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deuxième ligne: Email et Commune -->
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="email">Adresse email</label>
                            <div class="input-with-icon">
                                <i class="input-icon fas fa-envelope"></i>
                                <input type="email" id="email" name="email" placeholder="Entrez votre adresse email" >
                                @error('email')
                                    <div class="error-message" style="color: rgb(184, 8, 8)">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Troisième ligne: Indicatif et Contact -->
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="commune">Commune</label>
                            <div class="input-with-icon">
                                <i class="input-icon fas fa-map-marker-alt"></i>
                                <input type="text" id="commune" name="commune" placeholder="Entrez votre commune" >
                                @error('commune')
                                    <div class="error-message" style="color: rgb(184, 8, 8)">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="contact">Contact</label>
                            <div class="input-with-icon">
                                <i class="input-icon fas fa-mobile-alt"></i>
                                <input type="text" id="contact" name="contact" placeholder="Entrez votre numéro de téléphone" >
                                @error('contact')
                                    <div class="error-message" style="color: rgb(184, 8, 8)">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mot de passe (pleine largeur) -->
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <div class="input-with-icon">
                                <i class="input-icon fas fa-lock"></i>
                                <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" >
                                @error('password')
                                    <div class="error-message" style="color: rgb(184, 8, 8)">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                                <i class="password-toggle fas fa-eye" onclick="togglePassword()"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-col">
                       <div class="form-group">
                            <label for="password">Confirmation</label>
                            <div class="input-with-icon">
                                <i class="input-icon fas fa-lock"></i>
                                <input type="password" name="password_confirmation" id="password"placeholder=" Confirmer" />
                                @error('password_confirmation')
                                    <div class="error-message" style="color: rgb(184, 8, 8)">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                                <i class="password-toggle fas fa-eye" onclick="togglePassword()"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quatrième ligne: CMU et Photo de profil -->
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="CMU">CMU (facultatif)</label>
                            <div class="input-with-icon">
                                <i class="input-icon fas fa-id-card"></i>
                                <input type="text" id="CMU" name="CMU" placeholder="Entrez votre numéro CMU (si applicable)">
                                @error('CMU')
                                    <div class="error-message" style="color: rgb(184, 8, 8)"> 
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="profile_picture">Photo de profil (facultatif)</label>
                            <div class="input-with-icon">
                                <i class="input-icon fas fa-camera"></i>
                                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" style="padding-left: 45px;">
                                @error('profile_picture')
                                    <div class="error-message" style="color: rgb(184, 8, 8)">
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
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>