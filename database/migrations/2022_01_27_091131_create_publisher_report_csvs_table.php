<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublisherReportCsvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publisher_report_csvs', function (Blueprint $table) {
            $table->id();
            $table->string('csv_name')->nullable();
            $table->string('downloade')->nullable();
            $table->enum('mark_paid', ['0','1'])->nullable()->default('0')->comment('0:NotPaid,1:Paid');
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
        Schema::dropIfExists('publisher_report_csvs');
    }
}
