@extends('paroisse.layouts.template')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Détails du Fidèle</h3>
                <a href="{{ route('paroissien.index') }}" class="btn btn-secondary">Retour</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        @if ($paroissien->photo)
                            <img src="{{ asset('storage/' . $paroissien->photo) }}" alt="Photo"
                                class="img-fluid rounded-circle" style="max-width: 200px;">
                        @else
                            <img src="https://via.placeholder.com/200" alt="Pas de photo" class="img-fluid rounded-circle">
                        @endif
                        <h4 class="mt-3">{{ $paroissien->nom_prenom }}</h4>
                        <p class="text-muted">{{ $paroissien->situation_matrimoniale }}</p>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-striped">
                            <tr>
                                <th>Date de Naissance</th>
                                <td>{{ $paroissien->date_naissance->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Sexe</th>
                                <td>{{ $paroissien->sexe }}</td>
                            </tr>
                            <tr>
                                <th>Adresse</th>
                                <td>{{ $paroissien->adresse }}</td>
                            </tr>
                            <tr>
                                <th>Activité</th>
                                <td>{{ $paroissien->statut_activite }}</td>
                            </tr>
                            <tr>
                                <th>Téléphone</th>
                                <td>{{ $paroissien->telephone }}</td>
                            </tr>
                            <tr>
                                <th>Paroisse</th>
                                <td>{{ $paroissien->nom_paroisse }}</td>
                            </tr>
                            <tr>
                                <th>Mouvement</th>
                                <td>
                                    @if ($paroissien->est_dans_mouvement)
                                        <span class="badge bg-success">Oui</span> : {{ $paroissien->nom_mouvement }}
                                    @else
                                        <span class="badge bg-secondary">Non</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Baptême</th>
                                <td>
                                    @if ($paroissien->est_baptise)
                                        <span class="badge bg-success">Oui</span> le
                                        {{ $paroissien->date_bapteme->format('d/m/Y') }}
                                    @else
                                        <span class="badge bg-secondary">Non</span>
                                    @endif
                                    @if ($paroissien->nom_paroisse_bapteme)
                                        <span class="badge bg-success">Oui</span> Paroisse Baptême:
                                        {{ $paroissien->nom_paroisse_bapteme }}
                                    @else
                                        <span class="badge bg-secondary">Non</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
