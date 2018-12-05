<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->increments('id');
            $table->string('school_name');
            $table->boolean('sync_weibo')->default(0);
            $table->string('weibo_user')->default('');
            $table->string('weibo_password')->default('');
            $table->string('weibo_token')->default('');

            $table->boolean('autoInform_qiyehao')->default(0);
            $table->string('SERVER_ADDR')->default('');
            $table->string('CLIENT_ID')->default('');
            $table->string('CLIENT_SECRET')->default('');

            $table->boolean('lost')->default(1);
            $table->boolean('found')->default(0);

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
        Schema::dropIfExists('admin');
    }
}
