@extends('admin.layouts.template')

@section('content')
    <style>
        :root {
            --primary-color: #181824;
            --primary-dark: #f35525;
            --secondary-color: #333333;
            --light-color: #f8f9fa;
            --border-radius: 12px;
            --box-shadow: 0 8px 20px rgba(243, 85, 37, 0.15);
        }

        .signup-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: none;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .signup-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-dark));
            color: white;
            padding: 25px 30px;
            border-bottom: none;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, transparent 100%);
        }

        .card-header h3 {
            font-weight: 700;
            margin: 0;
            font-size: 1.5rem;
            position: relative;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .form-label i {
            margin-right: 8px;
            color: var(--primary-dark);
            width: 20px;
            text-align: center;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: var(--border-radius);
            padding: 12px 15px;
            transition: all 0.3s;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(243, 85, 37, 0.25);
        }

        /* Profile Picture Section */
        .profile-picture-section {
            background-color: #f9f9f9;
            border: 2px dashed #e0e0e0;
            border-radius: var(--border-radius);
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .profile-picture-section:hover {
            border-color: var(--primary-dark);
            background-color: #fef7f5;
        }

        .profile-upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        @media (min-width: 768px) {
            .profile-upload-container {
                flex-direction: row;
                justify-content: center;
                text-align: left;
            }
        }

        .profile-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid var(--primary-dark);
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .profile-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-preview .placeholder {
            color: #999;
            font-size: 2.5rem;
        }

        .profile-upload-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-dark));
            color: white;
            border: none;
            border-radius: var(--border-radius);
            padding: 10px 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 8px rgba(243, 85, 37, 0.3);
            margin-bottom: 5px;
        }

        .profile-upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(243, 85, 37, 0.4);
            color: white;
        }

        .profile-info {
            font-size: 0.85rem;
            color: #666;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-color));
            color: white;
            border: none;
            border-radius: var(--border-radius);
            padding: 14px 0;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            width: 100%;
            box-shadow: 0 4px 8px rgba(243, 85, 37, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(243, 85, 37, 0.4);
            color: white;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-color));
        }

        .wave-decoration {
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
            opacity: 0.2;
            margin: 30px 0;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card signup-card">
                    <div class="card-header text-center">
                        <h3><i class="fas fa-church me-2"></i> Enregistrer une paroisse</h3>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        @if (Session::get('success1'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                {{ Session::get('success1') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (Session::get('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                {{ Session::get('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (Session::get('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                {{ Session::get('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form class="needs-validation" method="POST" enctype="multipart/form-data"
                            action="{{ route('paroisse.store') }}" novalidate>
                            @csrf

                            <div class="row g-4">
                                <!-- Paroisse Name -->
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-church"></i> Nom de la paroisse
                                    </label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                        id="name" placeholder="Ex: Saint Michel" required>
                                    @error('name')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Contact 1 -->
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="contact" class="form-label">
                                        <i class="fas fa-phone"></i> Contact 1
                                    </label>
                                    <input type="text" class="form-control" name="contact" id="contact"
                                        placeholder="Numéro principal" value="{{ old('contact') }}" maxlength="14" required>
                                    @error('contact')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Contact 2 -->
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="tel" class="form-label">
                                        <i class="fas fa-mobile-alt"></i> Contact 2
                                    </label>
                                    <input type="text" class="form-control" name="tel" id="tel"
                                        placeholder="Numéro secondaire" value="{{ old('tel') }}" maxlength="14" required>
                                    @error('tel')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope"></i> Email
                                    </label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="email@paroisse.com" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Ville -->
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="ville_id" class="form-label">
                                        <i class="fas fa-city"></i> Ville
                                    </label>
                                    <select class="form-control form-select" id="ville_id" name="ville_id" required>
                                        <option value="">Sélectionnez une ville</option>
                                        @foreach ($villes as $ville)
                                            <option value="{{ $ville->id }}"
                                                {{ old('ville_id') == $ville->id ? 'selected' : '' }}>
                                                {{ $ville->nom_ville }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ville_id')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Commune -->
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="commune_id" class="form-label">
                                        <i class="fas fa-map-marker-alt"></i> Commune
                                    </label>
                                    <select class="form-control form-select" id="commune_id" name="commune_id" required
                                        disabled>
                                        <option value="">Sélectionnez d'abord une ville</option>
                                    </select>
                                    @error('commune_id')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Profile Picture Section -->
                                <div class="col-12">
                                    <div class="profile-picture-section mt-2">
                                        <label class="form-label justify-content-center mb-3">
                                            <i class="fas fa-camera"></i> Photo de la paroisse
                                        </label>

                                        <div class="profile-upload-container">
                                            <div class="profile-preview">
                                                <div class="placeholder">
                                                    <i class="fas fa-church"></i>
                                                </div>
                                            </div>

                                            <div>
                                                <label for="profile_picture" class="profile-upload-btn">
                                                    <i class="fas fa-upload"></i> Choisir une image
                                                </label>
                                                <input type="file" id="profile_picture" name="profile_picture"
                                                    class="d-none" accept="image/*">
                                                <div class="profile-info mt-2">
                                                    JPG, PNG, GIF • Max 2MB
                                                </div>
                                                @error('profile_picture')
                                                    <div class="invalid-feedback d-block">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="wave-decoration"></div>

                            <div class="row">
                                <div class="col-12 col-md-6 offset-md-3">
                                    <button class="btn-submit" type="submit">
                                        <i class="fas fa-check-circle me-2"></i> Créer le compte
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Validation du formulaire Bootstrap
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');

            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Gestion des messages flash avec SweetAlert
        // Note: Swal est déjà chargé dans le template
        @if (Session::has('success'))
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: '{{ Session::get('success') }}',
                confirmButtonColor: '#181824',
                background: '#ffffff',
                timer: 3000
            });
        @endif

        @if (Session::has('error'))
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: '{{ Session::get('error') }}',
                confirmButtonColor: '#f35525',
                background: '#ffffff'
            });
        @endif

        // Gestion de l'aperçu de l'image de profil
        document.getElementById('profile_picture').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.profile-preview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Aperçu de la photo">`;
                }
                reader.readAsDataURL(file);
            }
        });

        // Gestion de la sélection des communes en fonction de la ville
        document.addEventListener('DOMContentLoaded', function() {
            const villeSelect = document.getElementById('ville_id');
            const communeSelect = document.getElementById('commune_id');

            // Si une ville est déjà sélectionnée (ex: old input), charger les communes
            if (villeSelect.value) {
                loadCommunes(villeSelect.value, "{{ old('commune_id') }}");
            }

            villeSelect.addEventListener('change', function() {
                loadCommunes(this.value);
            });

            function loadCommunes(villeId, selectedCommuneId = null) {
                communeSelect.innerHTML = '<option value="">Chargement...</option>';
                communeSelect.disabled = true;

                if (!villeId) {
                    communeSelect.innerHTML = '<option value="">Sélectionnez d\'abord une ville</option>';
                    return;
                }

                fetch(`/admin/get-communes/${villeId}`)
                    .then(response => response.json())
                    .then(data => {
                        communeSelect.innerHTML = '<option value="">Sélectionnez une commune</option>';
                        data.forEach(commune => {
                            const selected = selectedCommuneId == commune.id ? 'selected' : '';
                            communeSelect.innerHTML +=
                                `<option value="${commune.id}" ${selected}>${commune.nom_commune}</option>`;
                        });
                        communeSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        communeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                    });
            }
        });
    </script>
@endsection
