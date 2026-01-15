<div class="row g-4 mb-4">
    @if (Request::routeIs('admin.paroisse.index'))
        <div class="col-md-3">
            <div class="stat-card-modern">
                <div class="stat-icon icon-pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-details">
                    <h4>{{ $stats['total_attente'] }}</h4>
                    <p>En attente</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-modern">
                <div class="stat-icon icon-primary">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-details">
                    <h4>{{ number_format($stats['montant_attente'], 0, ',', ' ') }} <small>FCFA</small></h4>
                    <p>Montant total</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-modern">
                <div class="stat-icon icon-success">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="stat-details">
                    <h4>{{ $stats['total_traite'] }}</h4>
                    <p>Dèjà traités</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-modern">
                <div class="stat-icon icon-danger">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-details">
                    <h4>{{ $stats['total_rejete'] }}</h4>
                    <p>Déjà rejetés</p>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-3">
            <div class="stat-card-modern">
                <div class="stat-icon icon-primary">
                    <i class="fas fa-university"></i>
                </div>
                <div class="stat-details">
                    <h4>{{ $stats['total_virement'] }}</h4>
                    <p>Virements bancaires</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-modern">
                <div class="stat-icon icon-success">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <div class="stat-details">
                    <h4>{{ $stats['total_mobile'] }}</h4>
                    <p>Paiements Mobile</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-modern">
                <div class="stat-icon icon-primary">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="stat-details">
                    <h4>{{ number_format($stats['montant_total_paye'], 0, ',', ' ') }} <small>FCFA</small></h4>
                    <p>Total versé</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-modern">
                <div class="stat-icon icon-pending">
                    <i class="fas fa-history"></i>
                </div>
                <div class="stat-details">
                    <h4>{{ $stats['total_historique'] }}</h4>
                    <p>Total historiques</p>
                </div>
            </div>
        </div>
    @endif
</div>
