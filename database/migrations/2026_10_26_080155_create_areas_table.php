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
    Schema::create('areas', function (Blueprint $table) {
        $table->id();
        $table->string('nama_area');
        $table->text('deskripsi')->nullable();
        $table->enum('tingkat_kerawanan', ['rendah', 'sedang', 'tinggi'])->default('sedang');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
