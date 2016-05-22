<?php

use App\Models\User;
use App\Models\Empresa;

describe('test_user.php --> App\Models\User' , function(){
    describe("crear_administrador()" , function(){
        it("Debe poder crear un usuario administrador" , function(){
            $paco = User::crear_administrador( array('username' => 'Paco' , 'email' => 'paco@gmail.com' , 'password' => 'paco') );

            $user_paco = User::where('username' , 'paco')->first();

            expect($user_paco)->to->be->an('\App\Models\User');
            expect($user_paco->username)->to->be('Paco');

            $user_paco->delete();
        });
    });



    describe("crear_cuenta_empresa()" , function(){
        it("Debe poder crear una cuenta de empresa" , function(){

            $uber = User::crear_cuenta_empresa(array(
                'username'     => 'Tim',
                'email'        => 'root@uber.com',
                'password'     => 'taxi',
                'nombre'       => 'Uber',
                'sede_social'  => 'San Francisco',
                'nif'          => '999'
            ));

            $uber_user    = User::where('username' , 'Tim')->first();
            $uber_empresa = Empresa::where('nombre' , 'Uber')->first();

            expect($uber_user)->to->be->an('App\Models\User');
            expect($uber_user->email)->to->be('root@uber.com');

            expect($uber_empresa->nif)->to->be('999');

            $uber_user->delete();

        });

    });
});



?>
