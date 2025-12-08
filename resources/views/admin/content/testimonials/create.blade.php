@extends('admin.layouts.template')

@section('content')
    <style>
        .btn-primary {
            background-color: #d4bc8a !important;
            border-color: #d4bc8a !important;
            color: #ffffffff !important;
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ajouter un témoignage</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('content.testimonials.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="name">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="location">Localisation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                    id="location" name="location" value="{{ old('location') }}" required>
                                @error('location')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="message">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4"
                                    required>{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="rating">Note <span class="text-danger">*</span></label>
                                <select class="form-control @error('rating') is-invalid @enderror" id="rating"
                                    name="rating" required>
                                    <option value="">Sélectionner une note</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                            {{ $i }} étoile{{ $i > 1 ? 's' : '' }}
                                        </option>
                                    @endfor
                                </select>
                                @error('rating')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="display_on">Afficher sur <span class="text-danger">*</span></label>
                                <select class="form-control @error('display_on') is-invalid @enderror" id="display_on"
                                    name="display_on" required>
                                    <option value="">Sélectionner une page</option>
                                    <option value="home" {{ old('display_on') == 'home' ? 'selected' : '' }}>Accueil
                                    </option>
                                    <option value="avantages" {{ old('display_on') == 'avantages' ? 'selected' : '' }}>
                                        Avantages</option>
                                    <option value="both" {{ old('display_on') == 'both' ? 'selected' : '' }}>Les deux
                                    </option>
                                </select>
                                @error('display_on')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Actif</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer
                                </button>
                                <a href="{{ route('content.testimonials.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
