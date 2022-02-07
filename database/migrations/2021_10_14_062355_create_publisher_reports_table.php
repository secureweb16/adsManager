<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublisherReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('publisher_reports', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('publisher_id')->nullable();
        $table->foreign('publisher_id')->references('id')->on('users')->onDelete('CASCADE');
        $table->unsignedBigInteger('campaign_id')->nullable();        
        $table->integer('no_of_publish')->nullable();
        $table->integer('no_of_clicks')->nullable();
        $table->integer('old_clicks')->nullable();
        $table->float('user_amount')->nullable();
        $table->float('admin_amount')->nullable();
        $table->float('paid_amount')->nullable();
        $table->float('payable_amount')->nullable();
        $table->float('total_amount')->nullable();
        $table->string('group_id')->nullable();
        $table->date('paid_date')->nullable();
        $table->date('cron_hit_date')->nullable();
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
      Schema::dropIfExists('publisher_reports');
    }
  }
