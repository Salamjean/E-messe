<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Réinitialisation du mot de passe</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; line-height: 1.6;">
        <img src="{{ asset('assets/assets/img/logoplateau.png') }}" alt="E-Messe Logo" style="width: 120px; margin-bottom: 20px;">

        <h2>Bonjour {{ $user->name }},</h2>

        <p>Vous avez demandé à réinitialiser votre mot de passe pour E-Messe.</p>

        <p>Voici votre code de réinitialisation :</p>
        <h3 style="background: #f2f2f2; padding: 10px; display: inline-block;">{{ $code }}</h3>

        <p>Ce code est valable pendant 10 minutes. Ne le partagez avec personne.</p>

        <p>
            Cliquez sur le bouton ci-dessous pour ouvrir l’application et saisir votre code :<br><br>
            <a href="https:http://sancta-missa.com/reset-password" 
               style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                Ouvrir l'application
            </a>
        </p>

        <p>Si vous n’avez pas demandé cette réinitialisation, vous pouvez ignorer ce mail.</p>

        <p>Cordialement,<br>L’équipe E-Messe</p>
    </div>
</body>
</html>
