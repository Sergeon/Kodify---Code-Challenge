<?php

use App\Models\User;
use App\Models\Empresa;
use App\Models\Role;



describe("test_permisos.php" , function(){

    describe("Administradores del sistema" , function($context){

        before(function($context){

            $context->admin = User::where('username' , 'admin')->first();

            if( ! ($context->admin) )
                throw new \Exception("Hay que seedear la db con datos maestros y de prueba antes de poder ejecutar los tests", 1);
        });

        it("Deben tener el rol Admin" , function( $context ){
            expect($context->admin->hasRole('admin'))->to->be(true);
        });

        it("No deben tener rol de empresa" , function($context){
            expect($context->admin->hasRole('empresa'))->to->be(false) ;
        });

        it("Deben tener permiso para crear empresas" , function($context){
            expect($context->admin->can('crear_empresas'))->to->be(true) ;
        });

        it("Deben tener permiso para crear administradores" , function($context){
            expect($context->admin->can('crear_administradores'))->to->be(true) ;
        });

        it("Deben poder crear relaciones" , function($context){
            expect($context->admin->can('crear_relaciones'))->to->be(true) ;
        });


        it("Deben poder crear acuerdos" , function($context){
            expect($context->admin->can('crear_acuerdos'))->to->be(true) ;
        });

    });


    describe("cuentas de empresa del sistema" , function(){


        before(function($context){
            $pescanova = Empresa::where('nombre' , 'Pescanova')->first()->user;
            $context->pescanova = $pescanova;
        });

        it("Deben estar asociadas a una empresa" , function( $context ){
            expect($context->pescanova)->to->be->a('App\Models\User');
        });

        it("Deben tener el rol de empresa" , function($context){
            expect($context->pescanova->hasRole('empresa'))->to->be(true);
        } );

        it("No Deben poder crear acuerdos" , function($context){
            expect($context->pescanova->can('crear_acuerdos'))->to->be(false);
        });

        it("Deben poder listar su propia información" , function($context){
            expect($context->pescanova->can('listar_info_simple'))->to->be(true);
        });

        it("No deben poder listar toda la informacion del sistema" , function($context){
            expect($context->pescanova->can('listar_info_sistema'))->to->be(false);
        });


    });


} );

?>
