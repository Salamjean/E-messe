@extends('admin.layouts.template')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #181824;
            --primary-dark: #f35525;
            --border-radius: 12px;
        }

        .content-header {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-dark));
            color: white;
            padding: 25px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(243, 85, 37, 0.2);
        }

        .form-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 10px 15px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-dark);
            box-shadow: 0 0 0 0.2rem rgba(243, 85, 37, 0.25);
        }

        /* Custom color scheme overrides */
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

        .content-header {
            background: #5ea7b5 !important;
        }
    </style>

    <div class="container-fluid">
        <div class="content-header">
            <h2><i class="fas fa-edit me-2"></i> Modifier l'Information de Contact</h2>
        </div>

        <div class="form-card">
            <form action="{{ route('content.contact-infos.update', $info->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">Type *</label>
                        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror"
                            required>
                            <option value="">Sélectionner un type</option>
                            <option value="email" {{ old('type', $info->type) == 'email' ? 'selected' : '' }}>Email
                            </option>
                            <option value="phone" {{ old('type', $info->type) == 'phone' ? 'selected' : '' }}>Téléphone
                            </option>
                            <option value="address" {{ old('type', $info->type) == 'address' ? 'selected' : '' }}>Adresse
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="icon" class="form-label">Icône (FontAwesome) *</label>
                        <input type="text" name="icon" id="icon"
                            class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', $info->icon) }}"
                            placeholder="ex: fas fa-envelope" required>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Exemple: fas fa-envelope, fas fa-phone, fas fa-map-marker-alt</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Titre *</label>
                        <input type="text" name="title" id="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $info->title) }}" placeholder="ex: Écrivez-nous" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="value" class="form-label">Valeur *</label>
                        <input type="text" name="value" id="value"
                            class="form-control @error('value') is-invalid @enderror"
                            value="{{ old('value', $info->value) }}" placeholder="ex: contact@emesse.com" required>
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="subtitle" class="form-label">Sous-titre</label>
                        <input type="text" name="subtitle" id="subtitle"
                            class="form-control @error('subtitle') is-invalid @enderror"
                            value="{{ old('subtitle', $info->subtitle) }}" placeholder="ex: Nous répondons en 24h">
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="is_active" class="form-label">Statut</label>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                                {{ old('is_active', $info->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Actif</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('content.contact-infos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-primary"
                        style="background-color: #f35525; border-color: #f35525;">
                        <i class="fas fa-save me-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
