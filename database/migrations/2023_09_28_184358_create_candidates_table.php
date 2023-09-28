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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('candidate_name');
            $table->string('email')->unique();
            $table->string('categories');
            $table->string('date');
            $table->string('source')->nullable();
            $table->string('experience');
            $table->string('contact')->unique();
            $table->string('contact_by');
            $table->string('status');
            $table->string('salary')->nullable();
            $table->string('expectation')->nullable();
            $table->string('upload_resume')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
