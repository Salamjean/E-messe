@extends('user.layouts.template')

@section('content')
    <div class="container-fluid px-0">
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('user.paroisse.index') }}" class="text-decoration-none text-muted">
                    <i class="fas fa-arrow-left me-2"></i> Retour aux paroisses
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="position-relative" style="height: 200px; background-color: #f8f9fa;">
                        @if ($paroisse->cover_image)
                            <img src="{{ asset('storage/' . $paroisse->cover_image) }}" alt="Couverture"
                                class="w-100 h-100 object-fit-cover">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                <i class="fas fa-church fa-4x text-muted"></i>
                            </div>
                        @endif
                        <button
                            class="btn btn-light position-absolute top-0 end-0 m-3 rounded-circle shadow-sm favorite-btn"
                            data-id="{{ $paroisse->id }}"
                            style="width: 40px; height: 40px; color: {{ $isFavorite ? '#CCA457' : '#ccc' }};">
                            <i class="{{ $isFavorite ? 'fas' : 'far' }} fa-star"></i>
                        </button>
                    </div>

                    <div class="card-body px-4 pb-5">
                        <div class="d-flex align-items-end mt-n5 mb-4 position-relative" style="z-index: 1;">
                            <img src="{{ $paroisse->profile_picture ? asset('storage/' . $paroisse->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($paroisse->name) . '&background=random' }}"
                                alt="{{ $paroisse->name }}" class="rounded-circle border border-4 border-white shadow-sm"
                                style="width: 100px; height: 100px; object-fit: cover; background-color: #fff;">
                            <div class="ms-3 mb-2">
                                <h2 class="fw-bold mb-0 text-dark">{{ $paroisse->name }}</h2>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $paroisse->name ?? 'Localisation inconnue' }}
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="fw-bold mb-3">À propos</h5>
                                <p class="text-muted mb-4">
                                    {{ $paroisse->description ?? 'Aucune description disponible pour cette paroisse.' }}
                                </p>

                                @if ($paroisse->messes->count() > 0)
                                    <h5 class="fw-bold mb-3">Dernières messes célébrées</h5>
                                    <div class="list-group list-group-flush mb-4">
                                        @foreach ($paroisse->messes as $messe)
                                            <div class="list-group-item px-0 border-0 d-flex align-items-center">
                                                <div class="bg-light rounded p-2 me-3 text-center" style="width: 50px;">
                                                    <small
                                                        class="d-block fw-bold text-dark">{{ $messe->created_at->format('d') }}</small>
                                                    <small class="d-block text-uppercase text-muted"
                                                        style="font-size: 0.7em;">{{ $messe->created_at->format('M') }}</small>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $messe->motif_intention }}</h6>
                                                    <small class="text-muted">{{ $messe->celebration_choisie }}</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                                    <h6 class="fw-bold mb-3">Informations</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2 d-flex">
                                            <i class="fas fa-phone me-3 mt-1 text-muted"></i>
                                            <span>{{ $paroisse->contact ?? ($paroisse->tel ?? 'Non renseigné') }}</span>
                                        </li>
                                        <li class="mb-2 d-flex">
                                            <i class="fas fa-envelope me-3 mt-1 text-muted"></i>
                                            <span>{{ $paroisse->email ?? 'Non renseigné' }}</span>
                                        </li>
                                        <li class="mb-2 d-flex">
                                            <i class="fas fa-globe me-3 mt-1 text-muted"></i>
                                            <span>{{ $paroisse->website ?? 'emesse.com' }}</span>
                                        </li>
                                    </ul>
                                </div>

                                <a href="{{ route('user.messe.create') }}?paroisse_id={{ $paroisse->id }}"
                                    class="btn btn-warning w-100 text-white fw-bold text-uppercase py-2"
                                    style="background-color: #CCA457; border: none;">
                                    Demander une messe
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const favoriteBtns = document.querySelectorAll('.favorite-btn');

            favoriteBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parishId = this.dataset.id;
                    const icon = this.querySelector('i');
                    const _token = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');

                    const url = "{{ route('user.paroisse.favorite', ['id' => ':id']) }}".replace(
                        ':id', parishId);

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': _token
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'added') {
                                icon.classList.remove('far');
                                icon.classList.add('fas');
                                this.style.color = '#CCA457';
                            } else {
                                icon.classList.remove('fas');
                                icon.classList.add('far');
                                this.style.color = '#ccc';
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
@endsection
