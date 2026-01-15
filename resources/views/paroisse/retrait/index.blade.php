@extends('paroisse.layouts.template')

@section('content')
    @include('paroisse.retrait.partials._styles')

    <div class="container-fluid mt-4">
        @include('paroisse.retrait.partials._header', [
            'title' => 'Demandes des retraits en attente',
            'subtitle' =>
                'Consultez la liste de vos demandes de retrait en attente, de la paroisse ' .
                Auth::guard('paroisse')->user()->name .
                '!',
            'icon' => 'clock',
        ])

        <div class="row">
            <div class="col-12">
                <div class="card-modern">
                    <div class="card-body-modern">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Vos demandes de retrait en attente</h5>
                            <div class="d-flex gap-2">
                                <a href="{{ route('paroisse.history') }}" class="btn btn-outline-primary rounded-pill"
                                    style="background-color: #f8f9fa; color: var(--dark); border: 2px solid #eef2f6;">
                                    <i class="fas fa-history me-2"></i>Historique
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
