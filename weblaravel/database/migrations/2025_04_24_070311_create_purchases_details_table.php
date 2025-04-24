<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('purchases_details', function (Blueprint $table) {
            $table->bigIncrements('id');           // surrogate PK
            $table->unsignedBigInteger('pur_id');
            $table->unsignedBigInteger('cust_id');
            $table->unsignedBigInteger('item_id');
            $table->string('item_name');
            $table->decimal('price_paid', 10, 2);  // price per item
            $table->integer('qty');
            $table->timestamps();

            // Composite uniqueness to prevent duplicate (pur,cust,item)
            $table->unique(['pur_id', 'cust_id', 'item_id']);

            $table->foreign('pur_id')->references('pur_id')->on('purchases')->onDelete('cascade');
            // $table->foreign('cust_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('item_id')->references('item_id')->on('items')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchases_details');
    }
}

