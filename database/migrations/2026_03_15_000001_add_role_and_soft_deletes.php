<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom role ke users dan soft_deletes ke users + barangs.
     */
    public function up(): void
    {
        // Tambah kolom role ke users
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('operator')->after('email');
            $table->softDeletes();
        });

        // Tambah soft deletes ke barangs
        Schema::table('barangs', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Set admin role untuk user dengan email admin@mbg.id
        \App\Models\User::where('email', 'admin@mbg.id')
            ->update(['role' => 'admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropSoftDeletes();
        });

        Schema::table('barangs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
