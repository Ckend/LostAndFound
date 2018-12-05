<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lost', function (Blueprint $table) {
            $table->increments('id');
            $table->string('school')->nullable(true);
            $table->string('stuName')->nullable(true);
            $table->string('stuNumber')->nullable(true);
            $table->string('academy')->nullable(true);
            $table->string('lostType')->nullable(true);
            $table->string('pickLocation')->nullable(true);
            $table->date('pickTime')->default(date("Y-m-d"));
            $table->string('getPlace')->nullable(true);
            $table->boolean('taken')->default(false);
            $table->boolean('isCard')->default(true);
            $table->string('ImgPath')->nullable(true);
            $table->string('takenName')->nullable(true);
            $table->string('takenAcademy')->nullable(true);
            $table->string('takenNumber')->nullable(true);
            $table->date('takenTime')->nullable(true);
            $table->date('showTime')->default(date("Y-m-d"));
            $table->string('takenPhone')->nullable(true);
            $table->boolean('delete')->default(false);
            $table->string('remarks')->nullable(true);
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
        Schema::dropIfExists('_lost');
    }
}
