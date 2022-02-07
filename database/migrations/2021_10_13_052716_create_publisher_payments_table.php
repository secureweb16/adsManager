<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublisherPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('publisher_payments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('publisher_id')->nullable();
        $table->foreign('publisher_id')->references('id')->on('users')->onDelete('CASCADE');
        $table->float('user_amount')->nullable();
        $table->float('admin_amount')->nullable();
        $table->float('paid_amount')->nullable();
        $table->float('payable_amount')->nullable();
        $table->float('total_amount')->nullable();        
        $table->date('paid_date')->nullable();
        $table->float('weekly_amount')->nullable();
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
      Schema::dropIfExists('publisher_payments');
    }
  }
