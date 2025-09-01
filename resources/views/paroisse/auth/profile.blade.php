@extends('paroisse.layouts.template')

@section('content')
<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <h2><i class="fas fa-church"></i> Modifier les informations de la paroisse</h2>
        </div>
        
        <div class="profile-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <i class="fas fa-check-circle"></i>
                    <div>{{ session('success') }}</div>
                    <button class="btn-close"><i class="fas fa-times"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>{{ session('error') }}</div>
                    <button class="btn-close"><i class="fas fa-times"></i></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button class="btn-close"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <form action="{{ route('paroisse.update') }}" method="POST" enctype="multipart/form-data" id="paroisse-form">
                @csrf
                @method('PUT')

                <!-- Photo de profil -->
                <div class="profile-img-section">
                    <div class="img-container">
                        <img src="{{ Auth::guard('paroisse')->user()->profile_picture ? asset('storage/' . Auth::guard('paroisse')->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::guard('paroisse')->user()->name) . '&size=200&background=f35525&color=fff' }}" 
                             alt="Photo de profil" class="profile-img" id="profile-picture-preview">
                        <label for="profile_picture" class="img-upload-btn">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" id="profile_picture" name="profile_picture" class="d-none" accept="image/*">
                    </div>
                    <p class="img-text">Formats supportés: JPG, PNG, SVG. Taille max: 2MB</p>
                </div>
                
                <!-- Informations de la paroisse -->
                <h3 class="section-title"><i class="fas fa-info-circle"></i> Informations de la paroisse</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">Nom de la paroisse</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                               value="{{ old('name', Auth::guard('paroisse')->user()->name) }}" readonly>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="localisation" class="form-label">Localisation</label>
                        <input type="text" class="form-control @error('localisation') is-invalid @enderror" id="localisation" name="localisation" 
                               value="{{ old('localisation', Auth::guard('paroisse')->user()->localisation) }}" required>
                        @error('localisation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" 
                               value="{{ old('email', Auth::guard('paroisse')->user()->email) }}" readonly>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="contact" class="form-label">Numéro de téléphone</label>
                        <input type="text" class="form-control @error('contact') is-invalid @enderror" id="contact" name="contact" 
                               value="{{ old('contact', Auth::guard('paroisse')->user()->contact) }}" required>
                        @error('contact')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                
                <!-- Mot de passe -->
                <h3 class="section-title"><i class="fas fa-lock"></i> Modification du mot de passe</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            <button type="button" class="password-toggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p class="form-text">Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.</p>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            <button type="button" class="password-toggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Confirmation -->
                <h3 class="section-title"><i class="fas fa-shield-alt"></i> Confirmation</h3>
                
                <div class="form-group">
                    <label for="current_password" class="form-label">Mot de passe actuel <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" name="current_password" required>
                        <button type="button" class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="form-text">Entrez votre mot de passe actuel pour confirmer les modifications.</p>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Actions -->
                <div class="form-footer">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Mettre à jour les informations</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    :root {
        --primary: #f35525;
        --dark: #181824;
        --light: #ffffff;
        --gray: #f8f9fa;
        --gray-dark: #eaeaea;
    }
    
    .profile-container {
        padding: 20px;
        background-color: #f5f7fb;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .profile-card {
        width: 100%;
        max-width: 70%;
        background: var(--light);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    
    .profile-header {
        background: var(--dark);
        color: var(--light);
        padding: 25px 30px;
    }
    
    .profile-header h2 {
        font-weight: 600;
        font-size: 24px;
        display: flex;
        align-items: center;
        margin: 0;
    }
    
    .profile-header h2 i {
        margin-right: 10px;
        color: var(--primary);
    }
    
    .profile-body {
        padding: 30px;
    }
    
    .profile-img-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 30px;
    }
    
    .img-container {
        position: relative;
        width: 130px;
        height: 130px;
        margin-bottom: 15px;
    }
    
    .profile-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid var(--gray-dark);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .img-upload-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: var(--primary);
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid white;
        transition: all 0.3s ease;
    }
    
    .img-upload-btn:hover {
        background: #e04a1f;
        transform: scale(1.1);
    }
    
    .img-text {
        font-size: 14px;
        color: #6c757d;
        text-align: center;
    }
    
    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 0px;
    }
    
    .form-group {
        flex: 1 0 calc(50% - 30px);
        margin: 0 10px 10px;
        min-width: 200px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--dark);
    }
    
    .form-control {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid var(--gray-dark);
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(243, 85, 37, 0.2);
    }
    
    .input-group {
        display: flex;
        align-items: center;
    }
    
    .password-toggle {
        background: none;
        border: none;
        margin-left: -40px;
        cursor: pointer;
        color: #6c757d;
        z-index: 5;
        padding: 10px;
    }
    
    .form-text {
        font-size: 13px;
        color: #6c757d;
        margin-top: 5px;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        margin: 30px 0 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--gray-dark);
        color: var(--dark);
        display: flex;
        align-items: center;
    }
    
    .section-title i {
        margin-right: 10px;
        color: var(--primary);
    }
    
    .btn {
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    
    .btn-primary {
        background: var(--primary);
        color: white;
    }
    
    .btn-primary:hover {
        background: #e04a1f;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(243, 85, 37, 0.3);
        color: white;
    }
    
    .btn-secondary {
        background: var(--gray-dark);
        color: var(--dark);
    }
    
    .btn-secondary:hover {
        background: #d8d8d8;
        color: var(--dark);
    }
    
    .form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid var(--gray-dark);
    }
    
    .alert {
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        position: relative;
    }
    
    .alert-success {
        background: #e6f7ee;
        color: #0a7b4c;
        border: 1px solid #a3e6c7;
    }
    
    .alert-danger {
        background: #fde8e8;
        color: #e53e3e;
        border: 1px solid #f8b6b6;
    }
    
    .alert i {
        margin-right: 10px;
        font-size: 20px;
    }
    
    .btn-close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        color: inherit;
    }
    
    .invalid-feedback {
        display: block;
        color: #e53e3e;
        font-size: 13px;
        margin-top: 5px;
    }
    
    .text-danger {
        color: #e53e3e;
    }
    
    @media (max-width: 768px) {
        .form-group {
            flex: 1 0 100%;
        }
        
        .form-footer {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .profile-container {
            padding: 10px;
        }
        
        .profile-body {
            padding: 20px;
        }
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fonctionnalité d'affichage/masquage du mot de passe
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Prévisualisation de l'image
        const profilePictureInput = document.getElementById('profile_picture');
        const profilePicturePreview = document.getElementById('profile-picture-preview');
        
        profilePictureInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Vérification de la taille du fichier
                if (file.size > 2048 * 1024) {
                    alert('Le fichier est trop volumineux. La taille maximale autorisée est de 2MB.');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePicturePreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Fermeture des alertes
        document.querySelectorAll('.btn-close').forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        });
    });
</script>
@endsection