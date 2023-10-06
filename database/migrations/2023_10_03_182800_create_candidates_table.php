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
            $table->unsignedBigInteger('candidate_role_id');
            $table->string('candidate_name');
            $table->string('email')->unique();
            $table->date('date');
            $table->string('source')->nullable();
            $table->string('experience');
            $table->string('contact')->unique();
            $table->string('contact_by');
            $table->string('status');
            $table->string('salary')->nullable();
            $table->string('expectation')->nullable();
            $table->string('upload_resume')->nullable();
            $table->timestamps();
            $table->foreign('candidate_role_id')->references('id')->on('candidate_roles')->onDelete('cascade');
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
