<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('found', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uploadSchool');
            $table->string('foundTitle');
            $table->date('lostTime')->default(date("Y-m-d"));
            $table->string('lostPlace')->nullable(true);
            $table->string('detail')->nullable(true);
            $table->string('QQ')->nullable(true);
            $table->string('getPlace')->nullable(true);
            $table->string('ImgPath')->nullable(true);
            $table->string('Phone')->nullable(true);
            $table->string('stuNumber');
            $table->string('Code');

            $table->boolean('delete')->default(false);
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
        Schema::dropIfExists('found');
    }
}
