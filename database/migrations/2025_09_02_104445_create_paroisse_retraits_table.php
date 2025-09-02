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
        Schema::create('paroisse_retraits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paroisse_id')->constrained()->onDelete('cascade');
            $table->decimal('montant', 15, 2);
            $table->string('methode'); // wave, orange_money, mtn_money, virement_bancaire, etc.
            $table->string('numero_compte'); // numéro de téléphone ou compte
            $table->string('nom_titulaire'); // nom du titulaire du compte
            $table->string('statut')->default('en_attente'); // en_attente, traite, rejete, complete
            $table->text('informations_supplementaires')->nullable();
            $table->string('reference')->unique();
            $table->timestamp('traite_le')->nullable();
            $table->timestamps();
            
            $table->index('reference');
            $table->index('statut');
            $table->index('paroisse_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paroisse_retraits');
    }
};
