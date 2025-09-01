<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Demandes de Messe - {{ $paroisse->name }}</title>
    <style>
        /* Styles de base */
        body { 
            font-family: 'Segoe UI', 'DejaVu Sans', sans-serif; 
            font-size: 12px; 
            background-color: #ffffff;
            color: #181824;
            margin: 0;
            padding: 20px;
        }
        
        /* En-tête avec logos */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f35525;
        }
        
        .logo {
            height: 70px;
            width: auto;
        }
        
        .header-center {
            text-align: center;
            flex-grow: 1;
            padding: 0 20px;
        }
        
        .header-center h1 {
            margin: 0;
            color: #181824;
            font-size: 22px;
            font-weight: 600;
        }
        
        .header-center p {
            margin: 5px 0;
            color: #666;
        }
        
        /* Boîte d'information */
        .info-box { 
            background: #f8f9fa; 
            padding: 15px; 
            border-radius: 8px; 
            margin-bottom: 20px; 
            border-left: 4px solid #f35525;
        }
        
        /* Cartes de messe */
        .messe-card { 
            border: 1px solid #eaeaea; 
            padding: 18px; 
            margin-bottom: 18px; 
            border-radius: 8px; 
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .messe-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        .messe-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 12px; 
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .badge { 
            padding: 5px 10px; 
            border-radius: 20px; 
            font-size: 10px; 
            font-weight: bold; 
        }
        
        .badge-en_attente { background: #fff3cd; color: #856404; }
        .badge-confirmee { background: #d4edda; color: #155724; }
        .badge-celebre { background: #d1ecf1; color: #0c5460; }
        .badge-annulee { background: #f8d7da; color: #721c24; }
        
        .detail-item { 
            margin-bottom: 8px; 
            display: flex;
        }
        
        .detail-label { 
            font-weight: 600; 
            color: #181824; 
            min-width: 140px;
        }
        
        .noms-list { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 5px; 
            margin-top: 5px; 
        }
        
        .nom-tag { 
            background: #f0f2f5; 
            padding: 3px 8px; 
            border-radius: 4px; 
            font-size: 10px; 
            color: #181824;
        }
        
        /* Pied de page */
        .footer { 
            margin-top: 30px; 
            text-align: center; 
            color: #7f8c8d; 
            font-size: 10px; 
            padding-top: 15px;
            border-top: 1px solid #eaeaea;
        }
        
        .page-break { 
            page-break-after: always; 
        }
        
        /* Éléments accentués */
        .accent-color {
            color: #f35525;
        }
        
        /* Mise en page responsive */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                text-align: center;
            }
            
            .logo {
                margin-bottom: 15px;
            }
            
            .detail-item {
                flex-direction: column;
            }
            
            .detail-label {
                min-width: auto;
                margin-bottom: 3px;
            }
        }
    </style>
</head>
<body>
    <div class="header-container">
        
        <!-- Titre au centre -->
        <div class="header-center">
            <h1>Demandes de Messe</h1>
            <p>Paroisse: {{ $paroisse->name }}</p>
            <p>Exporté le: <span class="accent-color">{{ $date_export }}</span></p>
            <p>Total des demandes: <span class="accent-color">{{ $total }}</span></p>
        </div>
        
        <!-- Logo à droite -->
        <img src="{{asset('assets/assets/images/pape.jpg')}}" alt="Logo droite" class="logo">
    </div>

    @foreach($messess as $index => $messe)
    <div class="messe-card">
        <div class="messe-header">
            <h3 style="margin: 0;">
                @if($messe->type_intention === 'Defunt')
                    Messe pour Défunt: {{ $messe->nom_defunt ?? 'Non spécifié' }}
                @elseif($messe->type_intention === 'Action graces')
                    Action de Grâces
                @else
                    Intention Particulière
                @endif
            </h3>
            <span class="badge badge-{{ str_replace(' ', '_', $messe->statut) }}">
                {{ ucfirst($messe->statut) }}
            </span>
        </div>

        <div class="messe-details">
            <div class="detail-item">
                <span class="detail-label">Référence:</span> 
                <span class="accent-color">#{{ str_pad($messe->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Date souhaitée:</span> {{ \Carbon\Carbon::parse($messe->date_souhaitee)->format('d/m/Y') }}
            </div>
            @if($messe->heure_souhaitee)
            <div class="detail-item">
                <span class="detail-label">Heure:</span> {{ $messe->heure_souhaitee }}
            </div>
            @endif
            <div class="detail-item">
                <span class="detail-label">Type de célébration:</span> {{ $messe->celebration_choisie ?? 'Non spécifié' }}
            </div>
            @if($messe->paroisse)
            <div class="detail-item">
                <span class="detail-label">Paroisse:</span> {{ $messe->paroisse->name }}
            </div>
            @endif
            @if($messe->montant_offrande)
            <div class="detail-item">
                <span class="detail-label">Offrande:</span> 
                <span class="accent-color">{{ number_format($messe->montant_offrande, 0, ',', ' ') }} FCFA</span>
            </div>
            @endif
            <div class="detail-item">
                <span class="detail-label">Demandeur:</span> {{ $messe->nom_demandeur }}
            </div>
            <div class="detail-item">
                <span class="detail-label">Email:</span> {{ $messe->email_demandeur }}
            </div>
            <div class="detail-item">
                <span class="detail-label">Téléphone:</span> {{ $messe->telephone_demandeur }}
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Noms concernés:</span>
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

            @if($messe->type_intention === 'Action graces' && $messe->motif_action_graces)
            <div class="detail-item">
                <span class="detail-label">Motif action de grâces:</span> {{ $messe->motif_action_graces }}
            </div>
            @endif

            @if($messe->type_intention === 'Intention particuliere' && $messe->motif_intention)
            <div class="detail-item">
                <span class="detail-label">Motif intention particulière:</span> {{ $messe->motif_intention }}
            </div>
            @endif
        </div>
    </div>

    @if(($index + 1) % 3 === 0 && ($index + 1) < count($messess))
    <div class="page-break"></div>
    @endif
    @endforeach

    <div class="footer">
        <p>Document généré automatiquement par le système de gestion des messes</p>
    </div>
</body>
</html>