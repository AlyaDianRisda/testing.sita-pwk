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
        Schema::create('proposal_kuota_dosens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->nullable()->constrained('periods')->onDelete('cascade');
            $table->foreignId('dosen_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->integer('kuota_total');
            $table->integer('kuota_tersisa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_kuota_dosens');
    }
};
