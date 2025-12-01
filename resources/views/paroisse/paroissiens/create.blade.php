@extends('paroisse.layouts.template')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
    <div class="container mt-4"><br><br>
        <div class="card">
            <div class="card-header">
                <h3>Fiche d’identification des fidèles</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('paroissien.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Infos Base -->
                        <div class="col-md-6 mb-3">
                            <label>Nom & Prénoms</label>
                            <input type="text" name="nom_prenom" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Date de naissance</label>
                            <input type="date" name="date_naissance" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Sexe</label>
                            <select name="sexe" class="form-select">
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Situation Matrimoniale</label>
                            <select name="situation_matrimoniale" class="form-select">
                                <option>Célibataire</option>
                                <option>Concubin(e)</option>
                                <option>Fiancé(e)</option>
                                <option>Divorcé(e)</option>
                                <option>Veuf/veuve</option>
                                <option>Marié(e)</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Statut d'Activité</label>
                            <select name="statut_activite" class="form-select">
                                <option>Salarié</option>
                                <option>Demandeur d'emplois</option>
                                <option>Retraité</option>
                                <option>Profession libérale</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Adresse (Lieu d'Habitation)</label>
                            <input type="text" name="adresse" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Numéro de téléphone</label>
                            <input type="text" name="telephone" class="form-control">
                        </div>

                        {{-- <div class="col-md-12 mb-3">
                        <label>Nom de sa paroisse</label>
                        <input type="text" name="nom_paroisse" class="form-control">
                    </div> --}}

                        <hr>

                        <!-- Switch Mouvement -->
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="switchMouvement"
                                    name="est_dans_mouvement">
                                <label class="form-check-label" for="switchMouvement">Etes-vous dans un mouvement ?</label>
                            </div>
                            <div class="mt-2 d-none" id="divMouvement">
                                <label>Préciser le mouvement</label>
                                <input type="text" name="nom_mouvement" class="form-control">
                            </div>
                        </div>

                        <!-- Switch Baptême -->
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="switchBapteme" name="est_baptise">
                                <label class="form-check-label" for="switchBapteme">Etes-vous baptisé ?</label>
                            </div>
                            <div class="mt-2 d-none" id="divBapteme">
                                <label>Date de Baptême</label>
                                <input type="date" name="date_bapteme" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Photo</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <input type="hidden" name="nom_paroisse" value="{{ $nom_paroisse }}">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('paroissien.index') }}" class="btn btn-secondary"><i
                                class="fas fa-arrow-left"></i> Retour</a>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Logique JS simple pour afficher/cacher les champs
        document.getElementById('switchMouvement').addEventListener('change', function() {
            document.getElementById('divMouvement').classList.toggle('d-none', !this.checked);
        });

        document.getElementById('switchBapteme').addEventListener('change', function() {
            document.getElementById('divBapteme').classList.toggle('d-none', !this.checked);
        });
    </script>
@endsection
