<?php

use App\Models\Empresa;

use App\Models\User;
use App\Models\Relacion;
use App\Models\Role;
use App\Models\Acuerdo;



describe('test_empresa.php' , function($context){

    describe("clientes() y proveedores() de empresas" , function($context){

        it("Cualquier empresa deberia ser cliente de Bitcoin" , function($context ){
            expect($context->gotham->proveedores()->where('nombre' , 'Bitcoin ORG')->first() )->to->be->a('App\Models\Empresa');
            expect($context->pokemon->proveedores()->where('nombre' , 'Bitcoin ORG')->first() )->to->be->a('App\Models\Empresa');
            expect($context->pescanova->proveedores()->where('nombre' , 'Bitcoin ORG')->first() )->to->be->a('App\Models\Empresa');
        });

        it("cualquier empresa deberia ser cliente de Pescanova" , function($context){
            expect($context->pokemon->proveedores()->where('nombre' , 'Pescanova')->first() )->to->be->a('App\Models\Empresa');
            expect($context->bitcoin->proveedores()->where('nombre' , 'Pescanova')->first() )->to->be->a('App\Models\Empresa');
        });

        it("Pokemon debería ser cliente de Gotham" , function($context){
            expect( $context->gotham->clientes()->where('nombre' , 'Pokemon') )->to->not->be(null);
        });

    });

    describe("getColumns()" , function(){

        it("Debe devolver correctamente las claves de los modelos" , function(){
            expect(Empresa::getColumns()  )->to->be(array(  'nombre' , 'sede_social' , 'nif' ) );
        });
    });


    describe("get_non_clientes() " , function(){
        it("debe devolver todas las empresas que no son clientes de " . '$this' , function(){
            $pokemon = Empresa::where('nombre' , 'Pokemon')->first();

            //esta es la lista de los potenciales clientes de Pokemon -que son todo el set menos Pokemon-.
            $raw_potenciales_clientes = DB::select("select * from empresas where id not in (select proveedor_id  from relaciones where proveedor_id = $pokemon->id ) and  id != $pokemon->id;");
            $pokemon_potenciales_clientes = $pokemon->get_non_clientes()->toArray();

            $raw_sane = array();
            $orm_sane = array();

            foreach($raw_potenciales_clientes as $elem)
                $raw_sane[] = json_decode(json_encode($elem), true);  //http://stackoverflow.com/questions/18576762/php-stdclass-to-array

            foreach($pokemon_potenciales_clientes as $elem)
                $orm_sane[] = $elem;

            expect($orm_sane )->to->be($raw_sane);
        });
    });


    describe("get_non_proveedores()" , function(){
        it("debe devolver todas las empresas que no son proveedores de " . '$this' , function(){
            $pescanova = Empresa::where('nombre' , 'Pescanova')->first();

            $raw_potenciales_proveedores = DB::select("select * from empresas where id not in (select proveedor_id  from relaciones where cliente_id = $pescanova->id ) and  id != $pescanova->id;");

            $pescanova_potenciales_proveedores = $pescanova->get_non_proveedores()->toArray();

            $raw_sane = array();

            $orm_sane = array();

            foreach($raw_potenciales_proveedores as $elem)
                $raw_sane[] = json_decode(json_encode($elem), true);

            foreach($pescanova_potenciales_proveedores as $elem)
                $orm_sane[] = $elem;


            expect($orm_sane )->to->be($raw_sane);
        });
    });


    describe("es_proveedor_de()" , function(){

        it("Debe reconocer si una empresa dada es su cliente" , function($context){
            expect($context->gotham->es_proveedor_de($context->pokemon) )->to->be(true);
        });

        it("Debe no reconocer empresas que no sean su cliente" , function($context){
            expect($context->gotham->es_proveedor_de($context->bitcoin) )->to->be(false);
        });


    });

    describe("es_cliente_de()" , function(){
        it("Debe reconocer si una empresa dada es su proveedor" , function($context){
            expect($context->pokemon->es_cliente_de($context->gotham) )->to->be(true);
        });

        it("Debe no reconocer empresas que no sean su proveedor" , function($context){
            expect($context->gotham->es_cliente_de($context->pokemon) )->to->be(false);
        });

    });



    describe("add_cliente()" , function(){
        it("debe poder añadir clientes a una empresa" , function( $context){

            $context->pokemon->add_cliente($context->bitcoin);

            expect($context->bitcoin->es_cliente_de($context->pokemon))->to->be(true);

            $context->pokemon->borrar_cliente($context->bitcoin);

            expect($context->bitcoin->es_cliente_de($context->pokemon))->to->be(false);
        });
    });

            describe("borrar_proveedor() " , function(){

                it("debe borrar correctamente una relacion cliente-proveedor" , function( $context ){


                    $context->gotham->add_proveedor($context->pokemon);
                    $context->gotham->borrar_proveedor($context->pokemon);

                    expect( $context->gotham->es_cliente_de($context->pokemon) )->to->be(false);

                });
            });


            describe("borrar_cliente() ", function(){

                it("debe borrar correctamente una relacion proveedor-cliente" , function($context){

                    $context->gotham->add_cliente($context->bitcoin);
                    $context->gotham->borrar_cliente($context->bitcoin);

                    expect( $context->gotham->es_proveedor_de($context->bitcoin) )->to->be(false);

                });
            });






    describe("get_solo_proveedores()" , function(){

        it("debe devolver todas las empresas que sean solamente proveedores" , function( $context ){
            //Nota: este test va a fallar si en algún test anterior creas nuevas empresas o cambias relaciones
            //entre empresas y luego no borras los cambios.
            $provs = Empresa::get_solo_proveedores();

            expect( $provs->has($context->gotham->id)  )->to->be->true;
            expect( $provs->has($context->pescanova->id)  )->to->be->true;
            expect( $provs->has($context->bitcoin->id)  )->to->be->true;
            expect( $provs->has($context->pokemon->id)  )->to->be->false;
        });

    });



    describe( "aceptar_acuerdo()" , function($context){

        it("Debe acepta un acuerdo de tres empresas" , function( $context){


            $data = array( 'nombre' => 'pruba_empresa1' , 'descripcion' => 'acuerdo  simple' );


            $acuerdo = Acuerdo::crear_acuerdo(  $data , array( $context->bitcoin->id, $context->gotham->id , $context->pokemon->id  ) );


            $context->gotham->aceptar_acuerdo($acuerdo);

            $id = $context->gotham->id;

            $raw = \DB::select("select * from empresas_aceptan_acuerdos where empresa_id = $id and acuerdo_id = $acuerdo->id");

            $acuerdo->delete();

            expect(count($raw))->to->be(1);
            expect($raw[0]->empresa_id)->to->be($context->gotham->id);
        });
    });

    describe("acuerdo_es_aceptado() " , function(){

        it("Debe reconocer un acuerdo que ha sido aceptado por la empresa" , function( $context ){

            $data = array( 'nombre' => 'prueba_empresa2' , 'descripcion' => 'acuerdo  simple' );
            $acuerdo = Acuerdo::crear_acuerdo(  $data , array( $context->bitcoin->id, $context->gotham->id , $context->pokemon->id ) );

            $empresa= $context->bitcoin;

            $empresa->aceptar_acuerdo($acuerdo);

            try{
                expect($empresa->acuerdo_es_aceptado($acuerdo))->to->be(true);
            }
            catch(\Exception $ex){
                $acuerdo->delete();
            }
            $acuerdo->delete();

        });

        it("Debe negar relaciones con acuerdos que no tienen que ver con ella" , function( $context ){
            $data = array( 'nombre' => 'prueba_empresa3' , 'descripcion' => 'acuerdo  simple' );

            $acuerdo = Acuerdo::crear_acuerdo(  $data , array( $context->pescanova->id,  $context->pokemon->id  ) );

            $empresa= $context->bitcoin;

            $empresa->aceptar_acuerdo($acuerdo);
            try{
                expect($empresa->acuerdo_es_aceptado($acuerdo))->to->be(false);
            }
            catch(\Exception $ex){
                $acuerdo->delete();
            }
            $acuerdo->delete();

        });

    });

});
