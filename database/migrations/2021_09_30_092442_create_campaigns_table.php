<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advertiser_id')->nullable();
            $table->foreign('advertiser_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->string('headline')->nullable();
            $table->string('other_description')->nullable();
            $table->string('campaign_name')->nullable();
            $table->string('campaign_type')->nullable();
            $table->float('campaign_budget')->nullable();
            $table->float('remaing_total')->nullable();   
            $table->float('pay_ppc')->nullable();
            $table->float('pay_daily')->nullable();   
            $table->float('remaing_daily')->nullable();   
            $table->string('landing_url')->nullable();
            $table->text('tracking_url')->nullable();
            $table->text('description')->nullable();            
            $table->string('banner_image')->nullable(); 
            $table->date('post_date')->nullable(); 
            $table->bigInteger('time')->nullable(); 
            $table->string('days')->nullable();
            $table->dateTime('form_date')->nullable();
            $table->dateTime('to_date')->nullable();
            $table->string('button_text')->nullable(); 
            $table->enum('admin_approval', ['0','1','2'])->nullable()->comment('0:Pendding,1:Approve,2:Declined'); 
            $table->enum('campaign_status', ['0','1','2'])->nullable()->comment('0:Pause,1:Running,2:Stop');
            $table->enum('created_by', ['admin','user'])->default('user');  
            $table->softDeletes();
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
        Schema::dropIfExists('campaigns');
    }
}
