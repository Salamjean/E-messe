@extends('admin.layouts.template')

@section('content')
    <style>
        .btn-primary {
            background-color: #d4bc8a !important;
            border-color: #d4bc8a !important;
            color: #000 !important;
        }

        .btn-primary:hover {
            background-color: #c5ad7b !important;
            border-color: #c5ad7b !important;
        }

        .btn-secondary {
            background-color: #d9d9d9 !important;
            border-color: #d9d9d9 !important;
            color: #000 !important;
        }

        .btn-secondary:hover {
            background-color: #cacaca !important;
            border-color: #cacaca !important;
        }

        .card-header {
            background-color: #5ea7b5 !important;
            color: white !important;
        }

        thead th {
            background-color: #5ea7b5 !important;
            color: white !important;
        }
    </style>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            {{-- Corrected header text to reflect the form's purpose --}}
                            <h6>Ajouter un Horaire de Support</h6>
                            <a href="{{ route('content.support-hours.index') }}" class="btn btn-secondary btn-sm ms-auto">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" method="POST" action="{{ route('content.support-hours.store') }}"
                            novalidate>
                            @csrf

                            {{-- Type de support --}}
                            <div class="mb-3">
                                <label for="type" class="form-label">
                                    <i class="fas fa-tag"></i> Type de support
                                </label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type"
                                    name="type" required>
                                    <option value="">Sélectionnez un type</option>
                                    <option value="email" {{ old('type') == 'email' ? 'selected' : '' }}>Email</option>
                                    <option value="phone" {{ old('type') == 'phone' ? 'selected' : '' }}>Téléphone</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Titre --}}
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <i class="fas fa-heading"></i> Titre
                                </label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    name="title" id="title" value="{{ old('title') }}"
                                    placeholder="Ex: Support par email" required>
                                @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Horaire --}}
                            <div class="mb-3">
                                <label for="schedule" class="form-label">
                                    <i class="fas fa-calendar-alt"></i> Horaire
                                </label>
                                <input type="text" class="form-control @error('schedule') is-invalid @enderror"
                                    name="schedule" id="schedule" value="{{ old('schedule') }}"
                                    placeholder="Ex: Lun-Ven 9h-17h" required>
                                @error('schedule')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Note (optionnelle) --}}
                            <div class="mb-3">
                                <label for="note" class="form-label">
                                    <i class="fas fa-sticky-note"></i> Note (optionnelle)
                                </label>
                                <input type="text" class="form-control @error('note') is-invalid @enderror"
                                    name="note" id="note" value="{{ old('note') }}"
                                    placeholder="Ex: Hors jours fériés">
                                @error('note')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Statut Actif --}}
                            <div class="mb-3">
                                <div class="form-check form-switch"> {{-- Using form-switch for a toggle-like checkbox --}}
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                        value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-toggle-on"></i> Actif
                                    </label>
                                </div>
                            </div>

                            <div class="wave-decoration"></div> {{-- Placeholder for custom styling --}}

                            {{-- Boutons d'action --}}
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end"> {{-- Align buttons to the end --}}
                                <button class="btn btn-primary me-md-2" type="submit">
                                    <i class="fas fa-save me-2"></i> Ajouter l'horaire
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
@endsection

{{-- Script for form validation --}}
<script>
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
</script>
