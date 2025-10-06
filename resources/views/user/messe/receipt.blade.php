<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reçu Messe - {{ $messe->reference }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        :root {
            --primary: #2c3e50;
            --accent: #e74c3c;
            --light-bg: #f8f9fa;
            --border: #e0e0e0;
            --text: #2c3e50;
            --text-light: #7f8c8d;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: var(--text);
            background: white;
            margin: 0;
            padding: 10px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .invoice-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border: 1px solid var(--border);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .invoice-header {
            background: var(--primary);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .logo-section {
            margin-bottom: 20px;
        }
        
        .church-name {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .church-tagline {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: 300;
            margin: 10px 0;
        }
        
        .invoice-info {
            background: var(--light-bg);
            padding: 25px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            border-bottom: 1px solid var(--border);
        }
        
        .bill-to h3, .receipt-info h3 {
            color: var(--primary);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .bill-to p, .receipt-info p {
            margin: 5px 0;
            color: var(--text-light);
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .items-table th {
            background: var(--light-bg);
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--primary);
            border-bottom: 2px solid var(--border);
        }
        
        .items-table td {
            padding: 15px;
            border-bottom: 1px solid var(--border);
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .calculation-section {
            padding: 25px;
            background: var(--light-bg);
        }
        
        .calculation-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
        }
        
        .calculation-row.total {
            border-top: 2px solid var(--border);
            margin-top: 10px;
            padding-top: 15px;
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
        }
        
        .payment-section {
            padding: 25px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            border-top: 1px solid var(--border);
        }
        
        .payment-method h3, .terms h3, .notes h3 {
            color: var(--primary);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        
        .payment-options {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        
        .payment-option {
            padding: 8px 15px;
            border: 1px solid var(--border);
            border-radius: 4px;
            font-size: 11px;
        }
        
        .terms p, .notes p {
            color: var(--text-light);
            font-size: 11px;
            line-height: 1.5;
        }
        
        .footer {
            background: var(--primary);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .website {
            font-size: 14px;
            font-weight: 600;
        }
        
        .highlight {
            color: var(--accent);
            font-weight: 600;
        }
        
        .text-right {
            text-align: right;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .invoice-container {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- En-tête -->
        <div class="invoice-header">
            <div class="logo-section">
                <div class="church-name">{{ $messe->paroisse->name ?? 'PAROISSE STE TRINITÉ' }}</div>
                <div class="church-tagline">Service des Messes & Intentions</div>
            </div>
            <div class="invoice-title">REÇU DE PAIEMENT</div>
        </div>
        
        <!-- Informations client et reçu -->
        <div class="invoice-info">
            <div class="bill-to">
                <h3>Demandeur</h3>
                <p><strong>{{ $messe->nom_demandeur }}</strong></p>
                <p>{{ $messe->email_demandeur }}</p>
                <p>{{ $messe->telephone_demandeur }}</p>
                <p>Réf: <span class="highlight">{{ $messe->paiements->first()->reference ?? 'M' . $messe->id }}</span></p>
            </div>
            
            <div class="receipt-info">
                <h3>Reçu</h3>
                <p><strong>N° {{ $messe->paiements->first()->reference ?? 'M' . $messe->id }}</strong></p>
                <p>Date: {{ now()->format('d/m/Y') }}</p>
                <p>Paroisse: {{ $messe->paroisse->name ?? 'Ste Trinité' }}</p>
            </div>
        </div>
        
        <!-- Détails des services -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Service</th>
                    <th>Type</th>
                    <th class="text-right">Montant</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <strong>Célébration de Messe</strong><br>
                        <small>
                            @if($messe->type_intention === 'Defunt')
                                Pour le repos de l'âme de {{ $messe->nom_defunt }}
                            @elseif($messe->type_intention === 'Action graces')
                                Action de Grâces - {{ $messe->motif_action_graces }}
                            @else
                                Intention particulière - {{ $messe->motif_intention }}
                            @endif
                        </small>
                    </td>
                    <td>
                        @if($messe->celebration_choisie === 'Messe quotidienne')
                            Quotidienne
                        @elseif($messe->celebration_choisie === 'Messe dominicale')
                            Dominicale
                        @else
                            Solennelle
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($messe->montant_offrande ?? ($messe->paroisse->montant_offrande ?? 3000), 0, ',', ' ') }} FCFA</td>
                </tr>
                
                <!-- Dates sélectionnées -->
                @if($messe->dates_selectionnees)
                <tr>
                    <td>2</td>
                    <td colspan="2">
                        <strong>Dates de célébration</strong><br>
                        <small>
                            @php
                                $dates = json_decode($messe->dates_selectionnees, true);
                            @endphp
                            @if(is_array($dates))
                                {{ implode(', ', array_slice($dates, 0, 3)) }}
                                @if(count($dates) > 3)
                                    ... et {{ count($dates) - 3 }} autres dates
                                @endif
                            @else
                                {{ $messe->dates_selectionnees }}
                            @endif
                        </small>
                    </td>
                    <td class="text-right">Inclus</td>
                </tr>
                @endif
                
                <!-- Personnes concernées -->
                @if($messe->nom_prenom_concernes)
                <tr>
                    <td>3</td>
                    <td colspan="2">
                        <strong>Personnes concernées</strong><br>
                        <small>
                            @php
                                $noms = is_array($messe->nom_prenom_concernes) 
                                        ? $messe->nom_prenom_concernes 
                                        : json_decode($messe->nom_prenom_concernes, true) ?? [$messe->nom_prenom_concernes];
                            @endphp
                            {{ implode(', ', array_slice($noms, 0, 2)) }}
                            @if(count($noms) > 2)
                                ... et {{ count($noms) - 2 }} autres
                            @endif
                        </small>
                    </td>
                    <td class="text-right">Inclus</td>
                </tr>
                @endif
            </tbody>
        </table>
        
        <!-- Calcul du total -->
        <div class="calculation-section">
            <div class="calculation-row">
                <span>Sous-total</span>
                <span>{{ number_format($messe->montant_offrande ?? ($messe->paroisse->montant_offrande ?? 3000), 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="calculation-row">
                <span>Frais</span>
                <span>{{ number_format($messe->montant_offrande * 0.02 ?? ($messe->paroisse->montant_offrande ?? 3000), 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="calculation-row total">
                <span>TOTAL</span>
                <span>{{ number_format($messe->montant_offrande + $messe->montant_offrande * 0.02 ?? ($messe->paroisse->montant_offrande ?? 3000), 0, ',', ' ') }} FCFA</span>
            </div>
        </div>
</body>
</html>