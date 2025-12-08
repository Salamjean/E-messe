@extends('admin.layouts.template')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #181824;
            --primary-dark: #5ea7b5 !important;
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
            border: 1px solid #ddd;
            padding: 10px 15px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-dark);
            box-shadow: 0 0 0 0.2rem rgba(243, 85, 37, 0.15);
        }

        .btn-primary {
            background: var(--primary-dark);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background: #d4461f;
        }
    </style>

    <div class="container-fluid">
        <div class="content-header">
            <h2><i class="fas fa-plus-circle me-2"></i> Ajouter un Impact</h2>
        </div>

        <div class="form-card">
            <form action="{{ route('content.advantage-impacts.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="value" class="form-label">Valeur <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('value') is-invalid @enderror" id="value"
                        name="value" value="{{ old('value') }}" placeholder="Ex: 95%" required>
                    @error('value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="label" class="form-label">Label <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('label') is-invalid @enderror" id="label"
                        name="label" value="{{ old('label') }}" placeholder="Ex: de satisfaction" required>
                    @error('label')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="order" class="form-label">Ordre d'affichage <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('order') is-invalid @enderror" id="order"
                        name="order" value="{{ old('order', 1) }}" min="1" required>
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Actif
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Enregistrer
                    </button>
                    <a href="{{ route('content.advantage-impacts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
