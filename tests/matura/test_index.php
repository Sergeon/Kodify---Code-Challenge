<?php namespace Tests\Matura;

require 'util/db_test_seeder.php';

use App\Tests\Matura\Util\Db_Test_Seeder;
use App\Models\Empresa;

$app = require __DIR__.'/../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();


describe("La aplicación de prueba para dokify" , function( $context ){


    before(function($context){

        $context->seeder = new Db_Test_seeder();

        $context->seeder->populate();

        $context->gotham = Empresa::where('nombre' , 'Gotham City Research')->first();
        $context->bitcoin = Empresa::where('nombre' , 'Bitcoin ORG')->first();
        $context->pescanova = Empresa::where('nombre' , 'Pescanova')->first();
        $context->pokemon = Empresa::where('nombre' , 'Pokemon')->first();

    });

    after(function($context){
        //$context->seeder->clean();
    });


    require('test_user.php');
    require('test_auth.php');
    require('test_get_paths.php');
    require('test_permisos.php');
    require('test_empresa.php');
    require('test_acuerdo.php');

});






?>
