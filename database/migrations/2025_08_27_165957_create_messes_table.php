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
        Schema::create('messes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('paroisse_id')->nullable()->constrained('paroisses')->onDelete('set null');
            $table->enum('type_intention', ['Defunt', 'Action graces', 'Intention particuliere']);
            $table->string('nom_defunt')->nullable();
            $table->string('motif_action_graces')->nullable();
            $table->string('motif_intention')->nullable();
            $table->string('nom_prenom_concernes');
            $table->date('date_souhaitee');
            $table->time('heure_souhaitee')->nullable();
            $table->string('celebration_choisie')->nullable();
            $table->string('nom_demandeur');
            $table->string('email_demandeur');
            $table->string('telephone_demandeur');
            $table->enum('statut', ['en attente', 'confirmee', 'celebre', 'annulee','en_attente_paiement']);
            $table->decimal('montant_offrande', 8, 2)->nullable();
            $table->text('dates_selectionnees')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messes');
    }
};
