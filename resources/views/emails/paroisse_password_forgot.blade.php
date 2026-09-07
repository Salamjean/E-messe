<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe Paroisse</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f5f7; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333333;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f5f7; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #cca45e 0%, #b38d45 100%); padding: 35px 20px;">
                            <img src="{{ asset('assets/assets/images/logo_principal.svg') }}" alt="E-Messe Logo" style="width: 100px; max-width: 100px; display: block; margin-bottom: 12px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 700; letter-spacing: 0.5px;">Espace Paroisse E-Messe</h1>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 40px 40px 30px 40px;">
                            <h2 style="color: #1a1a1a; font-size: 20px; font-weight: 700; margin-top: 0; margin-bottom: 20px;">
                                Bonjour {{ $paroisse->name ?? 'Responsable Paroissial' }},
                            </h2>
                            <p style="font-size: 15px; line-height: 1.6; color: #555555; margin-bottom: 25px;">
                                Une demande de réinitialisation de mot de passe a été initiée pour le compte de votre paroisse sur la plateforme <strong>E-Messe</strong>.
                            </p>

                            <!-- OTP Box -->
                            <div style="background-color: #faf7f2; border: 2px dashed #cca45e; border-radius: 12px; padding: 25px; text-align: center; margin: 30px 0;">
                                <span style="font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #b38d45; display: block; margin-bottom: 8px;">
                                    Votre code de vérification OTP
                                </span>
                                <div style="font-size: 36px; font-weight: 800; letter-spacing: 10px; color: #1a1a1a; font-family: 'Courier New', Courier, monospace;">
                                    {{ $code }}
                                </div>
                                <span style="font-size: 12px; color: #888888; display: block; margin-top: 10px;">
                                    ⏱ Ce code expire dans <strong>15 minutes</strong>.
                                </span>
                            </div>

                            <p style="font-size: 14px; line-height: 1.6; color: #666666; margin-bottom: 20px;">
                                Veuillez saisir ce code sur la page de vérification pour définir un nouveau mot de passe.
                            </p>

                            <div style="background-color: #fff9e6; border-left: 4px solid #f1c40f; padding: 12px 16px; border-radius: 6px; margin-bottom: 25px;">
                                <p style="margin: 0; font-size: 13px; color: #7d6608; line-height: 1.5;">
                                    <strong>Important :</strong> Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email en toute sécurité. Vos identifiants restent inchangés.
                                </p>
                            </div>

                            <p style="font-size: 14px; color: #555555; margin-bottom: 5px;">
                                Cordialement,<br>
                                <strong>L’équipe E-Messe</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f8f9fa; padding: 20px 40px; border-top: 1px solid #eeeeee;">
                            <p style="font-size: 12px; color: #999999; margin: 0; line-height: 1.5;">
                                &copy; {{ date('Y') }} E-Messe. Tous droits réservés.<br>
                                Ceci est un message automatique, merci de ne pas y répondre directement.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
