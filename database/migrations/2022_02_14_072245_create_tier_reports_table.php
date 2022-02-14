<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTierReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('tier_reports', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('tier_id')->nullable();
        $table->foreign('tier_id')->references('id')->on('tiers')->onDelete('CASCADE');
        $table->bigInteger('campaign_id')->nullable();
        $table->bigInteger('publisher_id')->nullable();
        $table->string('group_id')->nullable();
        $table->integer('no_of_publish')->nullable();
        $table->integer('no_of_clicks')->nullable();
        $table->float('user_amount')->nullable();
        $table->float('admin_amount')->nullable();
        $table->float('total_amount')->nullable();
        $table->enum('status', ['0','1'])->nullable()->default('0')->comment('0:NotGenerated,1:Generated');
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
      Schema::dropIfExists('tier_reports');
    }
  }
