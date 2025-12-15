@extends('admin.layouts.template')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #181824;
            --primary-dark: #f35525;
            --secondary-color: #333333;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --border-radius: 12px;
            --box-shadow: 0 8px 20px rgba(243, 85, 37, 0.15);
        }

        .content-card {
            max-width: 800px;
            margin: 40px auto;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            border: none;
            transition: transform 0.3s ease;
        }

        .content-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: #5ea7b5 !important;
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
            font-size: 1.8rem;
            position: relative;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 30px;
            background-color: #fff;
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
        }

        .form-control,
        .form-select {
            border: 2px solid #e0e0e0;
            border-radius: var(--border-radius);
            padding: 12px 15px;
            transition: all 0.3s;
            font-size: 0.95rem;
            width: 100%;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-dark);
            box-shadow: 0 0 0 0.25rem rgba(243, 85, 37, 0.25);
        }

        .form-check-input {
            width: 1.5em;
            height: 1.5em;
            margin-top: 0.25em;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .form-check-label {
            margin-left: 8px;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #d4bc8a !important;
            border-color: #d4bc8a !important;
            color: #000 !important;
            border-radius: var(--border-radius);
            padding: 14px 30px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #c5ad7b !important;
            border-color: #c5ad7b !important;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #d9d9d9 !important;
            border-color: #d9d9d9 !important;
            color: #000 !important;
            border-radius: var(--border-radius);
            padding: 14px 30px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary:hover {
            background-color: #cacaca !important;
            border-color: #cacaca !important;
            transform: translateY(-2px);
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
            font-weight: 500;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-message {
            animation: fadeIn 0.5s ease-out;
            border-radius: var(--border-radius);
            margin-bottom: 25px;
        }

        .wave-decoration {
            height: 15px;
            background: linear-gradient(90deg, transparent, var(--primary-dark), transparent);
            opacity: 0.3;
            margin: 20px 0;
            border-radius: 50%;
        }

        @media (max-width: 768px) {
            .content-card {
                margin: 20px 15px;
            }

            .card-body {
                padding: 20px;
            }

            .card-header h3 {
                font-size: 1.5rem;
            }
        }
    </style>

    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card content-card">
                    <div class="card-header text-center">
                        <h3><i class="fas fa-clock"></i> Modifier Horaire de Support</h3>
                    </div>

                    <div class="card-body">
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

                        <form class="needs-validation" method="POST"
                            action="{{ route('content.support-hours.update', $hour->id) }}" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="type" class="form-label">
                                    <i class="fas fa-tag"></i> Type de support
                                </label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Sélectionnez un type</option>
                                    <option value="email" {{ old('type', $hour->type) == 'email' ? 'selected' : '' }}>Email
                                    </option>
                                    <option value="phone" {{ old('type', $hour->type) == 'phone' ? 'selected' : '' }}>
                                        Téléphone</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <i class="fas fa-heading"></i> Titre
                                </label>
                                <input type="text" class="form-control" name="title" id="title"
                                    value="{{ old('title', $hour->title) }}" placeholder="Ex: Support par email" required>
                                @error('title')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="schedule" class="form-label">
                                    <i class="fas fa-calendar-alt"></i> Horaire
                                </label>
                                <input type="text" class="form-control" name="schedule" id="schedule"
                                    value="{{ old('schedule', $hour->schedule) }}" placeholder="Ex: Lun-Ven 9h-17h"
                                    required>
                                @error('schedule')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">
                                    <i class="fas fa-sticky-note"></i> Note (optionnelle)
                                </label>
                                <input type="text" class="form-control" name="note" id="note"
                                    value="{{ old('note', $hour->note) }}" placeholder="Ex: Hors jours fériés">
                                @error('note')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                        value="1" {{ old('is_active', $hour->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-toggle-on"></i> Actif
                                    </label>
                                </div>
                            </div>

                            <div class="wave-decoration"></div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-save me-2"></i> Enregistrer les modifications
                                </button>
                                <a href="{{ route('content.support-hours.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Retour à la liste
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        @if (Session::has('success'))
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: '{{ Session::get('success') }}',
                confirmButtonColor: '#f35525',
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
    </script>
@endsection
