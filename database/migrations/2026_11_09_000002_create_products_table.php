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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')
                ->constrained('organizations');
            $table->string('name', 150);
            $table->string('category', 100);
            $table->decimal('price', 12, 2); // >= 0, dicek di validasi
            $table->text('description')->nullable();
            $table->string('photo_url', 500)->nullable();
            $table->string('status', 20)->default('Tampil'); // atau 'Sembunyi'
            $table->timestamps();
            $table->index(['organization_id', 'status']);
            $table->index(['organization_id', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
