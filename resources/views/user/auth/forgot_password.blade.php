<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - E-Messe</title>
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
            text-align: center;
        }

        .icon-box {
            font-size: 70px;
            margin-bottom: 30px;
            color: var(--primary);
            filter: drop-shadow(0 0 15px rgba(204, 164, 94, 0.3));
        }

        .left-content h1 {
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .left-content p {
            font-size: 17px;
            opacity: 0.9;
            max-width: 320px;
            margin: 0 auto;
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
            box-shadow: 0 10px 20px rgba(204, 164, 94, 0.2);
        }

        .alert-success-modern {
            background: rgba(46, 204, 113, 0.1);
            color: #27ae60;
            padding: 16px;
            border-radius: 14px;
            margin-bottom: 25px;
            font-size: 14.5px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 4px solid #27ae60;
        }

        .error-hint {
            color: #ff4d4d;
            font-size: 13px;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .footer-box {
            text-align: center;
            font-size: 15px;
            color: var(--text-muted);
        }

        .footer-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            transition: var(--transition);
        }

        .footer-link:hover {
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
        <a href="{{ route('login') }}" class="back-home" title="Retour à la connexion">
            <i class="fas fa-arrow-left"></i>
        </a>

        <div class="auth-left">
            <div class="left-content">
                <div class="icon-box">
                    <i class="fas fa-key"></i>
                </div>
                <h1>Accès oublié ?</h1>
                <p>Ne vous inquiétez pas, saisissez votre e-mail et nous vous aiderons à retrouver l'accès à votre
                    compte.</p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-header">
                <h2>Récupération</h2>
                <p>Réinitialisez votre mot de passe en quelques étapes.</p>
            </div>

            @if (session('success'))
                <div class="alert-success-modern">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('forgot-password.send') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Adresse e-mail</label>
                    <div class="input-group-modern">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="form-control-modern" value="{{ old('email') }}"
                            placeholder="jean.dupont@email.com" required>
                    </div>
                    @error('email')
                        <div class="error-hint">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-auth">
                    <i class="fas fa-paper-plane"></i>
                    <span>Envoyer le code OTP</span>
                </button>
            </form>

            <div class="footer-box">
                Vous avez retrouvé votre mot de passe ?<br>
                <a href="{{ route('login') }}" class="footer-link">Retour à la connexion</a>
            </div>
        </div>
    </div>
</body>

</html>
