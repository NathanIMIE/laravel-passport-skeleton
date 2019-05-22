<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->timestamp('first_assignation')->nullable();
            $table->timestamp('last_assignation')->nullable();
            $table->string('title');
            $table->string('description');
            $table->string('priority');
            $table->string('state');
            $table->unsignedBigInteger('id_proprietaire');
            $table->foreign('id_proprietaire')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('id_assignation');
            $table->foreign('id_assignation')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
