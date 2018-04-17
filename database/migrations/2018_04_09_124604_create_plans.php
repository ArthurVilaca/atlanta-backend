<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->double('value');
            $table->string('description');
            $table->double('pass_through');

            $table->timestamps();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->integer('plans_id')->nullable()->unsigned();
            $table->foreign('plans_id')->references('id')->on('plans');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->integer('dealers_id')->nullable()->unsigned();
            $table->foreign('dealers_id')->references('id')->on('dealers');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('cpf')->nullable();
            $table->string('cnpj')->nullable();
            $table->dateTime('birthdate')->nullable();

            $table->string('adress')->nullable();
            $table->string('adress_number')->nullable();
            $table->string('adress_complement')->nullable();
            $table->string('adress_district')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
        });

        Schema::table('dealers', function (Blueprint $table) {
            $table->string('bank')->nullable();
            $table->string('agency')->nullable();
            $table->string('account')->nullable();
        });

        Schema::create('clusters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cluster_name');
            $table->string('task_definition');

            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('clusters_id');

            $table->integer('plans_id')->unsigned();
            $table->foreign('plans_id')->references('id')->on('plans');

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
        Schema::dropIfExists('plans');
        Schema::dropIfExists('clusters');
        Schema::dropIfExists('services');
        Schema::dropIfExists('tasks');
    }
}
