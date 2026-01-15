<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - E-Messe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        :root {
            --primary: #cca45e;
            --primary-dark: #cca45e;
            --secondary: #2ecc71;
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
                radial-gradient(at 0% 0%, rgba(202, 164, 94, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(202, 164, 94, 0.1) 0px, transparent 50%);
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
            background: linear-gradient(rgba(60, 54, 54, 0.4), rgba(101, 99, 99, 0.6)), url('{{ asset('assets/assets/images/bgg.jpg') }}');
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
            background: linear-gradient(135deg, rgba(6, 99, 78, 0.4) 0%, transparent 100%);
        }

        .left-content {
            position: relative;
            z-index: 1;
        }

        .left-content h1 {
            font-size: 38px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .badge-admin {
            display: inline-block;
            padding: 6px 12px;
            background: var(--secondary);
            color: white;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            font-size: 16px;
            opacity: 0.9;
        }

        .feature-item i {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: var(--secondary);
        }

        .auth-right {
            flex: 1.2;
            background: var(--light);
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-header {
            margin-bottom: 35px;
        }

        .auth-logo {
            height: 45px;
            margin-bottom: 25px;
        }

        .auth-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .auth-header p {
            color: var(--text-muted);
            font-size: 15px;
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
            box-shadow: 0 0 0 4px rgba(6, 99, 78, 0.1);
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
            padding: 16px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-auth:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(6, 99, 78, 0.2);
        }

        .forgot-password {
            display: block;
            text-align: right;
            margin-top: 10px;
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .alert-modern {
            padding: 16px;
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
        <div class="auth-left">
            <div class="left-content">
                <span class="badge-admin">Administration</span>
                <h1>Supervision &<br>Contrôle.</h1>
                <div class="feature-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Analytique en temps réel</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-users-cog"></i>
                    <span>Gestion des utilisateurs & rôles</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Sécurité & Audit logs</span>
                </div>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-header">
                <img src="{{ asset('assets/assets/images/logo_principal.svg') }}" class="auth-logo" alt="Logo">
                <h2>Connexion Admin</h2>
                <p>Accédez au tableau de bord de supervision.</p>
            </div>

            @if (Session::get('success'))
                <div class="alert-modern alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ Session::get('success') }}</span>
                </div>
            @endif

            @if (Session::get('error'))
                <div class="alert-modern alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ Session::get('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.handleLogin') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Adresse Email</label>
                    <div class="input-group-modern">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="form-control-modern" value="{{ old('email') }}"
                            placeholder="admin@email.com" required>
                    </div>
                    @error('email')
                        <div style="color: #e74c3c; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <div class="input-group-modern">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" class="form-control-modern"
                            placeholder="••••••••" required>
                        <i class="fas fa-eye password-toggle " id="togglePassword"></i>

                    </div>
                    <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                    @error('password')
                        <div style="color: #e74c3c; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-auth">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Accéder au dashboard</span>
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.querySelector('#togglePassword');
            const passwordInput = document.querySelector('#password');

            toggleBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye-slash');
            });

            @if (Session::has('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: '{{ Session::get('success') }}',
                    confirmButtonColor: '#06634e'
                });
            @endif

            @if (Session::has('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: '{{ Session::get('error') }}',
                    confirmButtonColor: '#06634e'
                });
            @endif
        });
    </script>
</body>

</html>
