@extends('user.layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">Ma Fiche d'identification</h1>
                <p class="text-muted">Remplissez ce formulaire pour compléter votre identification paroissiale.</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10"> <!-- Wider column for more fields -->
                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="card-body p-5">

                        @if (session('success'))
                            <div class="alert alert-success fade show" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('user.fiche.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Photo Upload -->
                            {{-- <div class="text-center mb-5">
                                <div class="position-relative d-inline-block">
                                    <div
                                        style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center;">
                                        @if ($paroissien->photo)
                                            <img src="{{ Storage::url($paroissien->photo) }}" alt="Photo"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="fas fa-user fa-3x text-white"></i>
                                        @endif
                                    </div>
                                    <label for="photo"
                                        class="btn btn-gold btn-sm position-absolute rounded-circle shadow-sm"
                                        style="bottom: 0; right: 0; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-camera text-white"></i>
                                    </label>
                                    <input type="file" name="photo" id="photo" class="d-none" accept="image/*">
                                </div>
                                <p class="text-muted small mt-2">Photo d'identité</p>
                            </div> --}}

                            <h5 class="font-weight-bold mb-4 text-gold section-title">Information Personnelle</h5>
                            <p class="text-muted small mt-2 text-center text-gold">Remplissez les champs suivants</p>
                            <div class="row">
                                <div class="col-md-6 form-group mb-4">
                                    <label class="font-weight-bold text-muted small">Nom & Prénom(s) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nom_prenom"
                                        value="{{ old('nom_prenom', $paroissien->nom_prenom ?? '') }}"
                                        placeholder="Nom complet">
                                </div>
                                <div class="col-md-6 form-group mb-4">
                                    <label class="font-weight-bold text-muted small">Date de Naissance <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="date_naissance"
                                        value="{{ old('date_naissance', $paroissien->date_naissance ?? '') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-4">
                                    <label class="font-weight-bold text-muted small">Sexe <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" name="sexe">
                                        <option value="" disabled selected>Choisir...</option>
                                        <option value="M"
                                            {{ old('sexe', $paroissien->sexe ?? '') == 'M' ? 'selected' : '' }}>Masculin
                                        </option>
                                        <option value="F"
                                            {{ old('sexe', $paroissien->sexe ?? '') == 'F' ? 'selected' : '' }}>Féminin
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-4">
                                    <label class="font-weight-bold text-muted small">Situation Matrimoniale <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" name="situation_matrimoniale">
                                        <option value="" disabled selected>Choisir...</option>
                                        <option value="Célibataire"
                                            {{ old('situation_matrimoniale', $paroissien->situation_matrimoniale ?? '') == 'Célibataire' ? 'selected' : '' }}>
                                            Célibataire</option>
                                        <option value="Marié(e)"
                                            {{ old('situation_matrimoniale', $paroissien->situation_matrimoniale ?? '') == 'Marié(e)' ? 'selected' : '' }}>
                                            Marié(e)</option>
                                        <option value="Veuf(ve)"
                                            {{ old('situation_matrimoniale', $paroissien->situation_matrimoniale ?? '') == 'Veuf(ve)' ? 'selected' : '' }}>
                                            Veuf(ve)</option>
                                        <option value="Divorcé(e)"
                                            {{ old('situation_matrimoniale', $paroissien->situation_matrimoniale ?? '') == 'Divorcé(e)' ? 'selected' : '' }}>
                                            Divorcé(e)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-4">
                                    <label class="font-weight-bold text-muted small">Statut d'activité <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="statut_activite"
                                        value="{{ old('statut_activite', $paroissien->statut_activite ?? '') }}"
                                        placeholder="Ex: Étudiant, Cadre, Commerçant...">
                                </div>
                                <div class="col-md-6 form-group mb-4">
                                    <label class="font-weight-bold text-muted small">Téléphone <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="telephone"
                                        value="{{ old('telephone', $paroissien->telephone ?? '') }}" placeholder="+225...">
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-muted small">Adresse Habituelle <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="adresse"
                                    value="{{ old('adresse', $paroissien->adresse ?? '') }}"
                                    placeholder="Quartier, Rue, Ville...">
                            </div>

                            <hr class="my-4">

                            <h5 class="font-weight-bold mb-4 text-gold section-title">Vie Paroissiale</h5>
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-muted small">Paroisse de Résidence <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nom_paroisse"
                                    value="{{ old('nom_paroisse', $paroissien->nom_paroisse ?? '') }}"
                                    placeholder="Nom de votre paroisse actuelle">
                            </div>

                            <!-- Mouvement -->
                            <div class="form-group mb-4 bg-light p-3 rounded">
                                <div class="custom-control custom-switch mb-2">
                                    <input type="checkbox" class="custom-control-input" id="est_dans_mouvement"
                                        name="est_dans_mouvement"
                                        {{ old('est_dans_mouvement', $paroissien->est_dans_mouvement ?? '') ? 'checked' : '' }}
                                        onchange="toggleMouvementField()">
                                    <label class="custom-control-label font-weight-bold"
                                        for="est_dans_mouvement">Appartenez-vous à un mouvement/groupe ?</label>
                                </div>
                                <div id="mouvement-field"
                                    style="display: {{ old('est_dans_mouvement', $paroissien->est_dans_mouvement ?? '') ? 'block' : 'none' }};">
                                    <label class="font-weight-bold text-muted small mt-2">Nom du Mouvement</label>
                                    <input type="text" class="form-control" name="nom_mouvement"
                                        value="{{ old('nom_mouvement', $paroissien->nom_mouvement ?? '') }}"
                                        placeholder="Ex: Légion de Marie, Chorale...">
                                </div>
                            </div>

                            <!-- Baptême -->
                            <div class="form-group mb-4 bg-light p-3 rounded">
                                <div class="custom-control custom-switch mb-2">
                                    <input type="checkbox" class="custom-control-input" id="est_baptise"
                                        name="est_baptise"
                                        {{ old('est_baptise', $paroissien->est_baptise ?? '') ? 'checked' : '' }}
                                        onchange="toggleBaptemeFields()">
                                    <label class="custom-control-label font-weight-bold" for="est_baptise">Êtes-vous
                                        baptisé(e) ?</label>
                                </div>
                                <div id="bapteme-fields"
                                    style="display: {{ old('est_baptise', $paroissien->est_baptise ?? '') ? 'block' : 'none' }};">
                                    <div class="row mt-3">
                                        <div class="col-md-6 form-group">
                                            <label class="font-weight-bold text-muted small">Date de Baptême</label>
                                            <input type="date" class="form-control" name="date_bapteme"
                                                value="{{ old('date_bapteme', $paroissien->date_bapteme ?? '') }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="font-weight-bold text-muted small">Paroisse de Baptême</label>
                                            <input type="text" class="form-control" name="nom_paroisse_bapteme"
                                                value="{{ old('nom_paroisse_bapteme', $paroissien->nom_paroisse_bapteme ?? '') }}"
                                                placeholder="Paroisse où vous avez été baptisé">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0 mt-5 text-center">
                                <button type="submit" class="btn btn-gold btn-block text-white font-weight-bold py-3"
                                    style="border-radius: 10px;">
                                    Enregistrer ma fiche
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus {
            box-shadow: none;
            border-color: #d4a762;
        }

        .btn-gold {
            background-color: #cda45e;
            color: white;
            border: none;
            transition: all 0.3s;
        }

        .btn-gold:hover {
            background-color: #b38f52;
            color: white;
        }

        .text-gold {
            color: #cda45e;
        }

        .section-title {
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }

        /* Custom Switch Color */
        .custom-control-input:checked~.custom-control-label::before {
            background-color: #cda45e;
            border-color: #cda45e;
        }
    </style>

    <script>
        // Photo Preview
        document.getElementById('photo').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.position-relative img').src = e.target.result;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Toggle Logic
        function toggleMouvementField() {
            var isChecked = document.getElementById('est_dans_mouvement').checked;
            document.getElementById('mouvement-field').style.display = isChecked ? 'block' : 'none';
        }

        function toggleBaptemeFields() {
            var isChecked = document.getElementById('est_baptise').checked;
            document.getElementById('bapteme-fields').style.display = isChecked ? 'block' : 'none';
        }
    </script>
@endsection
