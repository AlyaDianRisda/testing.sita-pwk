<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('focus');
            $table->integer('kuota_topik')->nullable();
            $table->foreignId('period_id')->nullable()->constrained('periods')->onDelete('cascade');
            $table->foreignId('dosen_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
