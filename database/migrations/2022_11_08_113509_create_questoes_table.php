<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('questoes', function (Blueprint $table) {
                $table->id();
                $table->longText('descricao');
                $table->longText('ra');
                $table->longText('rb');
                $table->longText('rc');
                $table->longText('rd');
                $table->longText('re');
                $table->char('rgabarito');
                $table->longText('comentario');
                $table->enum('nivel', ['Fácil','Médio', 'Díficil']);
                $table->enum('status',  ['Ativo', 'Inativo']);
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
        Schema::dropIfExists('questoes');
    }
}
