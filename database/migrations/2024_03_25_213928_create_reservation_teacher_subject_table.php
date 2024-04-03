<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationTeacherSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_teacher_subject', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id');
            $table->unsignedBigInteger('teacher_subject_id');
            $table->timestamps();

            $table->foreign('reservation_id')
                    ->references('id')
                    ->on('reservations')
                    ->cascadeOnDelete();
            $table->foreign('teacher_subject_id')
                    ->references('id')
                    ->on('teacher_subjects')
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
        Schema::dropIfExists('reservation_teacher_subjects');
    }
}
