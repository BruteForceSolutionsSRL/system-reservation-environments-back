<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_subjects', function (Blueprint $table) {
            $table->id();
            $table->integer('group_number');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('university_subject_id');

            $table->foreign('teacher_id')
                    ->references('id')
                    ->on('teachers')
                    ->cascadeOnDelete();
            $table->foreign('university_subject_id')
                    ->references('id')
                    ->on('university_subjects')
                    ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_subjects');
    }
}
