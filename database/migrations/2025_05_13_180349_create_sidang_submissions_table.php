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
        Schema::create('sidang_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->foreignId('topik_id')->constrained('topics')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen2_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('penguji_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('penguji2_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->date('jadwal_sidang')->nullable();
            $table->time('waktu_sidang')->nullable();
            $table->string('lokasi_sidang')->nullable();
            $table->enum('skema_sidang', ['Online', 'Offline'])->nullable();
            $table->string('link_sidang')->nullable();
            $table->enum('tipe_sidang', ['Seminar Proposal', 'Seminar Pembahasan', 'Sidang Ujian', 'Ubah Nilai'])->default('Seminar Proposal');
            $table->enum('tipe_pengajuan', ['Pengajuan Periode', 'Pengajuan Mandiri'])->default('Pengajuan Periode');
            $table->enum('status_sidang', ['Dibuat', 'Ditolak', 'Pending', 'Dinilai', 'Selesai'])->default('Pending');
            $table->string('hasil')->nullable();
            $table->timestamps();

            /** Untuk File
             * Fsp (sempro)
             * Fsh (semhas)
             * Fsu (sidu)
             * FN (Penilaian)
             */
            $table->string('fsp1_pendaftaran')->nullable();
            $table->string('fsp2_logbook')->nullable();
            $table->string('fsp3_draft')->nullable();
            $table->string('fsp4_nilai')->nullable();

            $table->string('fsh1_pendaftaran')->nullable();
            $table->string('fsh2_logbook')->nullable();
            $table->string('fsh3_draft')->nullable();
            $table->string('fsh4_nilai')->nullable();

            $table->string('fsu1_buku')->nullable();
            $table->string('fsu2_logbook')->nullable();
            $table->string('fsu3_ba')->nullable();
            $table->string('fsu4_pengesahan')->nullable();
            $table->string('fsu5_nilai')->nullable();

            $table->string('un1_buku')->nullable();
            $table->string('un2_logbook')->nullable();
            $table->string('un3_ba')->nullable();
            $table->string('un4_pengesahan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sidang_submissions');
    }
};
