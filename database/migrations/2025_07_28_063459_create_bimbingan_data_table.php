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
        Schema::create('bimbingan_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen2_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('topik_id')->constrained('topics')->onDelete('cascade');
            $table->string('judul')->unique();
            $table->enum('status_bimbingan', ['Berjalan', 'Selesai'])->default('Berjalan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimbingan_data');
    }
};
