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
    Schema::create('user_deletes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id'); // ancien ID du user
        $table->string('name')->nullable();
        $table->string('user_name')->nullable();
        $table->string('email')->nullable();
        $table->string('contact')->nullable();
        $table->string('profile_picture')->nullable();
        $table->text('additional_data')->nullable(); // si tu veux stocker JSON (ex: infos liées)
        $table->timestamp('deleted_at');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_deletes');
    }
};
