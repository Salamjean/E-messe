@extends('user.layouts.template')

@push('css')
    {{-- Liens CSS --}}
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@section('content')
    <div class="container-fluid mt-0">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1"><i class="fa-solid fa-calendar-days me-2"></i>Nos Événements</h2>
                <p class="text-muted mb-0">Découvrez et filtrez nos événements à venir et en cours.</p>
            </div>
            {{-- <div class="btn-group shadow-sm p-1 bg-white rounded-3" role="group">
                <button type="button" id="card-view-btn" class="btn btn-light active rounded-2 px-3"
                    onclick="toggleView('card')">
                    <i class="fa-solid fa-grip me-1"></i> Cartes
                </button>
                <button type="button" id="table-view-btn" class="btn btn-light rounded-2 px-3"
                    onclick="toggleView('table')">
                    <i class="fa-solid fa-list me-1"></i> Tableau
                </button>
            </div> --}}
        </div>

        <!-- Filtres -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body">
                <form action="{{ route('user_event.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="row">
                        <div class="col-md-5">
                            <label for="type_event" class="form-label"><i class="fa-solid fa-filter me-1"></i>Filtrer par
                                type
                                :</label>
                            <select name="type_event" id="type_event" class="form-select">
                                <option value="">Tous les types</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type }}" @selected($selected_type == $type)>{{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="filter_date" class="form-label"><i class="fa-regular fa-calendar me-1"></i>Filtrer
                                par
                                date :</label>
                            <input type="date" name="filter_date" id="filter_date" class="form-control"
                                value="{{ $selected_date }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-dark"
                                style="width: 100%; background-color: #c49d54; border-color: #c49d54">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Vue cartes -->
        <div id="card-view">
            @if ($events->isEmpty())
                <div class="empty-state">
                    <i class="fa-regular fa-calendar-xmark"></i>
                    <h4 class="mt-3">Aucun événement ne correspond à votre recherche.</h4>
                    <p class="text-muted">Essayez d'ajuster vos filtres ou <a
                            href="{{ route('user_event.index') }}">réinitialisez-les</a>.</p>
                </div>
            @else
                <div class="event-grid">
                    @foreach ($events as $event)
                        <div class="card event-card-side h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                            <div class="position-relative">
                                <img src="{{ $event->image_url ?? 'https://via.placeholder.com/400x300.png?text=Image' }}"
                                    class="w-100" style="object-fit: cover; height: 250px;"
                                    alt="Image de {{ $event->titre }}">
                                <span
                                    class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill fw-bold shadow-sm">
                                    {{ $event->type_event }}
                                </span>
                            </div>
                            <div class="card-body p-4">
                                <h4 class="card-title fw-bold text-dark mb-3">{{ $event->titre }}</h4>

                                <div class="row g-3 mb-4">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-box-mini bg-light text-primary rounded-3 p-2 me-2">
                                                <i class="fa-regular fa-calendar-days"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block fw-bold uppercase"
                                                    style="font-size: 0.6rem;">DATE & HEURE</small>
                                                <span class="small fw-semibold d-block">
                                                    {{ \Carbon\Carbon::parse($event->date_debut)->format('d/m/Y H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-box-mini bg-light text-danger rounded-3 p-2 me-2">
                                                <i class="fa-solid fa-location-dot"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block fw-bold uppercase"
                                                    style="font-size: 0.6rem;">LIEU</small>
                                                <span class="small fw-semibold d-block text-truncate"
                                                    title="{{ $event->lieu ?: $event->paroisse->name ?? 'Non spécifiée' }}">
                                                    {{ $event->lieu ?: $event->paroisse->name ?? 'Non spécifiée' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-box-mini bg-light text-success rounded-3 p-2 me-2">
                                                <i class="fa-solid fa-user-tie"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block fw-bold uppercase"
                                                    style="font-size: 0.6rem;">CÉLÉBRANT</small>
                                                <span
                                                    class="small fw-semibold d-block text-truncate">{{ $event->celebrant ?: 'Non spécifié' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-box-mini bg-light text-info rounded-3 p-2 me-2">
                                                <i class="fa-solid fa-ticket"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block fw-bold uppercase"
                                                    style="font-size: 0.6rem;">PARTICIPATION</small>
                                                <span
                                                    class="small fw-semibold d-block">{{ $event->participation_frais ? number_format($event->participation_frais, 0, ',', ' ') . ' FCFA' : 'Libre' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="description-section-mini p-3 bg-light rounded-3">
                                    <small class="text-muted d-block fw-bold uppercase mb-2"
                                        style="font-size: 0.6rem;">DESCRIPTION</small>
                                    <p class="card-text small text-muted mb-0"
                                        style="text-align: justify; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $event->description }}
                                    </p>
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
                    <table id="events-table" class="table table-striped table-bordered dt-responsive nowrap"
                        style="width:100%">
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
                                        <img src="{{ $event->image_url ?? asset('assets/images/placeholder-event.jpg') }}"
                                            alt="{{ $event->titre }}" class="rounded" width="50" height="50"
                                            style="object-fit: cover;">
                                    </td>
                                    <td>{{ $event->titre }}</td>
                                    <td>{{ $event->paroisse->nom ?? 'Non spécifiée' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($event->date_debut)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $event->date_fin ? \Carbon\Carbon::parse($event->date_fin)->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                    <td><span class="badge event-type-badge">{{ $event->type_event }}</span></td>
                                    <td>{{ \Illuminate\Support\Str::limit($event->description, 100) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
    <script src="{{ asset('js/user/events.js') }}"></script>
@endpush
```
