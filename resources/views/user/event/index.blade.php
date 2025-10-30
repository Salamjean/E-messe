@extends('user.layouts.template')

@push('css')
{{-- Liens CSS --}}
<link rel="stylesheet" href="{{ asset('css/events.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

@endpush

@section('content')
<div class="container-fluid py-5">
    <!-- En-tête -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-dark"><i class="fa-solid fa-calendar-days me-2"></i>Nos Événements</h2>
        <p class="text-muted">Découvrez et filtrez nos événements à venir et en cours.</p>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('event.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-5">
                    <label for="type_event" class="form-label"><i class="fa-solid fa-filter me-1"></i>Filtrer par type :</label>
                    <select name="type_event" id="type_event" class="form-select">
                        <option value="">Tous les types</option>
                        @foreach ($types as $type)
                            <option value="{{ $type }}" @selected($selected_type == $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <label for="filter_date" class="form-label"><i class="fa-regular fa-calendar me-1"></i>Filtrer par date :</label>
                    <input type="date" name="filter_date" id="filter_date" class="form-control" value="{{ $selected_date }}">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- <!-- Vue boutons -->
    <div class="d-flex justify-content-end mb-3">
        <div class="btn-group" role="group">
            <button type="button" id="card-view-btn" class="btn btn-outline-primary active" onclick="toggleView('card')">
                <i class="fa-solid fa-grip-vertical me-1"></i> Vue Cartes
            </button>
            <button type="button" id="table-view-btn" class="btn btn-outline-primary" onclick="toggleView('table')">
                <i class="fa-solid fa-table me-1"></i> Vue Tableau
            </button>
        </div>
    </div> --}}

    <!-- Vue cartes -->
    <div id="card-view">
        @if($events->isEmpty())
            <div class="empty-state">
                <i class="fa-regular fa-calendar-xmark"></i>
                <h4 class="mt-3">Aucun événement ne correspond à votre recherche.</h4>
                <p class="text-muted">Essayez d'ajuster vos filtres ou <a href="{{ route('user_event.index') }}">réinitialisez-les</a>.</p>
            </div>
        @else
            <div class="event-grid">
                @foreach ($events as $event)
                <div class="card event-card-side" 
                     data-bs-toggle="modal" 
                     data-bs-target="#eventDetailModal"
                     data-title="{{ $event->titre }}"
                     data-description="{{ $event->description ?? 'Aucune description disponible.' }}"
                     data-image="{{ $event->image ? asset('storage/' . $event->image) : 'https://via.placeholder.com/800x600.png?text=Pas+d\'image' }}"
                     data-location="{{ $event->paroisse->nom ?? 'Paroisse non spécifiée' }}"
                     data-dates="{{ \Carbon\Carbon::parse($event->date_debut)->format('d/m/Y') }}{{ $event->date_fin ? ' - ' . \Carbon\Carbon::parse($event->date_fin)->format('d/m/Y') : '' }}"
                     data-celebrant="{{ $event->celebrant ?? 'Non spécifié' }}"
                     data-participation="{{ $event->participation ?? 'Libre' }}">
                    
                    <div class="event-card-image">
                        <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://via.placeholder.com/400x300.png?text=Image' }}" alt="Image de {{ $event->titre }}">
                    </div>
                    <div class="event-card-content">
                        <h5 class="event-card-title">{{ $event->titre }}</h5>
                        <div class="event-card-parish">
                            <i class="fa-solid fa-location-dot"></i> {{ $event->paroisse->nom ?? 'Non spécifiée' }}
                        </div>
                        <p class="text-muted mt-2">{{ \Illuminate\Support\Str::limit($event->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small><i class="fa-regular fa-calendar-days me-1"></i>{{ \Carbon\Carbon::parse($event->date_debut)->format('d/m/Y') }}</small>
                            <span class="badge event-type-badge">{{ $event->type_event }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $events->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Vue tableau -->
    <div id="table-view" style="display: none;">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table id="events-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Titre</th>
                            <th>Paroisse</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                            <th>Type</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                        <tr>
                            <td>
                                <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://via.placeholder.com/50x50.png?text=No+Img' }}" 
                                     alt="{{ $event->titre }}" class="rounded" width="50" height="50" style="object-fit: cover;">
                            </td>
                            <td>{{ $event->titre }}</td>
                            <td>{{ $event->paroisse->nom ?? 'Non spécifiée' }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->date_debut)->format('d/m/Y') }}</td>
                            <td>{{ $event->date_fin ? \Carbon\Carbon::parse($event->date_fin)->format('d/m/Y') : 'N/A' }}</td>
                            <td><span class="badge event-type-badge">{{ $event->type_event }}</span></td>
                            <td>{{ $event->description ?? 'Aucune description' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><i class="fa-solid fa-circle-info me-2"></i>Détails de l'événement</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <img id="modal-image" src="" alt="Image de l'événement" class="img-fluid rounded mb-3" style="display:none;">
            <h4 id="modal-title" class="fw-bold mb-3"></h4>

            <div class="modal-details-grid">
                <div class="modal-detail-item">
                    <i class="fa-regular fa-calendar"></i>
                    <div>
                        <strong>Date(s)</strong>
                        <p id="modal-dates"></p>
                    </div>
                </div>
                <div class="modal-detail-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <div>
                        <strong>Lieu</strong>
                        <p id="modal-location"></p>
                    </div>
                </div>
                <div class="modal-detail-item">
                    <i class="fa-solid fa-user"></i>
                    <div>
                        <strong>Célébrant</strong>
                        <p id="modal-celebrant"></p>
                    </div>
                </div>
                <div class="modal-detail-item">
                    <i class="fa-solid fa-users"></i>
                    <div>
                        <strong>Participation</strong>
                        <p id="modal-participation"></p>
                    </div>
                </div>
            </div>

            <hr>
            <strong>Description :</strong>
            <p id="modal-description" class="mt-2"></p>
        </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="{{ asset('js/events-datatables.js') }}"></script>

@endpush
