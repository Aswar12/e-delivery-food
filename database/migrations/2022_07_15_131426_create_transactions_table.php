<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('food_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->float('total')->nullable();
            $table->string('status')->default('PENDING');
            $table->text('payment_url')->nullable();
            $table->text('address')->nullable();
            $table->float('shipping_price');
            
            $table->softDeletes();
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
        Schema::dropIfExists('transactions');
    }
};
