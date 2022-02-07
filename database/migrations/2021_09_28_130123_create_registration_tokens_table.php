<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registration_tokens', function (Blueprint $table) {
            $table->id();            
            
            $table->unsignedBigInteger('user_id')->nullable();          
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            
            $table->bigInteger('token');
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('registration_tokens');
    }
}
