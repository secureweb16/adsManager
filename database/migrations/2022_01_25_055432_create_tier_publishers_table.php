<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTierPublishersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tier_publishers', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('tier_id')->nullable();
          $table->foreign('tier_id')->references('id')->on('tiers')->onDelete('CASCADE');
          $table->bigInteger('publisher_id')->nullable(); 
          $table->float('minimun_cpc')->nullable();
          $table->float('payout')->nullable();
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
        Schema::dropIfExists('tier_publishers');
    }
}
