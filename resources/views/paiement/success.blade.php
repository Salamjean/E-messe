<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Réussi</title>
    <style>
        body {
            font-family: sans-serif;
            text-align: center;
            padding: 50px;
        }

        .btn {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
    <script>
        // Tentative de redirection automatique vers l'app 
        setTimeout(function() {
            window.location.href = "{{ $redirectUrl }}";
        }, 1000);
    </script>
</head>

<body>
    <h1>✅ Paiement Réussi !</h1>
    <p>Que la paix du Seigneur soit avec vous.</p>
    <p>Vous allez être redirigé vers l'application...</p>

    <br>
    <a href="{{ $redirectUrl }}" class="btn">Retourner à l'application</a>
</body>

</html>
