<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code OTP - Réinitialisation du mot de passe</title>

    <style>
        body {
            background-color: #f5f7fa;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 500px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .header {
            background-color: #4a90e2;
            color: white;
            text-align: center;
            padding: 25px 0;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
        }

        .body {
            padding: 25px;
            color: #333333;
            font-size: 15px;
            line-height: 1.6;
        }

        .otp-box {
            margin: 25px auto;
            padding: 15px;
            width: 200px;
            font-size: 30px;
            text-align: center;
            letter-spacing: 5px;
            background: #f0f4ff;
            color: #1a3fa0;
            border-radius: 8px;
            border: 1px solid #c7d3ff;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #777777;
        }
    </style>
</head>
<body>

<div class="email-container">

    <div class="header">
        <h1>Code de Réinitialisation</h1>
    </div>

    <div class="body">
        <p>Bonjour {{ $user->name ?? '' }},</p>

        <p>Vous avez demandé à réinitialiser votre mot de passe.  
        Veuillez utiliser le code ci-dessous pour continuer :</p>

        <div class="otp-box">
            {{ $otp }}
        </div>

        <p>Ce code est valable pendant <strong>10 minutes</strong>.</p>

        <p>Si vous n’êtes pas à l’origine de cette demande, vous pouvez ignorer cet e-mail.</p>

        <p>Cordialement,<br>
        <strong>L’équipe Emesse</strong></p>
    </div>

    <div class="footer">
        © {{ date('Y') }} Emesse. Tous droits réservés.
    </div>

</div>

</body>
</html>
