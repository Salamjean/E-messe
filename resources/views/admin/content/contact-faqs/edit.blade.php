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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Modifier une FAQ de Contact</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('content.contact-faqs.update', $faq) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="question">Question <span class="text-danger">*</span></label>
                                <textarea name="question" id="question" class="form-control @error('question') is-invalid @enderror" rows="3"
                                    required>{{ old('question', $faq->question) }}</textarea>
                                @error('question')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="answer">Réponse <span class="text-danger">*</span></label>
                                <textarea name="answer" id="answer" class="form-control @error('answer') is-invalid @enderror" rows="5"
                                    required>{{ old('answer', $faq->answer) }}</textarea>
                                @error('answer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="order">Ordre d'affichage <span class="text-danger">*</span></label>
                                        <input type="number" name="order" id="order"
                                            class="form-control @error('order') is-invalid @enderror"
                                            value="{{ old('order', $faq->order) }}" required>
                                        @error('order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_active">Statut</label>
                                        <select name="is_active" id="is_active" class="form-control">
                                            <option value="1"
                                                {{ old('is_active', $faq->is_active) == 1 ? 'selected' : '' }}>
                                                Actif
                                            </option>
                                            <option value="0"
                                                {{ old('is_active', $faq->is_active) == 0 ? 'selected' : '' }}>
                                                Inactif
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer les modifications
                                </button>
                                <a href="{{ route('content.contact-faqs.index') }}" class="btn btn-secondary">
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
