<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->integer('repeat');
            $table->date('date');
            $table->unsignedBigInteger('reservation_status_id');
            $table->unsignedBigInteger('reservation_reason_id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('reservation_status_id')
                    ->references('id')
                    ->on('reservation_statuses')
                    ->cascadeOnDelete();
            $table->foreign('reservation_reason_id')
                    ->references('id')
                    ->on('reservation_reasons')
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
        Schema::dropIfExists('reservations');
    }
}
