<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relaciones', function (Blueprint $table) {



            $table->increments('id');

            $table->integer('cliente_id')->unsigned()->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('proveedor_id')->unsigned()->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');


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
        Schema::drop('relaciones');
    }
}
