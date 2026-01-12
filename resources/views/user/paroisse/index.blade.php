@extends('user.layouts.template')

@section('content')
    <div class="container-fluid px-0">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h3 class="fw-bold text-dark">Rechercher une paroisse</h3>
                <div class="settings-icon">
                    <i class="fas fa-cog fa-lg text-secondary"></i>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <form action="{{ route('user.paroisse.index') }}" method="GET">
                    <div class="input-group bg-white rounded-pill p-2 shadow-sm">
                        <span class="input-group-text bg-transparent border-0 ps-3">
                            <i class="icon-magnifier text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-0 bg-transparent shadow-none"
                            placeholder="Paroisse, ville" value="{{ request('search') }}">
                    </div>
                </form>
            </div>
        </div>

        <h5 class="fw-bold text-dark mb-4">Paroisses Inscrites</h5>

        <div class="row">
            @forelse($paroisses as $paroisse)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card parish-card border-0 shadow-sm h-100 position-relative" style="border-radius: 15px;">
                        <button class="btn btn-link position-absolute top-0 end-0 p-3 favorite-btn"
                            data-id="{{ $paroisse->id }}"
                            style="z-index: 10; color: {{ in_array($paroisse->id, $favorites ?? []) ? '#CCA457' : '#ccc' }};">
                            <i class="{{ in_array($paroisse->id, $favorites ?? []) ? 'fas' : 'far' }} fa-star fa-lg"></i>
                        </button>

                        <div class="card-body d-flex flex-row align-items-center p-3">
                            <div class="parish-image me-3">
                                <img src="{{ $paroisse->profile_picture_url }}" alt="{{ $paroisse->name }}"
                                    class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                            <div class="parish-info flex-grow-1">
                                <h6 class="fw-bold mb-1 text-dark">{{ $paroisse->name }}</h6>
                                <div class="d-flex flex-column">
                                    <small class="text-muted mb-1">
                                        {{ $paroisse->name ?? 'Localisation inconnue' }}
                                        <span class="mx-1">•</span> 12 km
                                    </small>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i> Aujourd'hui 8:00
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 p-3 pt-0">
                            <a href="{{ route('user.paroisse.show', $paroisse->id) }}"
                                class="btn btn-warning text-white w-100 rounded-3 text-uppercase fw-bold"
                                style="background-color: #CCA457; border: none;">
                                Détails
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info border-0 shadow-sm rounded-3">Aucune paroisse trouvée.</div>
                </div>
            @endforelse
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

    <style>
        /* Custom Styles for this page matching the design */
        body {
            background-color: #f5f5f5;
        }

        .parish-card {
            background-color: #fff;
            transition: transform 0.2s;
        }

        .parish-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05) !important;
        }

        .form-control::placeholder {
            color: #adb5bd;
        }

        .btn-warning:hover {
            background-color: #b8934b !important;
        }
    </style>
@endsection
