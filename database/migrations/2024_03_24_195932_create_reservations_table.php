<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->integer('number_of_students'); 
            $table->date('date');

            $table->unsignedBigInteger('reservation_status_id'); 
            $table->unsignedBigInteger('start_time'); 
            $table->unsignedBigInteger('end_time'); 
        

            // foreign keys; 
            $table->foreign('reservation_status_id')->references('id')->on('reservation__statuses')->onDelete('cascade');
            $table->foreign('start_time')->references('id')->on('time__slots')->onDelete('cascade'); 
            $table->foreign('end_time')->references('id')->on('time__slots')->onDelete('cascade');  
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
        Schema::dropIfExists('reservations');
    }
}
