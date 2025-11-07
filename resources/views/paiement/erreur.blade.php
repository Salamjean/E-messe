<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Paiement échoué</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script>
    setTimeout(function() {
        window.location.href = "{{ $redirectUrl ?? 'sanctaapp://paiement?status=error' }}";
    }, 3000);
  </script>
</head>
<body style="text-align:center; font-family:Arial; padding:40px;">
  <h2>❌ Échec du paiement</h2>
  <p>{{ $message ?? 'Une erreur est survenue.' }}</p>
  <p>Redirection vers l’application...</p>
</body>
</html>
