<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribe', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('lang')->null();
            $table->string('name')->null();
            $table->string('companyname')->null();
            $table->string('email')->null();
            $table->string('phonecompany')->null();
            $table->string('phone')->null();
            $table->string('job')->null();
            $table->string('industrialsector')->null();
            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribe');
    }
}
