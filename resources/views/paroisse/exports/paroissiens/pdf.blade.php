<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Liste des Paroissiens</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            color: #2c3e50;
        }

        .header p {
            margin: 5px 0 0;
            font-style: italic;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .badge-oui {
            color: green;
            font-weight: bold;
        }

        .badge-non {
            color: #888;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Liste des Paroissiens</h2>
        <p>Généré le : {{ date('d/m/Y à H:i') }} | Total : {{ count($paroissiens) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nom & Prénoms</th>
                <th>Sexe</th>
                <th>Sit. Matri.</th>
                <th>Tél</th>
                <th>Adresse</th>
                <th>Mouvement</th>
                <th>Baptisé</th>
                <th>Paroisse Baptême</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($paroissiens as $p)
                <tr>
                    <td>{{ $p->nom_prenom }}<br><small>{{ $p->date_naissance }}</small></td>
                    <td>{{ $p->sexe }}</td>
                    <td>{{ $p->situation_matrimoniale }}</td>
                    <td>{{ $p->telephone }}</td>
                    <td>{{ $p->adresse }}</td>
                    <td>
                        @if ($p->est_dans_mouvement)
                            <span class="badge-oui">{{ $p->nom_mouvement }}</span>
                        @else
                            <span class="badge-non">Non</span>
                        @endif
                    </td>
                    <td>
                        @if ($p->est_baptise)
                            <span class="badge-oui">Oui</span>
                            @if ($p->date_bapteme)
                                <br><small>({{ $p->date_bapteme }})</small>
                            @endif
                        @else
                            <span class="badge-non">Non</span>
                        @endif
                    </td>
                    <td>{{ $p->nom_paroisse_bapteme }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">Aucun fidèle trouvé avec ces critères.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
