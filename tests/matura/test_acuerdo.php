<?php


use App\Models\Relacion;
use App\Models\Empresa;
use App\Models\Acuerdo;


describe("test_acuerdo.php" , function(){
    describe("crear_acuerdo()" , function(){

        it("Debe crear acuerdos bien formados, a partir de solo dos empresas" , function($context){

            $data = array( 'nombre' => 'pureba1' , 'descripcion' => 'acuerdo simple' );

            $acuerdo = Acuerdo::crear_acuerdo(  $data , array( $context->gotham->id , $context->pokemon->id ) );

            $rel = $acuerdo->relaciones()->get()->first();
            $cliente =  Empresa::find( $rel->cliente_id );
            $proveedor = Empresa::find( $rel->proveedor_id);
            $acuerdo->delete();
            expect( $cliente->id )->to->be( $context->pokemon->id);
        });


        it("Debe crear acuerdos bien formados, a partir de varias empresas" , function($context){
            $data = array( 'nombre' => 'pureba2' , 'descripcion' => 'acuerdo simple' );
            $acuerdo = Acuerdo::crear_acuerdo(  $data , array( $context->pescanova->id, $context->bitcoin->id, $context->gotham->id ) );
            $arr = $acuerdo->relaciones()->get()->toArray();

            $acuerdo->delete();
            expect( $arr[0]['cliente_id'] )->to->be($context->gotham->id);
            expect( $arr[0]['proveedor_id'] )->to->be($context->bitcoin->id);
            expect($arr[1]['cliente_id'])->to->be($context->bitcoin->id);
            expect($arr[1]['proveedor_id'])->to->be($context->pescanova->id);
            expect(isset($arr[2]))->to->be(false);

        });

        //TODO
        //probar que no crea acuerdos imposibles.
    });


    describe("get_empresas()" , function(){

        it("Debe devolver exactamente y solo las empresas que están en el acuerdo, por defecto en orden cliente -> proveedor" , function($context){
            $data = array( 'nombre' => 'prueba3' , 'descripcion' => 'acuerdo simple' );
            $acuerdo = Acuerdo::crear_acuerdo(  $data , array( $context->pescanova->id, $context->bitcoin->id , $context->gotham->id ) );

            $empresas = $acuerdo->get_empresas();

            $acuerdo->delete();

            expect( $empresas[0]->id)->to->be($context->gotham->id);
            expect( $empresas[1]->id)->to->be($context->bitcoin->id);
            expect( $empresas[2]->id)->to->be($context->pescanova->id);
        });


        it("Debe poder cambiar el orden de devolución" , function($context){

            $data = array( 'nombre' => 'prueba3' , 'descripcion' => 'acuerdo simple' );
            $acuerdo = Acuerdo::crear_acuerdo(  $data , array( $context->pescanova->id, $context->bitcoin->id, $context->gotham->id ) );

            $empresas = $acuerdo->get_empresas($acuerdo , 1 );

            $acuerdo->delete();

            expect( $empresas[0]->id)->to->be($context->pescanova->id);
            expect( $empresas[1]->id)->to->be($context->bitcoin->id);
            expect( $empresas[2]->id)->to->be($context->gotham->id);
        });


        it("Debe funcionar bien con acuerdos de solo dos empresas" , function($context){

            $data = array( 'nombre' => 'prueba3' , 'descripcion' => 'acuerdo simple' );
            $acuerdo = Acuerdo::crear_acuerdo(  $data , array( $context->pescanova->id, $context->bitcoin->id) );

            $empresas = $acuerdo->get_empresas( );
            $empresas_order = $acuerdo->get_empresas($acuerdo , 1 );

            $acuerdo->delete();

            expect($empresas[0]->id)->to->be($context->bitcoin->id);
            expect($empresas[1]->id)->to->be($context->pescanova->id);

            expect( $empresas_order[0]->id)->to->be($context->pescanova->id);
            expect( $empresas_order[1]->id)->to->be($context->bitcoin->id);


        });

    });

});


?>
