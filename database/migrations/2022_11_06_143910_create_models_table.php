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
        Schema::create('models', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->unsignedBigInteger('mark_id');
            $table->unsignedBigInteger('body_id');
            $table->unsignedBigInteger('engine_id');
            $table->unsignedBigInteger('transmission_id');
            $table->unsignedBigInteger('gear_id');

            $table->index([
                'name',
                'mark_id',
                'body_id',
                'engine_id',
                'transmission_id',
                'gear_id',
            ]);

            $table->foreign('mark_id')
                ->references('id')
                ->on('marks')
                ->onDelete('cascade');

            $table->foreign('body_id')
                ->references('id')
                ->on('bodies')
                ->onDelete('cascade');

            $table->foreign('engine_id')
                ->references('id')
                ->on('engines')
                ->onDelete('cascade');

            $table->foreign('transmission_id')
                ->references('id')
                ->on('transmissions')
                ->onDelete('cascade');

            $table->foreign('gear_id')
                ->references('id')
                ->on('gears')
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
        Schema::dropIfExists('models');
    }
};
