<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 *
 */
class EmpresasAceptanAcuerdos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas_aceptan_acuerdos', function (Blueprint $table) {


            $table->integer('empresa_id')->unsigned();
            $table->integer('acuerdo_id')->unsigned();

            $table->foreign('empresa_id')->references('id')->on('empresas')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(array('acuerdo_id' , 'empresa_id' ));

            $table->timestamps();
        });

        Schema::table('empresas_aceptan_acuerdos', function($table) {
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
        Schema::drop('empresas_aceptan_acuerdos');
    }
}
