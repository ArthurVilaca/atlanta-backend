<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_components', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_config');
            $table->string('text1')->nullable();
            $table->string('text2')->nullable();
            $table->string('text3')->nullable();
            $table->string('text4')->nullable();
            $table->string('text5')->nullable();
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->string('background_color')->nullable();
            $table->string('min_height')->nullable();
            $table->boolean('can_edit_background_image')->nullable();
            $table->boolean('can_edit_background_color')->nullable();
            $table->string('background_image')->nullable();

            $table->integer('component_id')->unsigned();
            $table->foreign('component_id')->references('id')->on('components');

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
        Schema::dropIfExists('config_components');
    }
}
