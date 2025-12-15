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
        Schema::create('parish_success_stories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->string('participation_increase')->nullable();
            $table->text('description');
            $table->integer('active_users')->default(0);
            $table->integer('masses_reserved')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parish_success_stories');
    }
};
