@extends('paroisse.layouts.template')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<style>
.dataTables_processing {
    background: rgba(255,255,255,0.9);
    z-index: 100;
}
</style>
@endsection

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestion des Reversements</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalReversement">
            <i class="fas fa-plus"></i> Nouveau Reversement
        </button>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-reversements" class="table table-striped table-hover align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>Référence</th>
                            <th>Date</th>
                            <th>Destinataire</th>
                            <th>Montant</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajout Reversement -->
<div class="modal fade" id="modalReversement" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-reversement">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Effectuer un transfert Mobile Money</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="alert-msg"></div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Montant (FCFA)</label>
                        <input type="number" class="form-control" name="montant" min="100" placeholder="Ex: 5000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Méthode de Paiement</label>
                        <select class="form-select" name="payment_method">
                            <option value="">-- Choisir (Optionnel) --</option>
                            <option value="MTN">MTN Mobile Money</option>
                            <option value="MOOV">Moov Money</option>
                            <option value="ORANGE">Orange Money</option>
                            <option value="WAVE">Wave</option>
                        </select>
                        <small class="text-muted">Laissez vide pour laisser le destinataire choisir</small>
                    </div>
                    <div class="row g-2">
                        <div class="col-4">
                            <label class="form-label fw-bold">Pays</label>
                            <select class="form-select" name="prefix">
                                <option value="225">CI (+225)</option>
                                <option value="221">SN (+221)</option>
                                <option value="226">BF (+226)</option>
                            </select>
                        </div>
                        <div class="col-8">
                            <label class="form-label fw-bold">Numéro Téléphone</label>
                            <input type="text" class="form-control" name="telephone" placeholder="0707070707" required>
                            <small class="text-muted">Sans l'indicatif pays</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="btn-submit">
                        <span class="spinner-border spinner-border-sm d-none me-1"></span>
                        Transférer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    // Initialisation DataTable avec gestion d'erreurs
    var table = $('#table-reversements').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('reversement.data') }}",
            error: function(xhr, error, thrown) {
                console.error('Erreur DataTable:', error, thrown);
                alert('Erreur lors du chargement des données');
            }
        },
        columns: [
            { data: 'reference', name: 'reference' },
            { data: 'created_at', name: 'created_at' },
            { data: 'numero_destinataire', name: 'numero_destinataire', render: function(data, type, row) {
                return '(+' + row.prefix_pays + ') ' + data;
            }},
            { data: 'montant', name: 'montant', render: $.fn.dataTable.render.number(' ', ',', 0, '', ' FCFA') },
            { data: 'statut', name: 'statut', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']],
        language: {
            processing: "Traitement en cours...",
            zeroRecords: "Aucun reversement trouvé",
            emptyTable: "Aucun reversement disponible"
        }
    });

    // Formulaire avec meilleure gestion d'erreurs
    $('#form-reversement').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let btn = $('#btn-submit');
        let spinner = btn.find('.spinner-border');
        let alertBox = $('#alert-msg');
        
        btn.prop('disabled', true); 
        spinner.removeClass('d-none'); 
        alertBox.html('');

        $.ajax({
            url: "{{ route('reversement.store') }}",
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#modalReversement').modal('hide');
                form[0].reset();
                table.ajax.reload(null, false);
                Swal.fire({
                    icon: 'success', 
                    title: 'Transfert Réussi', 
                    text: response.message || ''
                });
            },
            error: function(xhr) {
                let errorMsg = "Une erreur est survenue.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.status === 500) {
                    errorMsg = "Erreur interne du serveur. Veuillez réessayer.";
                }
                alertBox.html('<div class="alert alert-danger alert-dismissible fade show">' + errorMsg + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            },
            complete: function() {
                btn.prop('disabled', false); 
                spinner.addClass('d-none');
            }
        });
    });
});
</script>
@endpush
