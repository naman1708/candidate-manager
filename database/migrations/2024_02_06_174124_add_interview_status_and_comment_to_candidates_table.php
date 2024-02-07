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
        Schema::table('candidates', function (Blueprint $table) {
            $table->text('superadmin_instruction')->nullable()->after('expectation');
            $table->enum('interview_status_tag', ['interview scheduled','interviewed', 'rejected', 'selected'])->nullable()->after('upload_resume');
            $table->text('comment')->nullable()->after('upload_resume');
            $table->unsignedBigInteger('user_id')->nullable()->after('candidate_role_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn('superadmin_instruction');
            $table->dropColumn('interview_status_tage');
            $table->dropColumn('comment');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
