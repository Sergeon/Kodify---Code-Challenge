<?php namespace App\Tests\Matura\Util;

use App\Classes\DButil;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Relacion;


class Db_Test_seeder {

    public function populate(){

        $this->clean();

        $pokemon = Empresa::where('nombre' , 'Pokemon')->first();

        //Solo seedeamos si no se ha seedeado ya la base de datos.
        if(! $pokemon){
            $this->poblar_empresas();
            $this->poblar_relaciones();
        }

    }

    public function clean(){

        \DB::table('relaciones')->delete();
        User::where('username' , '!=' , 'admin')->delete();

    }


    public function poblar_empresas(){



        User::crear_cuenta_empresa(

            array('username' => 'gotham',
                'email'    => 'root@gothamcityresearch.com',
                'password' =>'wifito' ,
                'nombre' => 'Gotham City Research',
                'sede_social'    => 'Gotham City',
                'nif' => '333666999',
                )
        );



        User::crear_cuenta_empresa( array(
            'username' => 'satoshi',
            'email'    => 'satoshin@gmx.com',
            'password' => 'hashcash',
            'nombre' => 'Bitcoin ORG',
            'sede_social'    => 'Bizantium',
            'nif' => '00000000839',
        ));

        User::crear_cuenta_empresa(array(
            'username' => 'capitanpescanova',
            'email'    => 'root@pescanova.com',
            'password' => 'calamares',
            'nombre' => 'Pescanova',
            'sede_social'    => 'Vigo',
            'nif' => '000001',
        ));

        User::crear_cuenta_empresa( array(

            'username'    => 'Ash',
            'email'       => 'Satoshiijata@nintendo.com',
            'password'    => 'pikachu',
            'nombre' => 'Pokemon',
            'sede_social'    => 'Osaka',
            'nif' => '222445',
        ));


        User::crear_cuenta_empresa(

            array('username' => 'gotham2',
                'email'    => 'root2@gothamcityresearch.com',
                'password' =>'wifito' ,
                'nombre' => 'Gotham City Research2',
                'sede_social'    => 'Gotham City',
                'nif' => '3336669991231',
                )
        );



        User::crear_cuenta_empresa( array(
            'username' => 'satoshi2',
            'email'    => 'satoshin2@gmx.com',
            'password' => 'hashcash',
            'nombre' => 'Bitcoin ORG2',
            'sede_social'    => 'Bizantium',
            'nif' => '000000008394',
        ));

        User::crear_cuenta_empresa(array(
            'username' => 'capitanpescanova2',
            'email'    => 'root2@pescanova.com',
            'password' => 'calamares',
            'nombre' => 'Pescanova2',
            'sede_social'    => 'Vigo',
            'nif' => '0000016',
        ));

        User::crear_cuenta_empresa( array(

            'username'    => 'Ash2',
            'email'       => 'Satoshiijata2@nintendo.com',
            'password'    => 'pikachu',
            'nombre' => 'Pokemon2',
            'sede_social'    => 'Osaka',
            'nif' => '222445456',
        ));





        User::crear_cuenta_empresa(

            array('username' => 'gotham3',
                'email'    => 'root3@gothamcityresearch.com',
                'password' =>'wifito' ,
                'nombre' => 'Gotham City Research3',
                'sede_social'    => 'Gotham City',
                'nif' => '3336999',
                )
        );



        User::crear_cuenta_empresa( array(
            'username' => 'satoshi3',
            'email'    => 'satoshin3@gmx.com',
            'password' => 'hashcash',
            'nombre' => 'Bitcoin ORG3',
            'sede_social'    => 'Bizantium',
            'nif' => '000000839',
        ));

        User::crear_cuenta_empresa(array(
            'username' => 'capitanpescanova3',
            'email'    => 'root3@pescanova.com',
            'password' => 'calamares',
            'nombre' => 'Pescanova3',
            'sede_social'    => 'Vigo',
            'nif' => '00001',
        ));

        User::crear_cuenta_empresa( array(

            'username'    => 'Ash33',
            'email'       => 'Satoshiijata3@nintendo.com',
            'password'    => 'pikachu',
            'nombre' => 'Pokemon3',
            'sede_social'    => 'Osaka',
            'nif' => '22845',
        ));


        User::crear_cuenta_empresa(

            array('username' => 'gotham4',
                'email'    => 'root4@gothamcityresearch.com',
                'password' =>'wifito' ,
                'nombre' => 'Gotham City Research4',
                'sede_social'    => 'Gotham City',
                'nif' => '3389766999',
                )
        );



        User::crear_cuenta_empresa( array(
            'username' => 'satoshi4',
            'email'    => 'satoshin4@gmx.com',
            'password' => 'hashcash',
            'nombre' => 'Bitcoin ORG4',
            'sede_social'    => 'Bizantium',
            'nif' => '00011000839',
        ));

        User::crear_cuenta_empresa(array(
            'username' => 'capitanpescanova4',
            'email'    => 'root4@pescanova.com',
            'password' => 'calamares',
            'nombre' => 'Pescanova4',
            'sede_social'    => 'Vigo',
            'nif' => '000111',
        ));

        User::crear_cuenta_empresa( array(

            'username'    => 'Ash4',
            'email'       => 'Satoshiijata4@nintendo.com',
            'password'    => 'pikachu',
            'nombre' => 'Pokemon4',
            'sede_social'    => 'Osaka',
            'nif' => '254445',
        ));


    }


    public function poblar_relaciones(){



        $gotham = Empresa::where('nombre' , 'Gotham City Research')->first();
        $bitcoin = Empresa::where('nombre' , 'Bitcoin ORG')->first();
        $pescanova = Empresa::where('nombre' , 'Pescanova')->first();
        $pokemon = Empresa::where('nombre' , 'Pokemon')->first();

        $bitcoin->add_cliente($gotham);
        $bitcoin->add_cliente($pescanova);
        $bitcoin->add_cliente($pokemon);


        $pescanova->add_cliente($gotham);
        $pescanova->add_cliente($bitcoin);
        $pescanova->add_cliente($pokemon);

        $gotham->add_cliente($pokemon);

    }



}







?>
