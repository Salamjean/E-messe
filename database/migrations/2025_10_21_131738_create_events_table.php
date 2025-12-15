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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('type_event');
            $table->text('description')->nullable();
            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();
            $table->string('lieu')->nullable();
            $table->string('celebrant')->nullable();
            $table->string('organisateur')->nullable();
            $table->decimal('participation_frais', 8, 2)->nullable();
            $table->integer('nombre_participants')->nullable();
            $table->enum('statut', ['Prévu', 'En cours', 'Terminé', 'Annulé'])->default('Prévu');
            $table->string('image')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
