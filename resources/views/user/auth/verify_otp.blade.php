<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .auth-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-container">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Vérification du code</h2>
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verify-otp.check') }}">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('email') }}">
                        
                        <div class="mb-3">
                            <label for="otp" class="form-label">Code OTP à 6 chiffres</label>
                            <input type="text" class="form-control @error('otp') is-invalid @enderror" 
                                   id="otp" name="otp" maxlength="6" pattern="[0-9]{6}" 
                                   placeholder="000000" required>
                            @error('otp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Entrez le code à 6 chiffres envoyé à votre adresse e-mail.
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check me-2"></i>Vérifier le code
                        </button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('forgot-password.form') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>