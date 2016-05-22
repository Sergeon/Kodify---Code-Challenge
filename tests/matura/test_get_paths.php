<?php

require('tests/TestCase.php');

use App\Models\Role;
use App\Models\User;


describe("test_get_paths.php" , function(){
    describe("La autenticación en el sistema" , function( ){
        describe("Acceso a las rutas de la app por get:" , function($context){

            before(function($context){

                $context->creator = new TestCase();
                $context->creator->createApplication();
            });


            it("/Empresa debe devolver 302 sin autentificacion" , function($context){

                $response = $context->creator->call('GET', '/empresa');
                expect($response->status() )->to->be(302);


            });

            it("/login debe devolver un 200 siempre" , function( $context){
                $response = $context->creator->call('GET', '/login');
                expect($response->status() )->to->be(200);
            });


            it("/empresa debe ser accesible por una cuenta de empresa" , function($context){
                \Auth::attempt( array( 'username' => 'satoshi' , 'password' => 'hashcash') );
                $response = $context->creator->call('GET', '/empresa');
                expect($response->status() )->to->be(200);
            });
        });
    });
});
