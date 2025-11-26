@extends('admin.layouts.template')
@section('content')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
  :root {
    --primary-color: #181824;
    --primary-dark: #f35525;
    --secondary-color: #333333;
    --light-color: #f8f9fa;
    --dark-color: #212529;
    --border-radius: 12px;
    --box-shadow: 0 8px 20px rgba(243,85,37, 0.15);
  }

  .signup-card {
    max-width: 1000px;
    margin: 40px auto;
    background-color: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    overflow: hidden;
    border: none;
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
    background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, transparent 100%);
  }

  .card-header h3 {
    font-weight: 700;
    margin: 0;
    font-size: 1.8rem;
    position: relative;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
  }

  .card-body {
    padding: 30px;
    background-color: #fff;
    text-align: center;
  }

  .form-label {
    font-weight: 600;
    color: var(--secondary-color);
    margin-bottom: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .form-label i {
    margin-right: 8px;
    color: var(--primary-dark);
  }

  .form-control {
    border: 2px solid #e0e0e0;
    border-radius: var(--border-radius);
    padding: 12px 0px;
    transition: all 0.3s;
    font-size: 0.95rem;
    width: 100%;
  }

  .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(243,85,37, 0.25);
  }

  .btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-color));
    color: white;
    border: none;
    border-radius: var(--border-radius);
    padding: 14px 0;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s;
    width: 100%;
    margin-top: 10px;
    box-shadow: 0 4px 8px rgba(243,85,37, 0.3);
    cursor: pointer;
  }

  .btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-color));
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(243,85,37, 0.4);
    color: white;
  }

  .btn-primary:active {
    transform: translateY(0);
  }

  .invalid-feedback {
    color: #dc3545;
    font-size: 0.85rem;
    margin-top: 5px;
    font-weight: 500;
  }

  /* Section photo de profil */
  .profile-picture-section {
    grid-column: 1 / -1;
    margin: 20px 0;
    padding: 20px;
    border-radius: var(--border-radius);
    background-color: #f9f9f9;
    border: 2px dashed #e0e0e0;
    transition: all 0.3s ease;
    text-align: center;
  }

  .profile-picture-section:hover {
    border-color: var(--primary-dark);
    background-color: #fef7f5;
  }

  .profile-picture-label {
    font-weight: 600;
    color: var(--secondary-color);
    margin-bottom: 15px;
    display: block;
    font-size: 1.1rem;
  }

  .profile-upload-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
  }

  .profile-preview {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid var(--primary-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f0f0f0;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  }

  .profile-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .profile-preview .placeholder {
    color: #999;
    font-size: 3rem;
  }

  .profile-upload-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-dark));
    color: white;
    border: none;
    border-radius: var(--border-radius);
    padding: 12px 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 8px rgba(243,85,37, 0.3);
  }

  .profile-upload-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(243,85,37, 0.4);
  }

  .profile-info {
    font-size: 0.85rem;
    color: #666;
    margin-top: 10px;
  }

  /* Animation pour les messages flash */
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .alert-message {
    animation: fadeIn 0.5s ease-out;
    border-radius: var(--border-radius);
    margin-bottom: 25px;
    border-left: 4px solid var(--primary-color);
  }

  /* Style modernisé pour SweetAlert */
  .swal2-popup {
    border-radius: var(--border-radius) !important;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
  }

  .swal2-title {
    color: var(--secondary-color) !important;
  }

  .swal2-confirm {
    background-color: var(--primary-color) !important;
    color: var(--secondary-color) !important;
  }

  /* Effet de vague décoratif */
  .wave-decoration {
    height: 15px;
    background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
    opacity: 0.3;
    margin: 20px 0;
    border-radius: 50%;
  }

  /* Section en deux colonnes */



  /* .column {
    display: flex;
    flex-direction: column;
  } */

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .signup-card {
      margin: 20px 15px;
      border-radius: 12px;
    }
    
    .card-body {
      padding: 20px;
    }
    
    .card-header h3 {
      font-size: 1.5rem;
    }



    .profile-upload-container {
      flex-direction: column;
    }
  }
</style>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card signup-card">
        <div class="card-header text-center">
          <h3><i class="fas fa-church"></i> Enregistrer une paroisse</h3>
        </div>
        
        <div class="card-body">
          @if (Session::get('success1'))
            <div class="alert alert-success alert-message">
              {{ Session::get('success1') }}
            </div>
          @endif

          @if (Session::get('success'))
            <div class="alert alert-success alert-message">
              {{ Session::get('success') }}
            </div>
          @endif

          @if (Session::get('error'))
            <div class="alert alert-danger alert-message">
              {{ Session::get('error') }}
            </div>
          @endif

          <div class="wave-decoration"></div>

          <form class="needs-validation" method="POST" enctype="multipart/form-data" action="{{ route('paroisse.store') }}" novalidate>
            @csrf
            @method('POST')

              <!-- Première colonne -->
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="validationCustom001" class="form-label">
                    <i class="fas fa-church"></i> Paroisse
                  </label>
                  <input type="text" class="form-control" name="name" id="validationCustom001" placeholder="Entrez la Paroisse" required>
                  @error('name')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>

                <div class="col-md-4 mb-3">
                  <label for="validationCustom004" class="form-label">
                    <i class="fas fa-phone"></i> Contact 1 de la paroisse
                  </label>
                  <input type="text" class="form-control" name="contact" id="validationCustom004" placeholder="Prémier numéro de téléphone" required>
                  @error('contact')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>

                <div class="col-md-4 mb-3">
                  <label for="validationCustom004" class="form-label">
                    <i class="fas fa-phone"></i> Contact 2
                  </label>
                  <input type="text" class="form-control" name="tel" placeholder="Deuxième numéro de téléphone" required>
                  @error('contact')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
              <!-- Deuxième colonne -->
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="validationCustom003" class="form-label">
                    <i class="fas fa-envelope"></i> Email de la paroisse
                  </label>
                  <input type="email" class="form-control" name="email" id="validationCustom003" placeholder="Entrez email de la paroisse" required>
                  @error('email')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
                <div class="col-md-4 mb-3">
                  <label for="ville_id" class="form-label">
                    <i class="fas fa-city"></i> Ville
                  </label>
                  <select class="form-control" id="ville_id" name="ville_id" required>
                    <option value="">Sélectionnez une ville</option>
                    @foreach($villes as $ville)
                      <option value="{{ $ville->id }}">{{ $ville->nom_ville }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="commune_id" class="form-label">
                    <i class="fas fa-map-marker-alt"></i> Commune
                  </label>
                  <select class="form-control" id="commune_id" name="commune_id" required disabled>
                    <option value="">Sélectionnez d'abord une ville</option>
                  </select>
                  @error('commune_id')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
              <!-- Section Photo de Profil -->
              <div class="profile-picture-section">
                <label class="profile-picture-label">
                  <i class="fas fa-camera me-2"></i> Photo de la paroisse
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
                    <input type="file" id="profile_picture" name="profile_picture" class="d-none" accept="image/*">
                    <div class="profile-info">
                      Formats acceptés: JPG, PNG, GIF • Taille max: 2MB
                    </div>
                    @error('profile_picture')
                      <div class="invalid-feedback d-block">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
              </div>

              <div class="wave-decoration"></div>

              <div class="d-grid mt-4">
                <button class="btn btn-primary" type="submit">
                  <i class="fas fa-plus-circle me-2"></i> Créer le compte
                </button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Validation du formulaire
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
    @if(Session::has('success'))
      Swal.fire({
        icon: 'success',
        title: 'Succès',
        text: '{{ Session::get('success') }}',
        confirmButtonColor: '#f9cf03',
        background: '#ffffff',
        timer: 3000
      });
    @endif

    @if(Session::has('error'))
      Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: '{{ Session::get('error') }}',
        confirmButtonColor: '#f9cf03',
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
    document.addEventListener('DOMContentLoaded', function () {
      const villeSelect = document.getElementById('ville_id');
      const communeSelect = document.getElementById('commune_id');

      villeSelect.addEventListener('change', function() {
        const villeId = this.value;
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
              communeSelect.innerHTML += `<option value="${commune.id}">${commune.nom_commune}</option>`;
            });
            communeSelect.disabled = false;
          })
          .catch(error => {
            console.error('Erreur:', error);
            communeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
          });
      });
    });
  </script>
@endsection