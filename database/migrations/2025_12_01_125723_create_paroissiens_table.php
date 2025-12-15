<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('paroissiens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('nom_prenom');
            $table->date('date_naissance');
            $table->enum('sexe', ['M', 'F']);
            $table->string('situation_matrimoniale');
            $table->string('adresse');
            $table->string('statut_activite');
            $table->string('nom_paroisse');
            $table->string('telephone');

            // Logique Mouvement
            $table->boolean('est_dans_mouvement')->default(false);
            $table->string('nom_mouvement')->nullable(); // Si oui

            // Logique Baptême
            $table->boolean('est_baptise')->default(false);
            $table->date('date_bapteme')->nullable(); // Si oui
            $table->string('nom_paroisse_bapteme')->nullable(); // Si oui

            $table->string('photo')->nullable(); // Chemin vers la photo
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paroissiens');
    }
};
