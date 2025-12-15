<!-- Section Favoris (Initialement cachée) -->
<div id="favorites-section" style="display: none;">
    @if (isset($favorites) && $favorites->isNotEmpty())
        <div class="row">
            @foreach ($favorites as $paroisse)
                <div class="col-md-6 mb-4">
                    <div class="card parish-card border-0 shadow-sm h-100" style="border-radius: 15px;">
                        <div class="card-body d-flex flex-row align-items-center p-3">
                            <div class="parish-image me-3">
                                <img src="{{ $paroisse->profile_picture ? asset('storage/' . $paroisse->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($paroisse->name) . '&background=random' }}"
                                    alt="{{ $paroisse->name }}" class="rounded-circle"
                                    style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                            <div class="parish-info flex-grow-1">
                                <h6 class="fw-bold mb-1 text-dark">{{ $paroisse->name }}</h6>
                                <div class="d-flex flex-column">
                                    <small class="text-muted mb-1">
                                        {{ $paroisse->name ?? 'Localisation inconnue' }}
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
            @endforeach
        </div>
    @else
        <div class="row">
            <div class="col-12 text-center py-5">
                <div class="empty-state-icon mb-3">
                    <i class="far fa-star" style="font-size: 48px; color: #ccc;"></i>
                </div>
                <h4 class="text-muted">Aucun favori</h4>
                <p class="text-muted">Vos paroisses favorites apparaitront ici.</p>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnFavorites = document.getElementById('btn-favorites');
        // We use a query selector for the messes section to handle varying IDs if necessary, or assume a standard one
        const messesSection = document.getElementById('messes-section') || document.querySelector(
            '[id^="messes-"]');
        const favoritesSection = document.getElementById('favorites-section');
        const filterPills = document.querySelectorAll('.filter-pill');

        // Helper to switch tabs
        function showFavorites(e) {
            e.preventDefault();
            if (messesSection) messesSection.style.display = 'none';
            if (favoritesSection) favoritesSection.style.display = 'block';

            filterPills.forEach(pill => pill.classList.remove('active'));
            if (btnFavorites) btnFavorites.classList.add('active');
        }

        // Helper to show messes (attached to "En cours" / "Historique" pills via their logic or generic handler)
        function showMesses() {
            if (favoritesSection && favoritesSection.style.display === 'block') {
                if (messesSection) messesSection.style.display = 'block';
                if (favoritesSection) favoritesSection.style.display = 'none';
            }
        }

        if (btnFavorites) {
            btnFavorites.addEventListener('click', showFavorites);
        }

        // Attach generic listener to other pills to switch back if they are clicked
        filterPills.forEach(pill => {
            if (pill !== btnFavorites) {
                pill.addEventListener('click', function(e) {
                    // Logic: If we are in Favorites view, clicking another tab should probably just navigate 
                    // (since they are 'href' links). BUT the user interaction implies instant tab switching feel.
                    // However, 'En cours' and 'Historique' are separate routes. 
                    // So navigation will happen naturally. 
                    // The only edge case is if we use AJAX. Assuming full page load for now, 
                    // this script mostly handles the *current* page's "Favorites" button usage.

                    // If we want to simulate the toggle BEFORE navigation (or if no nav happens):
                    showMesses();
                });
            }
        });
    });
</script>
