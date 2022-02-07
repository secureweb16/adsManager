<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('type');
            $table->string('foruser')->nullable();
            $table->text('message');
            $table->text('admin_message')->nullable();
            $table->text('url')->nullable();
            $table->text('admin_url')->nullable();
            $table->enum('user_status', ['0', '1'])->default('0')->comment("0:Not Seen, 1:Seen");
            $table->enum('admin_status', ['0', '1'])->default('0')->comment("0:Not Seen, 1:Seen");
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
        Schema::dropIfExists('notifications');
    }
}
