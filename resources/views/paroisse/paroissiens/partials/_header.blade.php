<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <div class="header-icon">
            <i class="fas fa-users-cog fa-lg"></i>
        </div>
        <div>
            <h3 class="mb-0 fw-bold" style="color: var(--secondary-color);">Gestion des Paroissiens</h3>
            <p class="text-muted mb-0">Visualisez et gérez la liste complète des fidèles de votre paroisse</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('paroissien.export.pdf') }}" id="btn-export-pdf" class="btn-export btn-export-pdf"
            target="_blank">
            <i class="fas fa-file-pdf"></i> PDF
        </a>
        <a href="{{ route('paroissien.export.excel') }}" id="btn-export-excel" class="btn-export btn-export-excel">
            <i class="fas fa-file-excel"></i> Excel
        </a>
        <a href="{{ route('paroissien.create') }}" class="btn-export btn-add">
            <i class="fas fa-plus"></i> Nouveau Fidèle
        </a>
    </div>
</div>
