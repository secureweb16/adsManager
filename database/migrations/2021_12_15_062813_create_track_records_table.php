<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('track_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('publisher_id')->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->unsignedBigInteger('clicks')->nullable();
            $table->float('total_cost')->nullable();
            $table->float('remaining_cost')->nullable();
            $table->float('Deducted_cost')->nullable();
            $table->float('average_cost')->nullable();
            $table->string('utmf')->nullable();
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
        Schema::dropIfExists('track_records');
    }
}
