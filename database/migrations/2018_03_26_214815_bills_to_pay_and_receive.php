<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BillsToPayAndReceive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billspays', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('dealer_id')->unsigned();
            $table->foreign('dealer_id')->references('id')->on('dealers');

            $table->double('amount');
            $table->string('month_reference');

            $table->dateTime('issue_date');
            $table->dateTime('due_date');
            $table->dateTime('payment_date')->nullable();

            $table->timestamps();
        });

        Schema::create('billsreceives', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients');

            $table->double('amount');
            $table->string('month_reference');

            $table->dateTime('issue_date');
            $table->dateTime('due_date');
            $table->dateTime('payment_date')->nullable();
            $table->timestamps();
        });

        Schema::create('remittances', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients');

            $table->double('amount_total');
            $table->string('month_reference');
            $table->string('description');

            $table->dateTime('due_date');
            $table->dateTime('payment_date')->nullable();
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
        Schema::dropIfExists('billspay');
        Schema::dropIfExists('billsreceive');
    }
}
