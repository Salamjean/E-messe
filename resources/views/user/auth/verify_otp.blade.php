<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification OTP - E-messe</title>
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
            color: var(--primary);
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

        .verification-icon {
            font-size: 60px;
            margin-bottom: 30px;
            color: rgba(255, 255, 255, 0.9);
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
            letter-spacing: 5px;
            text-align: center;
            font-weight: 700;
        }

        .form-group input:focus {
            border-color: var(--primary);
            outline: none;
            background-color: var(--white);
            box-shadow: 0 0 0 3px rgba(205, 164, 94, 0.15);
        }

        .form-group input::placeholder {
            letter-spacing: normal;
            font-weight: normal;
        }

        .verify-button {
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

        .verify-button:hover {
            color: var(--primary);
            background-color: var(--white);
            transform: translateY(-2px);
            border: 2px solid var(--primary);
        }

        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .resend-text {
            text-align: center;
            font-size: 14px;
            color: var(--gray);
        }

        .resend-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .resend-link:hover {
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

            .verify-button {
                padding: 14px;
            }
        }

        /* Animation d'entrée */
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
        <a href="{{ route('forgot-password.form') }}" class="back-button" title="Retour">
            <i class="fas fa-arrow-left"></i>
        </a>

        <div class="left-panel">
            <div class="left-content">
                <div class="verification-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h2>Sécurisez votre compte</h2>
                <p>Nous avons envoyé un code de vérification à votre adresse e-mail pour confirmer votre identité.</p>
            </div>
        </div>

        <div class="right-panel">
            <h2>Vérification OTP</h2>
            <p class="welcome-text">Entrez le code à 6 chiffres reçu par e-mail.</p>

            <form method="POST" action="{{ route('verify-otp.check') }}">
                @csrf
                <input type="hidden" name="email" value="{{ session('email') }}">

                <div class="form-group">
                    <label for="otp">Code de vérification</label>
                    <div class="input-with-icon">
                        <i class="input-icon fas fa-key"></i>
                        <input type="text" id="otp" name="otp" maxlength="6" pattern="[0-9]{6}"
                            placeholder="000000" required autofocus>
                        @error('otp')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="verify-button">
                    <i class="fas fa-check-circle me-2"></i>Vérifier le code
                </button>
            </form>

            <div class="resend-text">
                Vous n'avez pas reçu de code ? <br>
                <a href="{{ route('forgot-password.form') }}" class="resend-link">Réessayer l'envoi</a>
            </div>
        </div>
    </div>
</body>

</html>
