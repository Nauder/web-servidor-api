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
        Schema::create('emprestimo', function (Blueprint $table) {
            $table->id();
            $table->date('data_emprestimo');
            $table->date('data_entrega');
            $table->unsignedBigInteger('id_veiculo');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_empresa_emprestimo');
            $table->unsignedBigInteger('id_empresa_entrega');
            $table->timestamps();

            $table->foreign('id_veiculo')->references('id')->on('veiculo')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id')->on('usuario')->onDelete('cascade');
            $table->foreign('id_empresa_emprestimo')->references('id')->on('empresa')->onDelete('cascade');
            $table->foreign('id_empresa_entrega')->references('id')->on('empresa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emprestimo');
    }
};
