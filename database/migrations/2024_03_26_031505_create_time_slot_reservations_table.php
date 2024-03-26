<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeSlotReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_slot_reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('time_slot_id');
            $table->unsignedBigInteger('reservation_id');

            $table->foreign('time_slot_id')
                    ->references('id')
                    ->on('time_slots')
                    ->cascadeOnDelete();
            $table->foreign('reservation_id')
                    ->references('id')
                    ->on('reservations')
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
        Schema::dropIfExists('time_slot_reservations');
    }
}
