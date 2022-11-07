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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->index();
            $table->integer('run')->default(0)->index();
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('generation_id');
            $table->unsignedBigInteger('color_id');
            $table->integer('external_id');

            $table->index([
                'year',
                'run',
                'model_id',
                'generation_id',
                'color_id',
            ]);

            $table->foreign('model_id')
                ->references('id')
                ->on('models')
                ->onDelete('cascade');

            $table->foreign('generation_id')
                ->references('id')
                ->on('generations')
                ->onDelete('cascade');

            $table->foreign('color_id')
                ->references('id')
                ->on('colors')
                ->onDelete('cascade');

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
        Schema::dropIfExists('cars');
    }
};
