<?php



use App\Models\Role;
use App\Models\User;

describe("test_auth.php" , function(){

    describe("metodos básicos de Auth" , function(){

        describe("attemp() " , function(){

            it("debe permitir hacer login con una cuenta de administrador" , function(){
                Auth::attempt( array( 'username' => 'admin' , 'password' => 'admin') );
                expect(Auth::check() )->to->be(true);
            });

            it("debe permitir hacer login con una cuenta de empresa" , function(){
                Auth::attempt( array( 'username' => 'satoshi' , 'password' => 'hashcash') );
                $username = Auth::user()->username;
                expect($username )->to->be('satoshi');
                Auth::logout();
            });

        });


        describe("user()" , function(){

            it("debe poder devolver un usuario administrador" , function(){
                Auth::attempt( array( 'username' => 'admin' , 'password' => 'admin') );
                expect(Auth::user()->username )->to->be('admin');
            });

            it("debe poder devolver una cuenta de empresa" , function(){
                Auth::attempt( array( 'username' => 'gotham' , 'password' => 'wifito') );
                expect(Auth::user()->empresa->nombre )->to->be('Gotham City Research');
            });

        });


        describe("check()" , function(){

            it("Debe reconocer cuando no hay un usuario autenticado " , function(){
                Auth::logout();
                expect(Auth::check() )->to->be(false);
            });

            it("debereconocer cuando hay un usuario autenticado" , function(){
                Auth::attempt(array( 'username' => 'admin' , 'password' => 'admin') );
                expect(Auth::check() )->to->be(true);
            });
        });


        describe("logout()" , function(){

            it("debe desconectar a un usuario" , function(){
                Auth::attempt( array( 'username' => 'gotham' , 'password' => 'wifito') );
                Auth::logout();
                expect(Auth::check() )->to->be->false;
            });

        });
    });
});





?>
