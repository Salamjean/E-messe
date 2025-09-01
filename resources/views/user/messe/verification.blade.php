@extends('user.layouts.template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Vérification du paiement</div>

                <div class="card-body text-center">
                    @if($status === 'completed')
                        <div class="alert alert-success">
                            <h5>Paiement confirmé!</h5>
                            <p>Votre paiement a été traité avec succès.</p>
                        </div>
                        <a href="{{ route('user.messe.index') }}" class="btn btn-success">
                            Voir mes demandes
                        </a>
                    @else
                        <div class="alert alert-info">
                            <h5>Paiement en attente</h5>
                            <p>Votre paiement est en cours de traitement. Statut: {{ $status }}</p>
                            <p>Veuillez patienter ou vérifier à nouveau dans quelques instants.</p>
                        </div>
                        
                        <form action="{{ route('user.messe.verifier-manuellement', $paiement->reference) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                Vérifier à nouveau
                            </button>
                        </form>
                        
                        <a href="{{ route('user.messe.paiement', $paiement->reference) }}" class="btn btn-secondary">
                            Retour au paiement
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection