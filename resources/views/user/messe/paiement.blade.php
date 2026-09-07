@extends('user.layouts.template')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header py-4" style="background: linear-gradient(135deg, #1dc3f0 0%, #0d96cc 100%); color: white;">
                        <h5 class="card-title mb-0 text-center text-white fw-bold">
                            <i class="fas fa-shield-alt me-2"></i>Paiement de l'offrande de messe
                        </h5>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('info'))
                            <div class="alert alert-info alert-dismissible fade show rounded-3 mb-4" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ session('info') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Récapitulatif -->
                        <div class="recap-box p-4 rounded-3 mb-4" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                            <h6 class="fw-bold mb-3 text-secondary text-uppercase" style="font-size: 13px; letter-spacing: 0.5px;">
                                <i class="fas fa-file-invoice me-2 text-primary"></i>Détails de la transaction
                            </h6>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Messe demandée :</span>
                                <span class="fw-semibold">{{ $messe->demande_pour ?? 'Intention' }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Paroisse :</span>
                                <span class="fw-semibold">{{ $messe->paroisse->name ?? 'Paroisse' }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Référence :</span>
                                <span class="badge bg-light text-dark font-monospace border">{{ $paiement->reference }}</span>
                            </div>

                            <hr class="my-3 text-muted">

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fs-6 fw-bold text-dark">Montant total à régler :</span>
                                <span class="fs-4 fw-bold" style="color: #0d96cc;">
                                    {{ number_format($montantTotal, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                        </div>

                        <!-- Card Option Wave -->
                        <div class="text-center my-4">
                            <div class="payment-card-wave p-4 rounded-4 border">
                                <div class="wave-logo-container mb-3">
                                    <img src="{{ asset('assets/assets/image_recu/wave.png') }}" alt="Wave Mobile Money"
                                        style="height: 55px; max-width: 140px; object-fit: contain;">
                                </div>
                                <h5 class="fw-bold mb-2" style="color: #1a1a1a;">Paiement Wave Mobile Money</h5>
                                <p class="text-muted small mb-4" style="max-width: 380px; margin: 0 auto;">
                                    Payez instantanément et sans frais supplémentaires directement avec votre compte Wave.
                                </p>

                                <form action="{{ route('user.messe.initier-paiement', $paiement->reference) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-wave w-100 rounded-3 py-3 fs-5 fw-bold shadow-sm">
                                        <i class="fas fa-lock me-2"></i>Payer avec Wave
                                    </button>
                                </form>

                                <div class="d-flex align-items-center justify-content-center gap-2 mt-3 text-muted small">
                                    <i class="fas fa-shield-check text-success"></i>
                                    <span>Paiement 100% sécurisé via Wave Checkout</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <a href="{{ route('user.messe.index') }}" class="btn btn-link text-decoration-none text-muted">
                                <i class="fas fa-arrow-left me-2"></i>Retour à mes demandes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f1f5f9;
        }

        .payment-card-wave {
            background: #ffffff;
            border: 2px solid #e0f2fe !important;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(29, 195, 240, 0.08);
        }

        .payment-card-wave:hover {
            border-color: #1dc3f0 !important;
            box-shadow: 0 8px 30px rgba(29, 195, 240, 0.18);
            transform: translateY(-2px);
        }

        .btn-wave {
            background: linear-gradient(135deg, #1dc3f0 0%, #0d96cc 100%);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-wave:hover {
            background: linear-gradient(135deg, #0d96cc 0%, #0875a1 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(29, 195, 240, 0.35);
        }
    </style>
@endsection
