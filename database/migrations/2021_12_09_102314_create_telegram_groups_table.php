<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('publisher_id')->nullable();
            $table->foreign('publisher_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->string('telegram_group')->nullable();
            $table->integer('no_of_published')->nullable();
            $table->integer('no_of_clicks')->nullable();
            $table->integer('frequency_of_ads')->nullable();
            $table->string('frequency_type')->nullable();
            $table->string('default_group')->nullable();            
            $table->string('hours_type')->nullable();
            $table->text('days')->nullable();
            $table->text('from_time')->nullable();
            $table->text('to_time')->nullable();
            $table->dateTime('from_all_days')->nullable();
            $table->dateTime('to_all_days')->nullable();
            $table->enum('status', ['0','1'])->nullable()->default('1')->comment('0:InActive,1:Active');
            $table->enum('admin_status', ['0','1'])->nullable()->default('1')->comment('0:InActive,1:Active');
            $table->enum('verify', ['0','1'])->nullable()->default('0')->comment('0:InActive,1:Active');
            $table->enum('user_delete', ['0','1'])->nullable()->default('0')->comment('0:NotDelete,1:Delete');
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
        Schema::dropIfExists('telegram_groups');
    }
}
