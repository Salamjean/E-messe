# Plan de correction de l'envoi d'e-mails

L'utilisateur rencontre des problèmes d'envoi d'e-mails : non reçus sur Gmail et classés comme spam sur les adresses professionnelles. Cela provient généralement d'une configuration SMTP incomplète ou d'une incohérence dans les en-têtes "From".

## Actions à entreprendre

### 1. Uniformisation des adresses d'expédition

Actuellement, certaines notifications ont des adresses d'expédition (From) codées en dur, ce qui peut créer des conflits avec la configuration du serveur SMTP (Hostinger).

-   Modifier `app/Notifications/SendEmailToParoisseAfterRegistrationNotification.php` pour utiliser la configuration globale.
-   Modifier `routes/api.php` (routes de test) pour utiliser la configuration globale.

### 2. Optimisation de la configuration SMTP (.env)

Vérifier et suggérer des ajustements pour Hostinger :

-   S'assurer que `MAIL_FROM_ADDRESS` est identique à `MAIL_USERNAME`.
-   Utiliser le port `465` avec `ssl` ou `587` avec `tls`.

### 3. Recommandations DNS (Hors code)

Pour éviter que Gmail ne bloque les mails ou que les pros les mettent en spam, il **faut** que le domaine possède :

-   Un enregistrement **SPF** valide incluant les serveurs de Hostinger.
-   Un enregistrement **DKIM** configuré.
-   Un enregistrement **DMARC**.

## Fichiers à modifier

-   `app/Notifications/SendEmailToParoisseAfterRegistrationNotification.php`
-   `routes/api.php`
-   Suggérer des modifications pour `.env` (si l'utilisateur me permet de les voir/modifier).
