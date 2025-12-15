<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Paiement échoué</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .card {
      background-color: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      text-align: center;
      max-width: 400px;
      width: 100%;
    }

    .error-icon {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: #D9534F; /* Rouge pour l'erreur */
      display: inline-block;
      position: relative;
      margin-bottom: 20px;
      animation: scaleUp 0.5s ease-in-out;
    }

    .error-icon span {
      display: block;
      position: absolute;
      top: 50%;
      left: 50%;
      width: 40px;
      height: 6px;
      background-color: white;
      transform-origin: center;
    }

    .error-icon .left {
      transform: translate(-50%, -50%) rotate(45deg);
    }

    .error-icon .right {
      transform: translate(-50%, -50%) rotate(-45deg);
    }
    
    @keyframes scaleUp {
        0% { transform: scale(0.7); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    h2 {
      color: #D9534F; /* Couleur du titre assortie à l'icône */
      margin-top: 0;
    }

    p {
      color: #666;
      line-height: 1.5;
    }
    
    .error-message {
        font-weight: bold;
        color: #333;
    }

    .spinner {
      border: 4px solid rgba(0, 0, 0, 0.1);
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border-left-color: #D9534F; /* Couleur du spinner assortie */
      animation: spin 1s ease infinite;
      margin: 20px auto;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }
  </style>
  <script>
    setTimeout(function() {
        window.location.href = "{{ $redirectUrl ?? 'sanctaapp://paiement?status=error' }}";
    }, 3000);
  </script>
</head>
<body>

  <div class="card">
    <div class="error-icon">
        <span class="left"></span>
        <span class="right"></span>
    </div>
    <h2>Échec du paiement</h2>
    <p class="error-message">{{ $message ?? 'Une erreur est survenue lors de la transaction.' }}</p>
    <p>Vous allez être redirigé vers l’application.</p>
    <div class="spinner"></div>
  </div>

</body>
</html>