<div class="filter-section">
    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="filter_sexe" class="filter-label">Sexe</label>
            <select id="filter_sexe" class="form-select form-select-modern">
                <option value="">Tous les sexes</option>
                <option value="M">Masculin</option>
                <option value="F">Féminin</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="filter_situation" class="filter-label">Situation Matrimoniale</label>
            <select id="filter_situation" class="form-select form-select-modern">
                <option value="">Toutes les situations</option>
                <option value="Célibataire">Célibataire</option>
                <option value="Marié(e)">Marié(e)</option>
                <option value="Veuf(ve)">Veuf(ve)</option>
                <option value="Divorcé(e)">Divorcé(e)</option>
            </select>
        </div>
        <div class="col-md-6 d-flex justify-content-end gap-2">
            <button id="btn_filter" class="btn-export" style="background-color: var(--secondary-color); color: white;">
                <i class="fas fa-search"></i> Filtrer la liste
            </button>
            <button id="btn_reset" class="btn-export" style="background-color: #ddd; color: #555;">
                <i class="fas fa-undo"></i> Réinitialiser
            </button>
        </div>
    </div>
</div>
