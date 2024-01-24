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
        Schema::create('schedule_interviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->string('interview_date');
            $table->string('interview_time');
            $table->enum('interview_status',['selected','pending','rejected ']);
            $table->longText('reason');
            $table->timestamps();
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_interviews');
    }
};
