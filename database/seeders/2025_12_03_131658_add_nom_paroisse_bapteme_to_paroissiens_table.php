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
        Schema::table('paroissiens', function (Blueprint $table) {
            $table->string('nom_paroisse_bapteme')->nullable()->after('date_bapteme');
        });
    }

    public function down(): void
    {
        Schema::table('paroissiens', function (Blueprint $table) {
            $table->dropColumn('nom_paroisse_bapteme');
        });
    }

};
