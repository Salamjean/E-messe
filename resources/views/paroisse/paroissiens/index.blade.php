@extends('paroisse.layouts.template')

@section('content')
    @include('paroisse.paroissiens.partials._styles')

    <div class="container-fluid mt-4 pb-5">

        {{-- Affichage des messages flash --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
                style="border-radius: 12px; border-left: 5px solid #27ae60 !important; background: #fff;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle text-success me-3 fa-lg"></i>
                    <div>
                        <strong>Succès !</strong> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- En-tête --}}
        @include('paroisse.paroissiens.partials._header')

        {{-- Filtres --}}
        @include('paroisse.paroissiens.partials._filters')

        {{-- Table --}}
        @include('paroisse.paroissiens.partials._table')

    </div>
@endsection

@push('js')
    @include('paroisse.paroissiens.partials._scripts')
@endpush
