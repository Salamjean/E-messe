<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Paiement réussi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script>
    // Redirection automatique vers l'app
    setTimeout(function() {
        window.location.href = "{{ $redirectUrl }}";
    }, 2000);
  </script>
</head>
<body style="text-align:center; font-family:Arial; padding:40px;">
  <h2>✅ Paiement réussi !</h2>
  <p>Référence : <strong>{{ $paiement->reference }}</strong></p>
  <p>Redirection vers l'application en cours...</p>
</body>
</html>
