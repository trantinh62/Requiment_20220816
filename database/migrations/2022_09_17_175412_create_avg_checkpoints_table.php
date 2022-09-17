<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avg_checkpoints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('checkpoint_id')->nullable();
            $table->foreign('checkpoint_id')->references('id')->on('checkpoints');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('avg_attitude')->nullable();
            $table->integer('avg_performance')->nullable();
            $table->integer('avg_teamwork')->nullable();
            $table->integer('avg_training')->nullable();
            $table->integer('avg_adhere')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avg_checkpoints');
    }
};
