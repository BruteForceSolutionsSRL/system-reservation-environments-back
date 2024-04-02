<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
            $table->unsignedBigInteger('administrator_id');
            $table->unsignedBigInteger('reservation_id');

            $table->foreign('administrator_id')
                    ->references('id')
                    ->on('administrators')
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
        Schema::dropIfExists('logs');
    }
}
