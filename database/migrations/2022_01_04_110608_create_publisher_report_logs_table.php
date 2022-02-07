<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublisherReportLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publisher_report_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('publisher_report_id')->nullable();            
            $table->float('user_amount')->nullable();
            $table->float('admin_amount')->nullable();
            $table->integer('persentage')->nullable();
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
        Schema::dropIfExists('publisher_report_logs');
    }
}
