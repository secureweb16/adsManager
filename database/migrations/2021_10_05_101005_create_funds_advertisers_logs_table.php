<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundsAdvertisersLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funds_advertisers_logs', function (Blueprint $table) {
            
            $table->id();            
            $table->string('amount1');
            $table->string('amount2');
            $table->string('buyer_name')->nullable();
            $table->string('currency1')->nullable();
            $table->string('currency2')->nullable();
            $table->string('email')->nullable();
            $table->string('fee')->nullable();
            $table->string('ipn_id')->nullable();
            $table->string('ipn_mode')->nullable();
            $table->string('ipn_type')->nullable();
            $table->string('ipn_version')->nullable();
            $table->string('received_amount')->nullable();
            $table->string('received_confirms')->nullable();
            $table->string('send_tx')->nullable();
            $table->string('status')->nullable();
            $table->string('status_text')->nullable();
            $table->string('txn_id')->nullable();
            $table->string('merchant')->nullable();

            $table->unsignedBigInteger('transectionid')->nullable();
            $table->foreign('transectionid')->references('id')->on('coinpayment_transactions')->onDelete('CASCADE');                     
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
        Schema::dropIfExists('funds_advertisers_logs');
    }
}
