<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Paiement réussi</title>
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

    .success-icon {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: #4CAF50;
      display: inline-block;
      position: relative;
      margin-bottom: 20px;
    }

    .success-icon::before, .success-icon::after {
      content: '';
      position: absolute;
      background-color: white;
    }

    .success-icon::before {
      width: 5px;
      height: 25px;
      left: 37px;
      top: 40px;
      transform: rotate(-45deg);
    }

    .success-icon::after {
      width: 5px;
      height: 45px;
      left: 25px;
      top: 28px;
      transform: rotate(45deg);
    }
    
    /* Animation de la coche */
    .checkmark {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      display: block;
      stroke-width: 2;
      stroke: #fff;
      stroke-miterlimit: 10;
      margin: 10% auto;
      box-shadow: inset 0px 0px 0px #4caf50;
      animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    }
    
    .checkmark__circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: #4caf50;
        fill: #fff;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }

    .checkmark__check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }

    @keyframes stroke {
        100% {
            stroke-dashoffset: 0;
        }
    }

    @keyframes scale {
        0%, 100% {
            transform: none;
        }
        50% {
            transform: scale3d(1.1, 1.1, 1);
        }
    }

    @keyframes fill {
        100% {
            box-shadow: inset 0px 0px 0px 30px #4caf50;
        }
    }

    h2 {
      color: #333;
      margin-top: 0;
    }

    p {
      color: #666;
      line-height: 1.5;
    }

    strong {
      color: #333;
    }

    .spinner {
      border: 4px solid rgba(0, 0, 0, 0.1);
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border-left-color: #4CAF50;
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
    // Redirection automatique vers l'app
    setTimeout(function() {
        window.location.href = "{{ $redirectUrl }}";
    }, 3000); // Augmenté à 3 secondes pour que l'utilisateur ait le temps de voir le message
  </script>
</head>
<body>

  <div class="card">
    <div style="border-radius:200px; height:100px; width:100px; background: #F8FAF5; margin:0 auto;">
      <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
        <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
        <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
      </svg>
    </div>
    <h2>Paiement réussi !</h2>
    <p>Référence : <strong>{{ $paiement->reference }}</strong></p>
    <p>Vous allez être redirigé vers l'application.</p>
    <div class="spinner"></div>
  </div>

</body>
</html>