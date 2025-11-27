@extends('paroisse.layouts.template')

@section('styles')
    <!-- CSS Bootstrap + DataTables -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

@endsection


@section('content')
<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestion des Reversements</h2>

        <!-- Bouton nouvel enregistrement -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalReversement">
            <i class="fa fa-plus"></i> Nouveau Reversement
        </button>
    </div>

    <!-- Tableau -->
    <div class="card shadow">
        <div class="card-body">
            <table id="table-reversements" class="table table-bordered table-striped w-100">
                <thead>
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


<!-- Modal Ajout Reversement -->
<div class="modal fade" id="modalReversement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="form-reversement">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Effectuer un transfert</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div id="alert-msg"></div>

                    <div class="mb-3">
                        <label class="form-label">Montant (FCFA)</label>
                        <input type="number" class="form-control" name="montant" min="100" required>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <label class="form-label">Indicatif</label>
                            <select class="form-control" name="prefix">
                                <option value="225">CI (+225)</option>
                                <option value="221">SN (+221)</option>
                                <option value="226">BF (+226)</option>
                            </select>
                        </div>

                        <div class="col-8">
                            <label class="form-label">Téléphone</label>
                            <input type="text" class="form-control" name="telephone" placeholder="0707070707" required>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>

                    <button type="submit" class="btn btn-primary" id="btn-submit">
                        <span class="spinner-border spinner-border-sm d-none"></span>
                        Envoyer
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection



@section('scripts')
<!-- JS Librairies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
$(function() {

    // Initialisation DataTable
    let table = $('#table-reversements').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('reversement.data') }}",
        columns: [
            { data: 'reference' },
            { data: 'created_at' },
            {
                data: null,
                render: function(row) {
                    return '(+' + row.prefix_pays + ') ' + row.numero_destinataire;
                }
            },
            { data: 'montant' },
            { data: 'statut' }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
        },
        order: [[1, 'desc']]
    });


    // Envoi Ajax Formulaire
    $('#form-reversement').submit(function(e) {
        e.preventDefault();

        let btn = $('#btn-submit');
        let spinner = btn.find('.spinner-border');

        btn.prop('disabled', true);
        spinner.removeClass('d-none');
        $('#alert-msg').html('');

        $.post("{{ route('reversement.store') }}", $(this).serialize())
        .done(function(res) {

            $('#modalReversement').modal('hide');
            $('#form-reversement')[0].reset();
            table.ajax.reload();

            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: res.message
            });
        })
        .fail(function(xhr) {

            let message = 'Une erreur est survenue';
            if (xhr.responseJSON?.message) {
                message = xhr.responseJSON.message;
            }

            $('#alert-msg').html(`
                <div class="alert alert-danger">${message}</div>
            `);

        })
        .always(function() {
            btn.prop('disabled', false);
            spinner.addClass('d-none');
        });
    });

});
</script>
@endsection
