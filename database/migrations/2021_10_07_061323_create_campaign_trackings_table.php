<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('campaign_trackings', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('campaign_id')->nullable();
        $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('CASCADE');
        $table->string('traking_id')->nullable();
        $table->string('utmf')->nullable();
        $table->string('landing_url')->nullable();
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
      Schema::dropIfExists('campaign_trackings');
    }
  }
