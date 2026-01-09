@extends('user.layouts.template')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h2 class="font-weight-bold mb-0" style="color: #1a1a1a;">Mes demandes</h2>
            <a href="{{ route('user.messe.create') }}" class="btn btn-gold btn-new-reservation">
                <i class="mdi mdi-plus me-1"></i> Nouvelle demande
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('user.messe.hold') }}"
                    class="filter-pill {{ Route::is('user.messe.hold') || Route::is('user.messe.index') ? 'active' : '' }}">En
                    cours</a>
                <a href="{{ route('user.messe.historique_messes') }}"
                    class="filter-pill {{ Route::is('user.messe.historique_messes') ? 'active' : '' }}">Historique</a>
                <a href="#" id="btn-favorites" class="filter-pill">Favoris</a>
            </div>
        </div>
    </div>

    <div id="messes-section">
        @if ($messess->isEmpty())
            <div class="row">
                <div class="col-12 text-center py-5">
                    <div class="empty-state-icon mb-3">
                        <i class="mdi mdi-church" style="font-size: 48px; color: #ccc;"></i>
                    </div>
                    <h4 class="text-muted">Aucune célébrée</h4>
                    <p class="text-muted">Vos demandes célébrées apparaitront ici.</p>
                </div>
            </div>
        @else
            <div class="row">
                @foreach ($messess as $messe)
                    <div class="col-md-6 mb-4">
                        <a href="{{ route('user.messe.receipt', ['messe' => $messe->id]) }}" class="text-decoration-none">
                            <div class="card request-card h-100" style="cursor: pointer; transition: transform 0.2s;">
                                <div class="card-body d-flex align-items-start p-4">
                                    <!-- Icon Box -->
                                    <div
                                        class="icon-box me-3 {{ $messe->statut === 'confirmee' || $messe->statut === 'celebre' ? 'bg-icon-dark' : 'bg-icon-light' }}">
                                        @if ($messe->statut === 'confirmee' || $messe->statut === 'celebre')
                                            <i class="mdi mdi-hands-pray text-white" style="font-size: 24px;"></i>
                                        @else
                                            <i class="mdi mdi-plus text-dark" style="font-size: 24px;"></i>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-1 font-weight-bold">
                                            Intention {{ $messe->motif_intention ?? 'Particulière' }}
                                        </h5>
                                        <p class="card-text text-muted mb-1">
                                            {{ $messe->paroisse->name ?? 'Paroisse Inconnue' }}
                                        </p>
                                        <p class="card-text text-muted mb-3">
                                            @if ($messe->date_souhaitee)
                                                {{ \Carbon\Carbon::parse($messe->date_souhaitee)->format('d/m/Y') }}
                                                @if ($messe->heure_souhaitee)
                                                    {{ $messe->heure_souhaitee }}
                                                @endif
                                            @else
                                                Date à définir
                                            @endif
                                        </p>

                                        <!-- Status Badge -->
                                        @php
                                            $badgeClass = '';
                                            $statusText = '';
                                            switch ($messe->statut) {
                                                case 'en attente':
                                                case 'en_attente':
                                                    $badgeClass = 'badge-pastel-red';
                                                    $statusText = 'En attente';
                                                    break;
                                                case 'confirmee':
                                                    $badgeClass = 'badge-pastel-green';
                                                    $statusText = 'Confirmé';
                                                    break;
                                                case 'celebre':
                                                    $badgeClass = 'badge-pastel-blue';
                                                    $statusText = 'Célébré';
                                                    break;
                                                default:
                                                    $badgeClass = 'badge-pastel-gray';
                                                    $statusText = ucfirst($messe->statut);
                                            }
                                        @endphp
                                        <span class="badge rounded-pill {{ $badgeClass }} px-3 py-2 border-0">
                                            {{ $statusText }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @include('user.messe.partials.favorites')
@endsection

@push('styles')
    <style>
        .request-card {
            transition: all 0.3s ease;
        }

        a:has(.request-card):hover .request-card {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        a.text-decoration-none {
            color: inherit;
        }
    </style>
@endpush
