<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reçu de demande de messe - {{ $messe->reference }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        :root {
            --primary: #f35525;
            --primary-light: #ff7c52;
            --secondary: #2d2d42;
            --accent: #6c5ce7;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #212529;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-600: #6c757d;
            --gray-800: #343a40;
            --border-radius: 12px;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: var(--gray-800);
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            margin: 0;
            padding: 30px;
            min-height: 100vh;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        
        .receipt-header {
            background: linear-gradient(135deg, var(--secondary) 0%, #181824 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .receipt-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
        }
        
        .receipt-header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        .receipt-header p {
            margin: 5px 0;
            opacity: 0.9;
            font-weight: 300;
        }
        
        .church-name {
            font-size: 18px;
            font-weight: 600;
            margin-top: 15px;
            color: var(--primary-light);
        }
        
        .receipt-body {
            padding: 40px;
        }
        
        .reference-badge {
            display: inline-block;
            background: var(--gray-100);
            color: var(--gray-600);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 30px;
            border: 1px solid var(--gray-200);
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--gray-200);
            font-weight: 600;
            color: var(--secondary);
            font-size: 16px;
        }
        
        .section-title::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 20px;
            background: var(--primary);
            margin-right: 12px;
            border-radius: 2px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .info-card {
            background: var(--gray-100);
            padding: 15px;
            border-radius: var(--border-radius);
            border-left: 4px solid var(--primary);
        }
        
        .info-item {
            margin-bottom: 12px;
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-weight: 500;
            color: var(--gray-800);
            font-size: 14px;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-badge i {
            margin-right: 5px;
            font-size: 10px;
        }
        
        .badge-confirmee {
            background: rgba(40, 167, 69, 0.15);
            color: var(--success);
        }
        
        .badge-en_attente {
            background: rgba(255, 193, 7, 0.15);
            color: var(--warning);
        }
        
        .badge-celebre {
            background: rgba(23, 162, 184, 0.15);
            color: #17a2b8;
        }
        
        .badge-annulee {
            background: rgba(220, 53, 69, 0.15);
            color: var(--danger);
        }
        
        .badge-en_attente_paiement {
            background: rgba(108, 117, 125, 0.15);
            color: var(--gray-600);
        }
        
        .noms-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }
        
        .nom-tag {
            background: white;
            padding: 6px 12px;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 500;
            color: var(--gray-800);
            border: 1px solid var(--gray-200);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .amount-highlight {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
        }
        
        .signature-area {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px dashed var(--gray-300);
            text-align: center;
        }
        
        .signature-line {
            display: inline-block;
            width: 200px;
            border-top: 1px solid var(--gray-600);
            margin: 20px 0 10px 0;
        }
        
        .signature-text {
            font-size: 12px;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .receipt-footer {
            background: var(--gray-100);
            padding: 20px 40px;
            text-align: center;
            font-size: 12px;
            color: var(--gray-600);
            border-top: 1px solid var(--gray-200);
        }
        
        .footer-reference {
            font-weight: 600;
            color: var(--secondary);
            margin-top: 5px;
        }
        
        /* Style pour la section QR Code */
        .qr-section {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 30px;
            padding: 20px;
            background: var(--gray-100);
            border-radius: var(--border-radius);
            border-left: 4px solid var(--accent);
        }
        
        .qr-code {
            flex-shrink: 0;
        }
        
        .qr-info {
            flex: 1;
        }
        
        .qr-info h4 {
            margin: 0 0 10px 0;
            color: var(--secondary);
            font-size: 16px;
        }
        
        .qr-info p {
            margin: 5px 0;
            font-size: 12px;
            color: var(--gray-600);
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .receipt-container {
                box-shadow: none;
            }
        }
        
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .receipt-body {
                padding: 20px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .qr-section {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>CONFIRMATION DE DEMANDE DE MESSE</h1>
            <p>Votre demande a été enregistrée avec succès</p>
            <div class="church-name">
                {{ $messe->paroisse->name ?? 'Paroisse Non Spécifiée' }}
            </div>
        </div>
        
        <div class="receipt-body">
            <div class="reference-badge">
                Référence: <strong>{{ $messe->reference ?? 'M' . $messe->id . '-' . $messe->created_at->format('Ymd') }}</strong>
            </div>
            
            <div class="section">
                <div class="section-title">DÉTAILS DE LA DEMANDE</div>
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-item">
                            <span class="info-label">Date d'enregistrement</span>
                            <span class="info-value">{{ $messe->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Statut</span>
                            <span class="info-value">
                                <span class="status-badge badge-{{ str_replace(' ', '_', $messe->statut) }}">
                                    <i class="fas fa-circle"></i>
                                    {{ ucfirst($messe->statut) }}
                                </span>
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Type d'intention</span>
                            <span class="info-value">
                                @if($messe->type_intention === 'Defunt')
                                    Messe pour Défunt
                                @elseif($messe->type_intention === 'Action graces')
                                    Action de Grâces
                                @else
                                    Intention Particulière
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-item">
                            <span class="info-label">Date souhaitée</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($messe->date_souhaitee)->format('d/m/Y') }}</span>
                        </div>
                        
                        @if($messe->heure_souhaitee)
                        <div class="info-item">
                            <span class="info-label">Heure souhaitée</span>
                            <span class="info-value">{{ $messe->heure_souhaitee }}</span>
                        </div>
                        @endif
                        
                        @if($messe->celebration_choisie)
                        <div class="info-item">
                            <span class="info-label">Type de célébration</span>
                            <span class="info-value">{{ $messe->celebration_choisie }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            @if($messe->type_intention === 'Defunt' && $messe->nom_defunt)
            <div class="section">
                <div class="section-title">INFORMATIONS DÉFUNT</div>
                <div class="info-card">
                    <div class="info-item">
                        <span class="info-label">Nom du défunt</span>
                        <span class="info-value">{{ $messe->nom_defunt }}</span>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="section">
                <div class="section-title">PERSONNES CONCERNÉES</div>
                <div class="info-card">
                    <div class="noms-list">
                        @php
                            $noms = is_array($messe->nom_prenom_concernes) 
                                    ? $messe->nom_prenom_concernes 
                                    : json_decode($messe->nom_prenom_concernes, true) ?? [$messe->nom_prenom_concernes];
                        @endphp
                        @foreach($noms as $nom)
                            <span class="nom-tag">{{ $nom }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            
            @if($messe->montant_offrande)
            <div class="section">
                <div class="section-title">CONTRIBUTION</div>
                <div class="info-card">
                    <div class="info-item">
                        <span class="info-label">Montant de l'offrande</span>
                        <span class="info-value amount-highlight">
                            {{ number_format($messe->montant_offrande, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="section">
                <div class="section-title">COORDONNÉES DU DEMANDEUR</div>
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-item">
                            <span class="info-label">Nom complet</span>
                            <span class="info-value">{{ $messe->nom_demandeur }}</span>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $messe->email_demandeur }}</span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Téléphone</span>
                            <span class="info-value">{{ $messe->telephone_demandeur }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section Code QR -->
            {{-- Section Code QR --}}
            @if(isset($qrCode) && (extension_loaded('imagick') || extension_loaded('gd')))
            <div class="qr-section">
                <div class="qr-code">
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="Code QR de vérification" style="width: 120px; height: 120px;">
                </div>
                <div class="qr-info">
                    <h4>Code de Vérification</h4>
                    <p>Ce code QR contient les informations essentielles de votre demande.</p>
                    <p>Il peut être scanné pour vérifier l'authenticité de ce reçu.</p>
                    <p><strong>Référence:</strong> {{ $messe->reference ?? $messe->id }}</p>
                </div>
            </div>
            @else
            <div class="info-section">
                <h4>Informations de Vérification</h4>
                <p><strong>Référence:</strong> {{ $messe->reference ?? $messe->id }}</p>
                <p><strong>Date:</strong> {{ $messe->created_at->format('d/m/Y') }}</p>
                <p><strong>Demandeur:</strong> {{ $messe->nom_demandeur }}</p>
            </div>
            @endif
        </div>
        
        <div class="receipt-footer">
            <p>Ce document certifie que votre demande de messe a été enregistrée dans notre système</p>
            <p class="footer-reference">Référence: {{ $messe->reference ?? 'M' . $messe->id . '-' . $messe->created_at->format('Ymd') }}</p>
            <p>Émis le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
    </div>
</body>
</html>