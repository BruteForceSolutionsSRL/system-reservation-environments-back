<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('capacity');
            $table->integer('floor');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
            $table->unsignedBigInteger('block_id');
            $table->unsignedBigInteger('classroom_type_id');

            $table->foreign('block_id')
                    ->references('id')
                    ->on('blocks')
                    ->cascadeOnDelete();
            $table->foreign('classroom_type_id')
                    ->references('id')
                    ->on('classroom_types')
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
        Schema::dropIfExists('classrooms');
    }
}
