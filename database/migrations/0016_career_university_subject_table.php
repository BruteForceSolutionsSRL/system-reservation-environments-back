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
        Schema::create('career_university_subject', function (Blueprint $table) {
            $table->id();
            $table->string('grade');
            $table->unsignedBigInteger('career_id');
            $table->unsignedBigInteger('university_subject_id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('career_id')
                    ->references('id')
                    ->on('careers')
                    ->cascadeOnDelete();
            $table->foreign('university_subject_id')
                    ->references('id')
                    ->on('university_subjects')
                    ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_university_subject');
    }
};
