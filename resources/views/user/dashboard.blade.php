@extends('user.layouts.template')

@section('content')
    <!-- Stats Row -->
    <div class="row mb-4">
        <!-- En attente -->
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card card-img-holder text-white"
                style="background-color: #ffc165; border-radius: 15px; border: none;">
                <div class="card-body">
                    <img src="{{ asset('userAssets/assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-rounded inverse-warning-icon p-2 rounded-circle"
                            style="background: rgba(255,255,255,0.2)">
                            <i class="fas fa-clock fa-2x text-white"></i>
                        </div>
                        <h2 class="font-weight-normal mb-0 ms-3">{{ $pendingMesses ?? 0 }}</h2>
                    </div>
                    <h5 class="font-weight-normal mb-0">En attente</h5>
                </div>
            </div>
        </div>

        <!-- Célébrées -->
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card card-img-holder text-white"
                style="background-color: #64e39d; border-radius: 15px; border: none;">
                <div class="card-body">
                    <img src="{{ asset('userAssets/assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-rounded inverse-success-icon p-2 rounded-circle"
                            style="background: rgba(255,255,255,0.2)">
                            <i class="fas fa-church fa-2x text-white"></i>
                        </div>
                        <h2 class="font-weight-normal mb-0 ms-3">{{ $celebratedMesses ?? 0 }}</h2>
                    </div>
                    <h5 class="font-weight-normal mb-0">Célébrées</h5>
                </div>
            </div>
        </div>

        <!-- A venir -->
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card card-img-holder text-white"
                style="background-color: #6a92d8; border-radius: 15px; border: none;">
                <div class="card-body">
                    <img src="{{ asset('userAssets/assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-rounded inverse-info-icon p-2 rounded-circle"
                            style="background: rgba(255,255,255,0.2)">
                            <i class="fas fa-calendar-alt fa-2x text-white"></i>
                        </div>
                        <h2 class="font-weight-normal mb-0 ms-3">{{ $confirmedMesses ?? 0 }}</h2>
                    </div>
                    <h5 class="font-weight-normal mb-0">A venir</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Vos prochaines Messes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="font-weight-bold text-dark mb-0">Vos prochaines Messes</h4>
                <a href="{{ route('user.messe.create') }}" class="btn text-white font-weight-bold"
                    style="background-color: #d4a762; border-radius: 8px;">
                    <i class="fas fa-plus small me-2"></i> Nouvelle demande
                </a>
            </div>

            <div class="row">
                @forelse($upcomingMesses->take(3) ?? [] as $messe)
                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card bg-white"
                            style="border-radius: 15px; border: 1px solid #e3e3e3; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="font-weight-bold text-dark">
                                        {{ \Carbon\Carbon::parse($messe->date_souhaitee)->translatedFormat('l d F') }}
                                    </h6>
                                </div>

                                <h2 class="mb-2 font-weight-bold text-dark" style="font-size: 2rem;">
                                    {{ \Carbon\Carbon::parse($messe->heure_souhaitee)->format('H\hi') }}
                                </h2>

                                <p class="text-muted mb-4 small flex-grow-1">
                                    Pour {{ Str::limit($messe->intention, 50) }}
                                </p>

                                <div class="d-flex justify-content-between align-items-end mt-auto">
                                    <div>
                                        <p class="mb-0 text-muted small">Paroisse Saint-Maria</p>
                                    </div>
                                    <span
                                        class="badge rounded-pill px-3 py-2 
                                        {{ $messe->statut === 'confirmee' ? 'bg-success-light text-success' : '' }}
                                        {{ $messe->statut === 'en_attente' ? 'bg-warning-light text-warning' : '' }}
                                        {{ $messe->statut === 'annulee' ? 'bg-danger-light text-danger' : '' }}"
                                        style="font-size: 0.8rem; font-weight: 600;">
                                        {{ ucfirst($messe->statut) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card bg-white p-5 text-center" style="border-radius: 15px;">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune messe à venir.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Votre Paroisse -->
    {{-- <div class="row">
        <div class="col-12">
            <h4 class="font-weight-bold text-dark mb-3">Votre Paroisse</h4>
            <div class="card bg-white"
                style="border-radius: 15px; border: 1px solid #e3e3e3; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="icon-circle d-flex align-items-center justify-content-center me-4"
                        style="width: 60px; height: 60px; background-color: #add8e6; border-radius: 50%;">
                        <i class="fas fa-map-marker-alt text-white fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="font-weight-bold text-dark mb-1">Abidjan, Yopougon</h5>
                        <p class="text-muted mb-0">Paroisse Saint-Jean</p>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
