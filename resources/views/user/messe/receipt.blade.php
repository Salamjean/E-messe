@extends('user.layouts.template')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .receipt-page {
            min-height: 100vh;
            background: #f5f5f5;
            padding: 40px 20px;
            font-family: 'Poppins', sans-serif;
        }

        .receipt-actions {
            max-width: 450px;
            margin: 0 auto 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .btn-download {
            background: #22c55e;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            font-size: 14px;
        }

        .btn-download:hover {
            background: #16a34a;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .btn-back {
            background: #6b7280;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            font-size: 14px;
        }

        .btn-back:hover {
            background: #4b5563;
            color: white;
            transform: translateY(-2px);
        }

        .receipt-container {
            max-width: 450px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .receipt-header {
            text-align: left;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .amount-section {
            flex: 1;
        }

        .total-amount {
            font-size: 32px;
            font-weight: 700;
            color: #22c55e;
            margin-bottom: 8px;
        }

        .payment-description {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.5;
        }

        .payment-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .payment-icon.wave {
            background: transparent;
        }

        .payment-icon.orange {
            background: transparent;
        }

        .payment-icon.moov {
            background: transparent;
        }

        .payment-icon.mtn {
            background: transparent;
        }

        .payment-icon.bank {
            background: transparent;
        }

        .payment-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 5px;
        }

        .info-section {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }

        .info-row:not(:last-child) {
            border-bottom: 1px solid #e5e7eb;
        }

        .info-label {
            font-size: 13px;
            color: #6b7280;
            font-weight: 400;
        }

        .info-value {
            font-size: 14px;
            color: #111827;
            font-weight: 500;
            text-align: right;
        }

        .info-value.success {
            color: #22c55e;
            font-weight: 600;
        }

        .reference-section {
            background: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .reference-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .reference-label {
            font-size: 13px;
            color: #6b7280;
        }

        .reference-value {
            font-size: 13px;
            color: #111827;
            font-weight: 600;
            text-align: right;
            word-break: break-all;
            max-width: 60%;
        }

        .details-section {
            background: white;
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .details-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }

        .details-label {
            font-size: 13px;
            color: #6b7280;
        }

        .details-value {
            font-size: 14px;
            color: #111827;
            font-weight: 500;
            text-align: right;
        }

        @media print {
            .receipt-actions {
                display: none;
            }

            .receipt-page {
                padding: 0;
                background: white;
            }

            .receipt-container {
                box-shadow: none;
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .receipt-page {
                padding: 20px 10px;
            }

            .receipt-container {
                padding: 20px;
            }

            .total-amount {
                font-size: 28px;
            }

            .receipt-actions {
                flex-direction: column;
                gap: 10px;
            }

            .btn-download,
            .btn-back {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <div class="receipt-page">
        <div class="receipt-actions">
            <a href="{{ url()->previous() }}" class="btn-back">
                <i class="mdi mdi-arrow-left"></i>
                Retour
            </a>
            <a href="{{ route('user.messe.receipt.download', ['messe' => $messe->id]) }}" class="btn-download">
                <i class="mdi mdi-download"></i>
                Télécharger PDF
            </a>
        </div>

        <div class="receipt-container">
            <!-- En-tête avec montant et avatar -->
            <div class="receipt-header">
                <div class="amount-section">
                    <div class="total-amount">
                        +
                        {{ number_format(($messe->montant_offrande ?? 0) + ($messe->montant_offrande ?? 0) * 0.02, 0, ',', ' ') }}
                        F
                    </div>
                    <div class="payment-description">
                        Paiement de votre offrande pour la messe<br>via
                        {{ strtoupper($messe->paiements->first()->operateur ?? 'WAVE') }}
                    </div>
                </div>
                @php
                    $operateur = strtolower($messe->paiements->first()->operateur ?? 'wave');
                    $iconClass = 'wave';
                    $logoPath = '';

                    if (str_contains($operateur, 'orange')) {
                        $iconClass = 'orange';
                        $logoPath = asset('assets/assets/image_recu/orange.png');
                    } elseif (str_contains($operateur, 'moov')) {
                        $iconClass = 'moov';
                        $logoPath = asset('assets/assets/image_recu/moov.png');
                    } elseif (str_contains($operateur, 'mtn')) {
                        $iconClass = 'mtn';
                        $logoPath = asset('assets/assets/image_recu/mtn.png');
                    } elseif (
                        str_contains($operateur, 'stripe') ||
                        str_contains($operateur, 'bank') ||
                        str_contains($operateur, 'bancaire') ||
                        str_contains($operateur, 'carte')
                    ) {
                        $iconClass = 'bank';
                        $logoPath = asset('assets/assets/image_recu/stripe.png');
                    } else {
                        // Wave par défaut
                        $iconClass = 'wave';
                        $logoPath = asset('assets/assets/image_recu/wave.png');
                    }
                @endphp
                <div class="payment-icon {{ $iconClass }}">
                    @if ($logoPath)
                        <img src="{{ $logoPath }}" alt="{{ ucfirst($iconClass) }}">
                    @else
                        <!-- Wave Icon SVG (par défaut) -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            style="width: 30px; height: 30px; fill: white;">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" />
                        </svg>
                    @endif
                </div>
            </div>

            <!-- Informations principales -->
            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">Date & Heure</span>
                    <span
                        class="info-value">{{ $messe->paiements->first()->created_at ? $messe->paiements->first()->created_at->format('d/m/Y à H:i') : now()->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Statut</span>
                    <span class="info-value success">
                        @if ($messe->statut === 'en attente' || $messe->statut === 'en_attente')
                            en attente
                        @elseif($messe->statut === 'confirmee')
                            confirmé
                        @else
                            {{ $messe->statut }}
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Offrande</span>
                    <span class="info-value">{{ number_format($messe->montant_offrande ?? 0, 0, ',', ' ') }} F</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Frais</span>
                    <span class="info-value">{{ number_format(($messe->montant_offrande ?? 0) * 0.02, 0, ',', ' ') }}
                        F</span>
                </div>
            </div>

            <!-- Référence et opérateur -->
            <div class="reference-section">
                <div class="reference-row">
                    <span class="reference-label">Référence</span>
                    <span
                        class="reference-value">{{ $messe->paiements->first()->reference ?? 'MESSE_API_' . time() . '_' . $messe->id }}</span>
                </div>
                <div class="reference-row">
                    <span class="reference-label">Opérateur</span>
                    <span class="reference-value">WAVE</span>
                </div>
            </div>

            <!-- Détails de la messe -->
            <div class="details-section">
                <div class="details-row">
                    <span class="details-label">Intention</span>
                    <span class="details-value">
                        @if ($messe->type_intention === 'Defunt')
                            Défunt
                        @elseif($messe->type_intention === 'Action graces')
                            Action de grâces
                        @else
                            {{ $messe->type_intention }}
                        @endif
                    </span>
                </div>
                <div class="details-row">
                    <span class="details-label">Paroisse</span>
                    <span class="details-value">{{ $messe->paroisse->name ?? 'St Paul' }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Date de la messe</span>
                    <span class="details-value">
                        @if ($messe->date_souhaitee)
                            {{ \Carbon\Carbon::parse($messe->date_souhaitee)->format('d/m/Y') }}
                        @else
                            À définir
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
@endsection
