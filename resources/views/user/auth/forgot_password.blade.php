<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - E-messe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('assets/assets/images/logo_principal.svg') }}" />
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
            box-shadow: 0 0 0 3px rgba(205, 164, 94, 0.15);
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
            margin-bottom: 25px;
            letter-spacing: 0.5px;
        }

        .submit-button:hover {
            color: var(--primary);
            background-color: var(--white);
            transform: translateY(-2px);
            border: 2px solid var(--primary);
        }

        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
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
            text-decoration: underline;
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
        <a href="{{ route('login') }}" class="back-button" title="Retour à la connexion">
            <i class="fas fa-arrow-left"></i>
        </a>

        <div class="left-panel">
            <div class="left-content">
                <h2>Récupération de compte</h2>
                <p>Ne vous inquiétez pas, cela arrive aux meilleurs. Entrez votre e-mail pour recevoir un code de
                    réinitialisation.</p>
            </div>
        </div>

        <div class="right-panel">
            <h2>Mot de passe oublié</h2>
            <p class="welcome-text">Nous vous enverrons un code OTP pour vérifier votre identité.</p>

            @if (session('success'))
                <div
                    style="background: rgba(46, 204, 113, 0.1); color: #27ae60; padding: 15px; border-radius: 10px; margin-bottom: 20px; font-size: 14px;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('forgot-password.send') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <div class="input-with-icon">
                        <i class="input-icon fas fa-envelope"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="votre@email.com" required>
                        @error('email')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="submit-button">
                    <i class="fas fa-paper-plane me-2"></i>Envoyer le code OTP
                </button>
            </form>

            <div class="signup-link">
                Vous avez retrouvé votre mot de passe ? <a href="{{ route('login') }}">Se connecter</a>
            </div>
        </div>
    </div>
</body>

</html>
