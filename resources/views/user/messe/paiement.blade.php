@extends('user.layouts.template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Paiement de la demande de messe</div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <h5>Récapitulatif de votre demande</h5>
                        <p><strong>Type d'intention:</strong> {{ $messe->type_intention }}</p>
                        <p><strong>Montant:</strong> {{ number_format($paiement->montant, 2) }} FCFA</p>
                        <p><strong>Référence:</strong> {{ $paiement->reference }}</p>
                    </div>

                    <div class="text-center">
                        <form action="{{ route('user.messe.initier-paiement', $paiement->reference) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">
                                Payer avec Wave
                            </button>
                        </form>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('user.messe.index') }}" class="btn btn-secondary">
                            Retour à mes demandes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection