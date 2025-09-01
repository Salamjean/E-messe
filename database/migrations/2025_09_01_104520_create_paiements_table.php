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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('messe_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reference')->unique();
            $table->decimal('montant', 10, 2);
            $table->string('devise')->default('XOF');
            $table->string('methode')->default('wave');
            $table->string('statut')->default('en_attente');
            $table->string('transaction_id')->nullable();
            $table->text('donnees_transaction')->nullable();
            $table->timestamp('date_paiement')->nullable();
            $table->timestamps(); // Gardez une seule occurrence de timestamps
            
            $table->index('reference');
            $table->index('statut');
            $table->index('transaction_id');
            // Supprimez le deuxième appel à timestamps()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};