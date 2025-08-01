<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen2_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('topik_id')->constrained('topics')->onDelete('cascade');
            $table->string('rancangan_judul')->unique();
            $table->string('draft_file_path')->nullable();
            $table->string('catatan_validasi')->nullable();
            $table->enum('status_pengajuan', ['Pending', 'Diterima', 'Ditolak'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_submissions');
    }
};
