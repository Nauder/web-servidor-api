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
        Schema::create('veiculo', function (Blueprint $table) {
            $table->id();
            $table->string('placa');
            $table->string('modelo');
            $table->string('marca');
            $table->string('cor');
            $table->year('ano');
            $table->unsignedInteger('quilometragem');
            $table->unsignedInteger('custo_dia');
            $table->unsignedBigInteger('id_empresa');
            $table->timestamps();

            $table->foreign('id_empresa')->references('id')->on('empresa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veiculo');
    }
};
