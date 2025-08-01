<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('archived_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('file_path')->nullable();
            $table->string('file_path2')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->timestamp('archived_at')->useCurrent(); // Waktu diarsipkan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archived_periods');
    }
};
