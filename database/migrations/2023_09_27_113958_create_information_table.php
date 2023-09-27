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
        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->string('candidate_name');
            $table->string('email')->unique();
            $table->string('categories');
            $table->string('date');
            $table->string('source');
            $table->string('experience');
            $table->string('contact');
            $table->string('status');
            $table->string('salary');
            $table->string('expectation');
            $table->string('upload_resume');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('information');
    }
};
