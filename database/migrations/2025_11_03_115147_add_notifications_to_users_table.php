<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('emailNotif')->default(true)->after('actif');
            $table->boolean('smsNotif')->default(false)->after('emailNotif');
            $table->boolean('pushNotif')->default(true)->after('smsNotif');
        });

        DB::table('users')->update([
            'emailNotif' => true,
            'smsNotif' => false,
            'pushNotif' => true
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['emailNotif', 'smsNotif', 'pushNotif']);
        });
    }
};
