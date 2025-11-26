<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur Paiement</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; }
        .btn { background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>⚠️ Paiement échoué ou annulé</h1>
    <p>{{ $message }}</p>
    
    @if(isset($redirectUrl))
        <br>
        <a href="{{ $redirectUrl }}" class="btn">Retourner à l'application</a>
    @endif
</body>
</html>