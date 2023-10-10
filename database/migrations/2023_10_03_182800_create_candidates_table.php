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
            $table->unsignedBigInteger('candidate_role_id')->nullable();
            $table->string('candidate_name');
            $table->string('email')->unique();
            $table->date('date')->nullable();
            $table->string('source')->nullable();
            $table->string('experience')->nullable();
            $table->string('contact')->unique();
            $table->string('contact_by')->nullable();
            $table->string('status')->nullable();
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
