@extends('user.layouts.template')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header py-3 rounded-top-3" style="background-color: #f35525; color:white">
                        <h5 class="card-title mb-0 text-center text-white"><i class="fas fa-credit-card me-2"></i>Paiement
                            de la demande de messe</h5>
                    </div>

                    <div class="card-body p-4">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="alert alert-info bg-light border-0 rounded-3">
                            <h5 class="alert-heading d-flex align-items-center">
                                <i class="fas fa-receipt me-2 text-primary"></i>
                                Récapitulatif de votre demande
                            </h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-coins me-2 text-muted"></i>Montant de la messe:</strong></p>
                                    <p><strong><i class="fas fa-percent me-2 text-muted"></i>Frais de service :</strong>
                                    </p>
                                    <p><strong><i class="fas fa-wallet me-2 text-muted"></i>Montant total à
                                            payer:</strong></p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <p class="fw-bold text-primary">
                                        {{ number_format($messe->montant_offrande, 0, ',', ' ') }} FCFA</p>
                                    <p>{{ number_format($montantTotal - $messe->montant_offrande, 0, ',', ' ') }} FCFA</p>
                                    <p class="fw-bold text-success fs-5">{{ number_format($montantTotal, 0, ',', ' ') }}
                                        FCFA</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <p class="mb-0"><strong><i class="fas fa-hashtag me-2 text-muted"></i>Référence:</strong>
                                    {{ $paiement->reference }}</p>
                            </div>
                        </div>

                        <div class="text-center my-4">
                            <h5 class="mb-4">Mode de paiement sécurisé</h5>

                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="payment-option-card rounded-3 p-4 border shadow-sm">
                                        <div class="mb-3">
                                            <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                                            <div class="d-flex justify-content-center gap-3 mb-3">
                                                <i class="fab fa-cc-visa fa-2x text-secondary"></i>
                                                <i class="fab fa-cc-mastercard fa-2x text-secondary"></i>
                                                <i class="fas fa-mobile-alt fa-2x text-secondary"></i>
                                            </div>
                                        </div>
                                        <h6>Paiement Multi-canal CinetPay</h6>
                                        <p class="small text-muted">Payez en toute sécurité via Mobile Money (Wave, Orange,
                                            Moov, MTN) ou par Carte Bancaire.</p>
                                        <form action="{{ route('user.messe.initier-paiement', $paiement->reference) }}"
                                            method="POST" class="mt-3">
                                            @csrf
                                            <button type="submit" class="btn btn-primary w-100 rounded-2 py-3 fs-5">
                                                <i class="fas fa-lock me-2"></i>Payer maintenant
                                            </button>
                                        </form>
                                        <div class="mt-3">
                                            <img src="https://admin.cinetpay.com/img/logo.png" alt="CinetPay"
                                                style="height: 30px; opacity: 0.7;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <a href="{{ route('user.messe.index') }}" class="btn btn-outline-secondary rounded-2">
                                <i class="fas fa-arrow-left me-2"></i>Retour à mes demandes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Styles et scripts restent identiques -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .payment-option-card {
            transition: all 0.3s ease;
            background: white;
        }

        .payment-option-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            border: none;
            box-shadow: 0 4px 15px rgba(78, 84, 200, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #3a3f99, #6c71e0);
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(78, 84, 200, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #218838, #1aa179);
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(40, 167, 69, 0.4);
        }

        .alert-info {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            border-left: 4px solid #4e54c8;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".card").hide().fadeIn(800);
            $(".alert-info").hide().slideDown(600);
            $(".payment-option-card").hover(
                function() {
                    $(this).css('transform', 'translateY(-5px)');
                },
                function() {
                    $(this).css('transform', 'translateY(0)');
                }
            );
        });
    </script>
@endsection
