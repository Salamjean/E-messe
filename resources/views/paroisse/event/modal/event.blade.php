<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true"
    style="justify-content: right !important;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form id="eventForm" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="event_id">

                <div class="modal-header bg-dark text-white" style="background-color: #5ea7b5 !important;">
                    <h5 class="modal-title" id="eventModalLabel"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="titre" class="form-label fw-semibold">Titre <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="titre" name="titre" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="type_event" class="form-label fw-semibold">Type d'événement <span
                                    class="text-danger">*</span></label>
                            <select id="type_event" name="type_event" class="form-select" required>
                                <option value="">-- Sélectionner un type --</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="date_debut" class="form-label fw-semibold">Date de début <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local" id="date_debut" name="date_debut" class="form-control"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label for="date_fin" class="form-label fw-semibold">Date de fin <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local" id="date_fin" name="date_fin" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for="lieu" class="form-label fw-semibold">Lieu <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="lieu" name="lieu" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="celebrant" class="form-label fw-semibold">Célébrant</label>
                            <input type="text" id="celebrant" name="celebrant" class="form-control">
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea id="description" name="description" rows="4" class="form-control"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="participation_frais" class="form-label fw-semibold">Frais (FCFA)</label>
                            <input type="number" id="participation_frais" name="participation_frais" step="0.01"
                                min="0" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="image" class="form-label fw-semibold">Image</label>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <!-- Bouton Annuler avec icône "close" -->
                    <button type="button" class="btn btn-light border" style="color: #c5c5c7; border:none;"
                        data-bs-dismiss="modal">
                        <i class="material-icons align-middle me-1">cancel</i> Annuler
                    </button>

                    <!-- Bouton Sauvegarder avec icône "save" -->
                    <button type="submit" class="btn btn-save" id="saveBtn"
                        style="background-color: #c49d54; border:none;">
                        <i class="material-icons align-middle me-1">save</i> Créer l'évènement
                    </button>
                </div>

        </div>
        </form>
    </div>
</div>
</div>

<script>
    // Empêche la sélection de dates passées
    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        // Format YYYY-MM-DDTHH:MM pour datetime-local
        const formattedNow = now.toISOString().slice(0, 16);

        document.getElementById('date_debut').setAttribute('min', formattedNow);
        document.getElementById('date_fin').setAttribute('min', formattedNow);
    });
</script>

<style>
    .modal-dialog {
        max-width: 65%;
        /* largeur jusqu'à 90% de l'écran */
        width: auto;
        /* s'adapte au contenu si nécessaire */
        height: 80vh;
        /* hauteur jusqu'à 80% de la fenêtre */
        display: flex;
        align-items: right;
        /* centre verticalement */
    }
</style>
