<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification OTP - Paroisse E-Messe</title>
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
            background: linear-gradient(rgba(194, 163, 103, 0.92), rgba(179, 141, 69, 0.95)), url('{{ asset('assets/assets/images/bggg.jpg') }}');
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
            background: linear-gradient(135deg, rgba(204, 164, 94, 0.3) 0%, transparent 100%);
        }

        .left-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .verification-icon {
            font-size: 75px;
            margin-bottom: 25px;
            color: #ffffff;
            filter: drop-shadow(0 4px 15px rgba(0, 0, 0, 0.2));
        }

        .left-content h1 {
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 15px;
            color: #ffffff;
        }

        .left-content p {
            font-size: 16px;
            opacity: 0.95;
            max-width: 320px;
            margin: 0 auto;
            line-height: 1.5;
        }

        .auth-right {
            flex: 1.2;
            background: var(--light);
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .back-home {
            position: absolute;
            top: 25px;
            left: 25px;
            width: 45px;
            height: 45px;
            background: #ffffff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
            text-decoration: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
            transition: var(--transition);
            z-index: 100;
        }

        .back-home:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .auth-logo {
            height: 85px;
            width: 85px;
            margin: 0 auto 20px auto;
            display: block;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-header h2 {
            font-size: 26px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .auth-header p {
            color: var(--text-muted);
            font-size: 14.5px;
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
            text-align: center;
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
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 6px;
            text-align: center;
            transition: var(--transition);
            background: #fbfbfb;
        }

        .form-control-modern::placeholder {
            letter-spacing: 6px;
            font-weight: 400;
            font-size: 18px;
            opacity: 0.5;
        }

        .form-control-modern:focus {
            border-color: var(--primary);
            background: white;
            outline: none;
            box-shadow: 0 0 0 4px rgba(204, 164, 94, 0.15);
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
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-auth:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(204, 164, 94, 0.25);
        }

        .alert-modern {
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 25px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: rgba(46, 204, 113, 0.1);
            color: #27ae60;
            border-left: 4px solid #27ae60;
        }

        .alert-error {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
            border-left: 4px solid #e74c3c;
        }

        .error-hint {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
            justify-content: center;
        }

        .resend-box {
            text-align: center;
            font-size: 14.5px;
            color: var(--text-muted);
        }

        .resend-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            transition: var(--transition);
        }

        .resend-link:hover {
            text-decoration: underline;
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
        <a href="{{ route('paroisse.forgot-password.form') }}" class="back-home" title="Retour">
            <i class="fas fa-arrow-left"></i>
        </a>

        <div class="auth-left">
            <div class="left-content">
                <div class="verification-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1>Vérification Paroisse</h1>
                <p>Un code de sécurité à 6 chiffres a été envoyé à l'adresse e-mail de votre paroisse pour autoriser la réinitialisation.</p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-header">
                <img src="{{ asset('assets/assets/images/logo_principal.svg') }}" class="auth-logo" alt="Logo">
                <h2>Vérification OTP</h2>
                <p>Saisissez le code à 6 chiffres reçu par e-mail.</p>
            </div>

            @if (session('success'))
                <div class="alert-modern alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert-modern alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('paroisse.verify-otp.check') }}">
                @csrf
                @php
                    $targetEmail = $email ?? session('paroisse_email') ?? session('email') ?? old('email');
                @endphp
                <input type="hidden" name="email" value="{{ $targetEmail }}">

                <div class="form-group">
                    <label class="form-label">Code de vérification (6 chiffres)</label>
                    <div class="input-group-modern">
                        <i class="fas fa-key" style="letter-spacing: normal; font-size: 18px;"></i>
                        <input type="text" name="otp" class="form-control-modern" maxlength="6"
                            pattern="[0-9]{6}" placeholder="••••••" required autofocus autocomplete="one-time-code">
                    </div>
                    @error('otp')
                        <div class="error-hint">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-auth">
                    <i class="fas fa-check-circle"></i>
                    <span>Vérifier le code</span>
                </button>
            </form>

            <div class="resend-box">
                Vous n'avez pas reçu de code ?<br>
                <a href="{{ route('paroisse.forgot-password.form') }}" class="resend-link">Renvoyer un nouveau code</a>
            </div>
        </div>
    </div>
</body>

</html>
