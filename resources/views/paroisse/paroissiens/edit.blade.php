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
                
                <!-- ... Les champs textuels sont similaires à create avec value="{{ $paroissien->champ }}" ... -->
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nom & Prénoms</label>
                        <input type="text" name="nom_prenom" class="form-control" value="{{ $paroissien->nom_prenom }}">
                    </div>
                    <!-- (Ajoutez les autres champs ici pour abréger...) -->

                    <!-- Switch Mouvement -->
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switchMouvement" name="est_dans_mouvement" 
                            {{ $paroissien->est_dans_mouvement ? 'checked' : '' }}>
                            <label class="form-check-label">Etes-vous dans un mouvement ?</label>
                        </div>
                        <div class="mt-2 {{ $paroissien->est_dans_mouvement ? '' : 'd-none' }}" id="divMouvement">
                            <label>Préciser le mouvement</label>
                            <input type="text" name="nom_mouvement" class="form-control" value="{{ $paroissien->nom_mouvement }}">
                        </div>
                    </div>

                    <!-- Switch Baptême -->
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switchBapteme" name="est_baptise"
                            {{ $paroissien->est_baptise ? 'checked' : '' }}>
                            <label class="form-check-label">Etes-vous baptisé ?</label>
                        </div>
                        <div class="mt-2 {{ $paroissien->est_baptise ? '' : 'd-none' }}" id="divBapteme">
                            <label>Date de Baptême</label>
                            <input type="date" name="date_bapteme" class="form-control" value="{{ $paroissien->date_bapteme ? $paroissien->date_bapteme->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                    
                     <div class="col-md-12 mb-3">
                        <label>Nouvelle Photo (laisser vide pour garder l'actuelle)</label>
                        <input type="file" name="photo" class="form-control">
                        @if($paroissien->photo)
                            <img src="{{ asset('storage/'.$paroissien->photo) }}" width="50" class="mt-2">
                        @endif
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>
</div>
<!-- Inclure le même script JS que Create pour les switchs -->
@endsection