<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('pur_id');
            $table->unsignedBigInteger('cust_id');
            $table->date('date');
            $table->decimal('total', 12, 2);
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}

