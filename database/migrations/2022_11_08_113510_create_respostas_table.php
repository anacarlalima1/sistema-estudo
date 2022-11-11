<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespostasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('respostas', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_user')->unsigned();
                $table->integer('id_questao')->unsigned();
                $table->string('resultado');
                $table->char('alternativa');
                $table->char('gabarito');
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
        Schema::dropIfExists('respostas');
    }
}
