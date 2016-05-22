<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcuerdosRelacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acuerdos_relaciones', function (Blueprint $table) {


            $table->integer('relacion_id')->unsigned();


            $table->integer('acuerdo_id')->unsigned();

            $table->foreign('relacion_id')->references('id')->on('relaciones')->onUpdate('cascade')->onDelete('cascade');


            $table->primary(array('relacion_id' , 'acuerdo_id'));

            $table->timestamps();
        });



        Schema::table('acuerdos_relaciones', function($table) {
           $table->foreign('acuerdo_id')->references('id')->on('acuerdos')->onUpdate('cascade')->onDelete('cascade');
           });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acuerdos_relaciones');
    }
}
