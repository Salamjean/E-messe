<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Design Moderne</title>
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
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .remember {
            display: flex;
            align-items: center;
            color: var(--gray-dark);
        }
        
        .remember input {
            margin-right: 8px;
            accent-color: var(--primary);
            width: 16px;
            height: 16px;
        }
        
        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .forgot-link:hover {
            color: var(--primary);
            text-decoration: underline;
        }
        
        .login-button {
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
        
        .login-button:hover {
            background-color: #e04a1b;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(243, 85, 37, 0.4);
        }
        
        .login-button:active {
            transform: translateY(0);
        }
        
        .separator {
            display: flex;
            align-items: center;
            margin: 30px 0;
            color: var(--gray);
            font-size: 14px;
        }
        
        .separator .line {
            flex: 1;
            height: 1px;
            background-color: #e6e6e6;
        }
        
        .separator .text {
            padding: 0 15px;
        }
        
        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .social-button {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid #e6e6e6;
            background-color: var(--white);
            cursor: pointer;
            transition: var(--transition);
        }
        
        .social-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
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
                    <div class="logo">
                    <img src="{{asset('assets/assets/images/pape.jpg')}}" style="width: 400px; border-radius:100px" alt="">
                </div>
                </div>
                <h2>Découvrez notre plateforme innovante</h2>
                <p>Connectez-vous pour accéder à votre espace personnel et découvrir toutes nos fonctionnalités exclusives.</p>
                
                <ul class="features">
                    <li><i class="fas fa-check"></i> Interface intuitive et moderne</li>
                    <li><i class="fas fa-check"></i> Sécurité avancée de vos données</li>
                    <li><i class="fas fa-check"></i> Accès à toutes vos fonctionnalités</li>
                </ul>
            </div>
        </div>
        
        <div class="right-panel">
            <h2>Connexion</h2>
            <p class="welcome-text">Content de vous revoir ! Connectez-vous à votre compte.</p>
            
            <form method="POST" action="{{route('handleLogin')}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="text">Nom d'utilisateur</label>
                    <div class="input-with-icon">
                        <i class="input-icon fas fa-at"></i>
                        <input type="text" id="email" name="user_name" placeholder="Entrez votre nom d'utilisateur">
                        @error('user_name')
                            <div class="error-message" style="color: rgb(184, 8, 8)">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="input-with-icon">
                        <i class="input-icon fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe">
                        @error('password')
                            <div class="error-message" style="color: rgb(184, 8, 8)">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                        <i class="password-toggle fas fa-eye" onclick="togglePassword()"></i>
                    </div>
                </div>
                
                <div class="remember-forgot">
                    <label class="remember">
                        <input type="checkbox"> Se souvenir de moi
                    </label>
                    <a href="#" class="forgot-link">Mot de passe oublié?</a>
                </div>
                
                <button type="submit" class="login-button">Se connecter</button>
            </form>
            
            <div class="signup-link">
                Pas encore de compte? <a href="{{route('register')}}">S'inscrire</a>
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