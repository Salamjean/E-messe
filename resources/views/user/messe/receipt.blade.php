<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Étiquette Messe - {{ $messe->reference }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
        
        :root {
            --primary: #f35525;
            --secondary: #181824;
            --accent: #f35525;
            --light: #ffffff;
            --dark: #181824;
            --gray-200: #e9ecef;
            --gray-600: #6c757d;
            --border-radius: 12px;
            --border-radius-sm: 6px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: var(--dark);
            background: white;
            margin: 0;
            padding: 10px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .etiquette-container {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border: 2px solid var(--primary);
            border-radius: var(--border-radius);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .etiquette-header {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--dark) 100%);
            color: white;
            padding: 15px;
            text-align: center;
            position: relative;
        }
        
        .etiquette-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary);
        }
        
        .church-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 8px;
        }
        
        .church-logo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }
        
        .church-logo-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .church-details {
            text-align: left;
        }
        
        .church-name {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 2px;
            color: black;
        }
        
        .church-contact {
            font-size: 9px;
            opacity: 0.9;
            color: black;
        }
        
        .reference-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--primary);
            color: white;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
        }
        
        .etiquette-body {
            padding: 15px;
        }
        
        .prayer-section {
            background: linear-gradient(135deg, #fff5f2 0%, #ffeae5 100%);
            border: 1px solid var(--primary);
            border-radius: var(--border-radius-sm);
            padding: 12px;
            margin-bottom: 12px;
            text-align: center;
        }
        
        .prayer-title {
            color: var(--primary);
            font-weight: 700;
            font-size: 11px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .prayer-text {
            font-style: italic;
            color: var(--dark);
            font-size: 10px;
            line-height: 1.3;
        }
        
        .prayer-highlight {
            color: var(--primary);
            font-weight: 600;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 12px;
        }
        
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        
        .detail-label {
            font-size: 9px;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        
        .detail-value {
            font-size: 10px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .days-section {
            margin-bottom: 12px;
        }
        
        .days-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 5px;
            margin-top: 5px;
        }
        
        .day-tag {
            background: white;
            border: 1px solid var(--primary);
            border-radius: 4px;
            padding: 4px 6px;
            text-align: center;
            font-size: 9px;
            font-weight: 500;
            color: var(--primary);
        }
        
        .amount-section {
            background: linear-gradient(135deg, var(--primary) 0%, #ff6b4a 100%);
            color: white;
            padding: 10px;
            border-radius: var(--border-radius-sm);
            text-align: center;
            margin-bottom: 12px;
        }
        
        .amount-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            opacity: 0.9;
        }
        
        .amount-value {
            font-size: 16px;
            font-weight: 700;
        }
        
        .contact-section {
            background: var(--light);
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius-sm);
            padding: 10px;
            margin-bottom: 12px;
        }
        
        .contact-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        
        .contact-label {
            font-size: 9px;
            font-weight: 600;
            color: var(--gray-600);
        }
        
        .contact-value {
            font-size: 10px;
            font-weight: 500;
        }
        
        .footer-section {
            border-top: 2px dashed var(--gray-200);
            padding-top: 10px;
            text-align: center;
        }
        
        .footer-reference {
            font-size: 9px;
            color: var(--gray-600);
            margin-bottom: 4px;
        }
        
        .footer-date {
            font-size: 9px;
            color: var(--gray-600);
        }
        
        .stamp {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 50px;
            height: 50px;
            border: 2px solid var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-weight: 700;
            font-size: 8px;
            text-align: center;
            transform: rotate(15deg);
            background: white;
        }
        
        .qr-code {
            position: absolute;
            bottom: 10px;
            left: 10px;
            width: 50px;
            height: 50px;
            background: white;
            padding: 3px;
            border: 1px solid var(--gray-200);
            border-radius: 4px;
        }
        
        .no-image {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            font-size: 16px;
        }
        
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            
            .etiquette-container {
                box-shadow: none;
                border: 2px solid var(--primary);
                max-width: 90mm;
                margin: 0 auto;
            }
        }
        
        @media (max-width: 420px) {
            body {
                padding: 5px;
            }
            
            .etiquette-container {
                max-width: 100%;
            }
            
            .church-info {
                flex-direction: column;
                text-align: center;
            }
            
            .church-details {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="etiquette-container">
        <div class="etiquette-header">
            <div class="reference-badge" >
               # {{ $messe->paiements->first()->reference ?? 'M' . $messe->id }}
            </div>
            <br>
            <div class="church-info">
                <div class="church-details" style="text-align: center">
                    <div class="church-name" >
                        {{ $messe->paroisse->name ?? 'PAROISSE STE TRINITÉ' }}
                    </div>
                    <div class="church-contact">
                        {{ $messe->paroisse->contact ?? '27 22 40 83 54' }}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="etiquette-body">
            <!-- Section Prière -->
            <div class="prayer-section">
                <div class="prayer-title">AIDE, ASSISTANCE ET PROTECTION</div>
                <div class="prayer-text">
                    <span class="prayer-highlight">{{ $messe->nom_demandeur }}</span> 
                    demande Aide et Protection au Seigneur
                    pour {{ $messe->motif_intention }}
                    par l'intercession de 
                    <span class="prayer-highlight">{{ $messe->interception_par ?? 'la Sainte Trinité' }}</span>
                </div>
            </div>
            
            <!-- Détails de la célébration -->
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Date Messe</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($messe->date_souhaitee)->format('d/m/Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Heure</span>
                    <span class="detail-value">{{ $messe->heure_souhaitee ?? 'À déterminer' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Type</span>
                    <span class="detail-value">
                        @if($messe->type_intention === 'Defunt')
                            Défunt
                        @elseif($messe->type_intention === 'Action graces')
                            Action Grâces
                        @else
                            Intention
                        @endif
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Célébration</span>
                    <span class="detail-value">
                        @if($messe->celebration_choisie === 'Messe quotidienne')
                            Quotidienne
                        @elseif($messe->celebration_choisie === 'Messe dominicale')
                            Dominicale
                        @else
                            Solennelle
                        @endif
                    </span>
                </div>
            </div>
            
            <!-- Jours de célébration -->
            @php
                $jours = [];
                if ($messe->celebration_choisie === 'Messe quotidienne' && isset($messe->jours_quotidienne)) {
                    $joursListe = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
                    $joursSelectionnes = is_array($messe->jours_quotidienne) 
                        ? $messe->jours_quotidienne 
                        : json_decode($messe->jours_quotidienne, true);
                    if ($joursSelectionnes) {
                        foreach ($joursSelectionnes as $jourIndex) {
                            $jours[] = $joursListe[$jourIndex - 1];
                        }
                    }
                } elseif ($messe->celebration_choisie === 'Messe dominicale' && isset($messe->jours_dominicale)) {
                    $joursSelectionnes = is_array($messe->jours_dominicale) 
                        ? $messe->jours_dominicale 
                        : json_decode($messe->jours_dominicale, true);
                    if ($joursSelectionnes) {
                        foreach ($joursSelectionnes as $dateStr) {
                            $date = \Carbon\Carbon::parse($dateStr);
                            $jours[] = $date->format('d/m');
                        }
                    }
                }
            @endphp
            
            @if(!empty($jours))
            <div class="days-section">
                <div class="detail-label">JOURS CÉLÉBRATION</div>
                <div class="days-grid">
                    @foreach($jours as $jour)
                    <div class="day-tag">{{ $jour }}</div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Montant -->
            <div class="amount-section">
                <div class="amount-label">Offrande</div>
                <div class="amount-value">{{ number_format($messe->montant_offrande ?? ($messe->paroisse->montant_offrande ?? 3000), 0, ',', ' ') }} FCFA</div>
            </div>
            
            <!-- Coordonnées -->
            <div class="contact-section">
                <div class="contact-item">
                    <span class="contact-label">Demandeur:</span>
                    <span class="contact-value">{{ $messe->nom_demandeur }}</span>
                </div>
                <div class="contact-item">
                    <span class="contact-label">Téléphone:</span>
                    <span class="contact-value">{{ $messe->telephone_demandeur }}</span>
                </div>
                <div class="contact-item">
                    <span class="contact-label">Enregistré le:</span>
                    <span class="contact-value">{{ $messe->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="footer-section">
                <div class="footer-reference">Ref: {{ $messe->reference ?? 'M' . $messe->id }}</div>
                <div class="footer-date">Émis le {{ now()->format('d/m/Y H:i') }}</div>
            </div>
            
            <!-- Timbre -->
            <div class="stamp">
                VALIDÉ
            </div>
        </div>
    </div>
</body>
</html>