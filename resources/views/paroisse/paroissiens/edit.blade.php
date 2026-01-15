@extends('paroisse.layouts.template')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>Modifier les informations</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('paroissien.update', $paroissien->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Nom & Prénoms -->
                        <div class="col-md-6 mb-3">
                            <label>Nom & Prénoms</label>
                            <input type="text" name="nom_prenom" class="form-control"
                                value="{{ old('nom_prenom', $paroissien->nom_prenom) }}">
                        </div>

                        <!-- Date de naissance -->
                        <div class="col-md-6 mb-3">
                            <label>Date de naissance</label>
                            <!-- Important : Formater la date en Y-m-d pour l'input date -->
                            <input type="date" name="date_naissance" class="form-control"
                                value="{{ old('date_naissance', optional($paroissien->date_naissance)->format('Y-m-d')) }}"
                                required>
                        </div>

                        <!-- Sexe -->
                        <div class="col-md-4 mb-3">
                            <label>Sexe</label>
                            <select name="sexe" class="form-select">
                                <option value="" disabled>Choisir...</option>
                                <option value="M" {{ old('sexe', $paroissien->sexe) == 'M' ? 'selected' : '' }}>
                                    Masculin</option>
                                <option value="F" {{ old('sexe', $paroissien->sexe) == 'F' ? 'selected' : '' }}>Féminin
                                </option>
                            </select>
                        </div>

                        <!-- Situation Matrimoniale -->
                        <div class="col-md-4 mb-3">
                            <label>Situation Matrimoniale</label>
                            <select name="situation_matrimoniale" class="form-select">
                                @php
                                    $situations = [
                                        'Célibataire',
                                        'Concubin(e)',
                                        'Fiancé(e)',
                                        'Divorcé(e)',
                                        'Veuf/veuve',
                                        'Marié(e)',
                                    ];
                                @endphp
                                @foreach ($situations as $situation)
                                    <option value="{{ $situation }}"
                                        {{ old('situation_matrimoniale', $paroissien->situation_matrimoniale) == $situation ? 'selected' : '' }}>
                                        {{ $situation }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Statut d'Activité -->
                        <div class="col-md-4 mb-3">
                            <label>Statut d'Activité</label>
                            <select name="statut_activite" class="form-select">
                                @php
                                    $activites = ['Salarié', "Demandeur d'emplois", 'Retraité', 'Profession libérale'];
                                @endphp
                                @foreach ($activites as $activite)
                                    <option value="{{ $activite }}"
                                        {{ old('statut_activite', $paroissien->statut_activite) == $activite ? 'selected' : '' }}>
                                        {{ $activite }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Adresse -->
                        <div class="col-md-6 mb-3">
                            <label>Adresse (Lieu d'Habitation)</label>
                            <input type="text" name="adresse" class="form-control"
                                value="{{ old('adresse', $paroissien->adresse) }}">
                        </div>

                        <!-- Téléphone -->
                        <div class="col-md-6 mb-3">
                            <label>Numéro de téléphone</label>
                            <input type="text" name="telephone" class="form-control"
                                value="{{ old('telephone', $paroissien->telephone) }}">
                        </div>

                        <hr>

                        <!-- Switch Mouvement -->
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <!-- Astuce: champ hidden pour envoyer 0 si la case est décochée -->
                                <input type="hidden" name="est_dans_mouvement" value="0">
                                <input class="form-check-input" type="checkbox" id="switchMouvement"
                                    name="est_dans_mouvement" value="1"
                                    {{ old('est_dans_mouvement', $paroissien->est_dans_mouvement) ? 'checked' : '' }}>
                                <label class="form-check-label" for="switchMouvement">Etes-vous dans un mouvement ?</label>
                            </div>

                            <div class="mt-2 {{ old('est_dans_mouvement', $paroissien->est_dans_mouvement) ? '' : 'd-none' }}"
                                id="divMouvement">
                                <label>Préciser le mouvement</label>
                                <input type="text" name="nom_mouvement" class="form-control"
                                    value="{{ old('nom_mouvement', $paroissien->nom_mouvement) }}">
                            </div>
                        </div>

                        <!-- Switch Baptême -->
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input type="hidden" name="est_baptise" value="0">
                                <input class="form-check-input" type="checkbox" id="switchBapteme" name="est_baptise"
                                    value="1" {{ old('est_baptise', $paroissien->est_baptise) ? 'checked' : '' }}>
                                <label class="form-check-label" for="switchBapteme">Etes-vous baptisé ?</label>
                            </div>

                            <div class="mt-2 {{ old('est_baptise', $paroissien->est_baptise) ? '' : 'd-none' }}"
                                id="divBapteme">

                                <label>Date de Baptême</label>
                                <input type="date" name="date_bapteme" class="form-control"
                                    value="{{ old('date_bapteme', optional($paroissien->date_bapteme)->format('Y-m-d')) }}">
                                <label>Nom de la paroisse</label>
                                <input type="text" name="nom_paroisse_bapteme" class="form-control"
                                    value="{{ old('nom_paroisse_bapteme', $paroissien->nom_paroisse_bapteme) }}">
                            </div>
                        </div>

                        <!-- Photo -->
                        <div class="col-md-12 mb-3">
                            <label>Nouvelle Photo (laisser vide pour garder l'actuelle)</label>
                            <input type="file" name="photo" class="form-control">
                            @if ($paroissien->photo)
                                <div class="mt-2">
                                    <p class="text-muted small">Photo actuelle :</p>
                                    <img src="{{ $paroissien->photo_url }}"
                                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                                </div>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"
                        style="margin-top: 10px; background-color: #cca45e; border-color: #cca45e;">Mettre à jour</button>
                    <a href="{{ route('paroissien.index') }}" class="btn btn-secondary"
                        style="margin-top: 10px;">Annuler</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Script JavaScript pour gérer l'affichage dynamique des champs -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction générique pour basculer la visibilité
            function toggleDiv(checkboxId, divId) {
                const checkbox = document.getElementById(checkboxId);
                const div = document.getElementById(divId);

                if (!checkbox || !div) return;

                // Fonction interne pour appliquer la classe
                const updateState = () => {
                    if (checkbox.checked) {
                        div.classList.remove('d-none');
                    } else {
                        div.classList.add('d-none');
                        // Optionnel : Vider les champs si on décoche
                        // const inputs = div.querySelectorAll('input');
                        // inputs.forEach(input => input.value = '');
                    }
                };

                // Écouter le changement
                checkbox.addEventListener('change', updateState);

                // État initial (déjà géré par Blade class d-none, mais double sécurité)
                updateState();
            }

            // Initialisation des deux switchs
            toggleDiv('switchMouvement', 'divMouvement');
            toggleDiv('switchBapteme', 'divBapteme');
        });
    </script>
@endsection
