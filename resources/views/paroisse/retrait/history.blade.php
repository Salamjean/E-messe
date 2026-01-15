@extends('paroisse.layouts.template')

@section('content')
    @include('paroisse.retrait.partials._styles')

    <div class="container-fluid mt-4">
        @include('paroisse.retrait.partials._header', [
            'title' => 'Historique des Retraits',
            'subtitle' =>
                "Consultez l'historique de vos demandes de retrait, de la paroisse " .
                Auth::guard('paroisse')->user()->name .
                '!',
            'icon' => 'history',
        ])

        <div class="row">
            <div class="col-12">
                <div class="card-modern">
                    <div class="card-body-modern">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Votre historique de retrait</h5>
                            <div class="d-flex gap-2">
                                <a href="{{ route('paroisse.retraits') }}" class="btn btn-outline-primary rounded-pill"
                                    style="background-color: #f8f9fa; color: var(--dark); border: 2px solid #eef2f6;">
                                    <i class="fas fa-clock me-2"></i>En attente
                                </a>
                                <a href="{{ route('paroisse.retrait.create') }}" class="btn-nouveau">
                                    <i class="fas fa-plus me-2"></i>Nouvelle demande
                                </a>
                            </div>
                        </div>

                        @include('paroisse.retrait.partials._stats')

                        @include('paroisse.retrait.partials._table')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('paroisse.retrait.partials._scripts')
@endsection
