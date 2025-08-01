<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_open')->default(false);
            $table->enum('type', ['Pengajuan Proposal', 'Seminar Proposal', 'Seminar Pembahasan', 'Sidang Ujian', 'Sidang Mandiri']);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('periods');
    }
};
