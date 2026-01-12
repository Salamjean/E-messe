@extends('user.layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('user.settings.index') }}" class="text-decoration-none text-dark">
                    <h5 class="mb-0"><i class="fas fa-arrow-left mr-2"></i> Retour</h5>
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h3 class="text-center mb-5 font-weight-bold">Modifier le profil</h3>

                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="card-body p-5">

                        @if (session('success'))
                            <div class="alert alert-success fade show" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('user.settings.updateProfile') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Profile Picture -->
                            <div class="text-center mb-5">
                                <div class="position-relative d-inline-block">
                                    <div
                                        style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center;">
                                        <img src="{{ $user->profile_picture_url }}" alt="Profile"
                                            style="width: 100%; height: 100%; object-fit: cover;" id="profile-preview">
                                    </div>
                                    <label for="profile_picture"
                                        class="btn btn-warning btn-sm position-absolute rounded-circle shadow-sm"
                                        style="bottom: 0; right: 0; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background-color: #d4a762; border-color: #d4a762;">
                                        <i class="fas fa-camera text-white"></i>
                                    </label>
                                    <input type="file" name="profile_picture" id="profile_picture" class="d-none"
                                        accept="image/*">
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Formats: JPG, PNG, SVG. Max 2 Mo</small>
                                </div>
                            </div>

                            <!-- Nom complet -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-muted small">Nom complet</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i
                                                class="fas fa-user text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-left-0" name="name"
                                        value="{{ old('name', $user->name) }}" placeholder="Nom complet">
                                </div>
                            </div>

                            <!-- Nom d'utilisateur (Prénom usually in this app context based on previous files) -->
                            <!-- Actually 'user_name' is mapped to 'Prénom' in some contexts or just username. The design shows "Eyes Kouassi" which looks like full name. -->
                            <!-- Let's assume 'name' is Name and 'user_name' is Username/Firstname. -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-muted small">Nom d'utilisateur</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i
                                                class="far fa-user text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-left-0" name="user_name"
                                        value="{{ old('user_name', $user->user_name) }}" placeholder="Nom d'utilisateur">
                                </div>
                            </div>


                            <!-- Email -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-muted small">Adresse email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i
                                                class="far fa-envelope text-muted"></i></span>
                                    </div>
                                    <input type="email" class="form-control border-left-0" name="email"
                                        value="{{ old('email', $user->email) }}" placeholder="email@exemple.com">
                                </div>
                            </div>

                            <!-- Téléphone -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-muted small">Numéro de téléphone</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i
                                                class="fas fa-phone-alt text-muted text-small"
                                                style="font-size: 0.9rem;"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-left-0" name="contact"
                                        value="{{ old('contact', $user->contact) }}" placeholder="+225...">
                                </div>
                            </div>

                            <!-- Civilité -->
                            <div class="form-group mb-5">
                                <label class="font-weight-bold text-muted small">Civilité</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i
                                                class="fas fa-user-friends text-muted"></i></span>
                                    </div>
                                    <select class="form-control border-left-0" name="civilite">
                                        <option value="M."
                                            {{ old('civilite', $user->civilite) == 'M.' ? 'selected' : '' }}>M.</option>
                                        <option value="Mme"
                                            {{ old('civilite', $user->civilite) == 'Mme' ? 'selected' : '' }}>Mme
                                        </option>
                                        <option value="Mlle"
                                            {{ old('civilite', $user->civilite) == 'Mlle' ? 'selected' : '' }}>Mlle
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-gold btn-block text-white font-weight-bold py-3"
                                    style="border-radius: 10px;">
                                    Enregistrer les modifications
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus {
            box-shadow: none;
            border-color: #d4a762;
        }

        .input-group-text {
            background-color: transparent;
            border-right: none;
        }

        .form-control {
            border-left: none;
            height: 50px;
        }

        .input-group-text+.form-control {
            border-left: none;
        }

        /* Fix for input group borders */
        .input-group>.form-control:not(:first-child) {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .input-group>.input-group-prepend>.input-group-text {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-right: 0;
        }
    </style>

    <script>
        document.getElementById('profile_picture').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];

                // Vérification de la taille (2 Mo = 2048 * 1024 octets)
                if (file.size > 2048 * 1024) {
                    alert('L\'image est trop volumineuse. La taille maximale est de 2 Mo.');
                    this.value = '';
                    return;
                }

                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
