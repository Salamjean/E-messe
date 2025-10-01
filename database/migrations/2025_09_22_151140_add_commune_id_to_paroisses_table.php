<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('paroisses', function (Blueprint $table) {
         // Ajoute la clé étrangère pour la commune après la colonne 'name'
         $table->foreignId('commune_id')->nullable()->after('name')->constrained('communes')->onDelete('set null');
            
         // Supprime l'ancienne colonne 'localisation'
         $table->dropColumn('localisation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paroisses', function (Blueprint $table) {
            // Pour revenir en arrière, on supprime la clé étrangère et la colonne
            $table->dropForeign(['commune_id']);
            $table->dropColumn('commune_id');
            
            // Et on recrée l'ancienne colonne
            $table->string('localisation')->nullable();
        });
    }
};
