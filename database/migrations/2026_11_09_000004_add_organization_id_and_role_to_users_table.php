<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Kenapa kolom ini dipisah ke file sendiri: migration bawaan
     * `users` Laravel bertimestamp 0001_01_01_000000, jadi dieksekusi
     * SEBELUM migration `organizations` mana pun yang dibuat hari ini.
     * Kalau foreign key ke `organizations` ditulis di migration bawaan
     * itu, `php artisan migrate` gagal karena tabel yang dirujuk belum
     * ada. File ini sengaja bernomor urut terakhir (000004), jadi
     * `organizations` sudah terbentuk saat foreign key dibuat.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()
                ->constrained('organizations'); // NULL = vendor
            $table->string('role', 20); // 'vendor' atau 'umkm_admin'
            $table->index('organization_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn(['organization_id', 'role']);
        });
    }
};
