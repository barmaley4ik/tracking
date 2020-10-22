<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',100);
            $table->string('vin',17)->unique();
            $table->jsonb('image_auction')->nullable();
            $table->jsonb('image_taken_auction')->nullable();
            $table->jsonb('image_in_stock')->nullable();
            $table->jsonb('image_delivered')->nullable();
            $table->jsonb('image_left_to_client')->nullable();
            $table->string('creator');
            $table->string('updater');
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
        Schema::dropIfExists('cars');
    }
}
