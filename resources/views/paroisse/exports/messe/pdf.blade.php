<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .total-row td {
            background-color: #eee;
            font-weight: bold;
            color: #a00;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Généré le : {{ date('d/m/Y à H:i') }} | Paroisse: {{ Auth::guard('paroisse')->user()->name }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Date Demande</th>
                <th style="width: 15%;">Demandeur</th>
                <th style="width: 10%;">Date Souhaitée</th>
                <th style="width: 15%;">Intention</th>
                <th style="width: 25%;">Noms Concernés / Motif</th>
                <th style="width: 10%;">Statut</th>
                <th style="width: 15%; text-align: right;">Montant (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($messes as $messe)
                <tr>
                    <td>{{ $messe->created_at->format('d/m/Y') }}</td>
                    <td>{{ $messe->user ? $messe->user->name : 'Anonyme' }}</td>
                    <td>{{ $messe->date_souhaitee ? \Carbon\Carbon::parse($messe->date_souhaitee)->format('d/m/Y') : '' }}
                        {{ $messe->heure_souhaitee ? 'à ' . $messe->heure_souhaitee : '' }}</td>
                    <td>{{ $messe->celebration_choisie }}</td>
                    <td>
                        <strong>Noms:</strong>
                        @php
                            $noms = $messe->nom_prenom_concernes;
                            if (is_string($noms)) {
                                $decoded = json_decode($noms, true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                    $noms = implode(', ', $decoded);
                                }
                            } elseif (is_array($noms)) {
                                $noms = implode(', ', $noms);
                            }
                        @endphp
                        {{ $noms }}
                        <br>
                        <small><strong>Motif:</strong> {{ $messe->motif_intention }}</small>
                    </td>
                    <td>{{ ucfirst($messe->statut) }}</td>
                    <td style="text-align: right;">{{ number_format($messe->montant_offrande, 0, ',', ' ') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">TOTAL GLOBAL</td>
                <td>{{ $totalMesses }} Messes</td>
                <td style="text-align: right;">{{ number_format($totalMontant, 0, ',', ' ') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Document généré automatiquement par E-Messe
    </div>
</body>

</html>
