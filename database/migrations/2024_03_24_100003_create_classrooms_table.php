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
            $table->string('name')->unique(); 
            $table->integer('capacity'); 
            $table->integer('floor'); 

            $table->unsignedBigInteger('block_id'); 
            $table->unsignedBigInteger('classroom_type_id'); 
            // foreign keys: 
            $table->foreign('block_id')->references('id')->on('blocks')->onDelete('cascade');
            $table->foreign('classroom_type_id')->references('id')->on('classroom__types')->onDelete('cascade');

            $table->timestamps();
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
