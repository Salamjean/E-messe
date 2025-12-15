<?php

// app/Notifications/TestNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Brozot\LaravelFcm\FcmMessage;

class TestNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        // Pas besoin d'arguments pour un test simple
    }

    public function via($notifiable)
    {
        // Envoi via Firebase (fcm) et sauvegarde dans la base de données de Laravel
        return ['fcm', 'database'];
    }

    public function toFcm($notifiable)
    {
        return (new FcmMessage)
            ->notification([
                'title' => '🔔 Test Réussi !',
                'body' => 'Ceci est une notification de test réussie !',
            ])
            ->data([
                'type' => 'test_notif',
                'timestamp' => now()->timestamp
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Test Réussi !',
            'body' => 'Ceci est une notification de test réussie !',
            'type' => 'test_notif',
        ];
    }
}

// {
//   "type": "service_account",
//   "project_id": "testemesse",
//   "private_key_id": "81b8562ca379299422c2771e28f278fbef5925a6",
//   "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDKNeRPV5gB389v\nL5FLOD2THqUnMhI+nGndS7bQ5isEWKs0TQxp5MyZ9z606SjGF6PxESQmsF3xxsaH\nNStbGCqfZbpfTEd7xzgsjUbEx9sYYFHVXAv+TsTaeyzyOd234Fg/gGxBklbyDJgH\nuU2eHxz1Ch3OeYL++os5G040yXQMgl84ESP/gdHyd/CkkUZI0yR1jiQ58FmYjA+c\naJyS9rtTN88NBXsHtiedQWOrBFOgTzygGyqSyNv58OqTlg3XlmEDxv8AzojiKva3\ny8fQ+8WsIgVG2Cxo8Ab5QHRuoyf6Bj7BFuFNE2aTMClI/u90kLUqEOif6O0wXMTZ\nreDMETpbAgMBAAECggEAIY14sUulDuaOunHHp5YQ7Yj/uXW+/kGg9VXbY2VVzPZT\nji2cujN7Wa7jakxn9hq1GsqP15WbOQOVLJk/dPGZHPLZzndPQzQm14mnrP5GlhBh\nc8g+uzdhxaj7p8O/TQP+UKckD2tTXv2E+n8eaHbcLLK9iGW0PU/gaxzjSLIGvg5W\nFgypHGdG7tK5SRWHMd7RCzL7+HzhvWfHfj6J1BRjFdI4ivDc4YnfMdZgrYxp6PUC\nbYfqeLkUzQxC6g4IZPAkG6g7N/SkGEp7aveq5IH6PrWgkzatTOBhGsBLIgpRKMpT\nOcMGEKQWJNVLTIDOpt0Ezjy22C2XDf61iC4IBZGmqQKBgQD6zyFuy7CEQZQgqW2i\n/Y5RVXr11b3JmbBaGwF74DwrLXNbNJeNVbMxHAGm56a14DxqTgtzsicxfc7wEH8M\nz9Gd3BjXGR2Lwp4ODYgJI/hxVSEHTpg1dWAy+h6OPi3Odd2Bs8dpkjp3DuKUxk6p\nTQeMgK9mR15ccTLl7/Hqe1p6QwKBgQDOZUUWoGqSszF5ABlbA0d8pVp42mzqgU/d\nPh770WLhK/yjFsrlt5jAM7C/P51U7aO+nZLl9PJIWZKW3bOdIitasfc7JUpe+Wxn\nt/+8vccIwY4Lf3ocgqRGMUQbgKQrLDKIAuk7WrN8h/oZegLXb4VmZ4wR50AQPpYi\n+TwUvXV6CQKBgQCpG1eTeMs/oWfazeIab5Oxy0zozID2mSWK24KYn4xyIGcTK9az\neQElL5j9jFufbd3OX2jhTVGX0RfiTX0cilLveSNWhJyjS0l+eCks4I/2+ksWvitq\ngAWo2XQYvFvuRRJhDXJ7ABljhSnI3hzTkhhw56Nb9urssXZ8Cti2HQ3YOQKBgDa3\nRi+Pa3Fkq/LmAdLM9cCnnWp4jXNFwMRoWZJsmggTvzAFNNjdaVNa4LpyxOTk8wnv\ngCgSXcCsbl4sfBdmHZQ3pdE6O5MeEI0WjGee8Ar0NRH7Q4YXZDFvkhywZ9VCLEs+\nalJf40FaUwU6AVLXr8fMH6gHZEZqMScoZ7Mf3urxAoGBAKbM7zq1lPxc1yT32Acx\nf8aCf7rt/UP26fa53tZOS+oFI0RCDJ2yLiERUsNwCNXa6JyAeR3Oh1UNVF3zY3Cf\nvFnjsAnKFZ2tvr1i6dKw1zyKDsVmktl5y/eHSDiXxulPyQUF9I7EQwSUNOPjXt5J\nfIKgfUEp6mGuplym6B3bNmRj\n-----END PRIVATE KEY-----\n",
//   "client_email": "firebase-adminsdk-fbsvc@testemesse.iam.gserviceaccount.com",
//   "client_id": "107204591736941256718",
//   "auth_uri": "https://accounts.google.com/o/oauth2/auth",
//   "token_uri": "https://oauth2.googleapis.com/token",
//   "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
//   "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-fbsvc%40testemesse.iam.gserviceaccount.com",
//   "universe_domain": "googleapis.com"
// }
