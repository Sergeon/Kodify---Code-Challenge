<?php

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Relacion;


class RelacionesTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('relaciones')->delete();

        $gotham = Empresa::where('nombre' , 'Gotham City Research')->first();
        $bitcoin = Empresa::where('nombre' , 'Bitcoin ORG')->first();
        $pescanova = Empresa::where('nombre' , 'Pescanova')->first();
        $pokemon = Empresa::where('nombre' , 'Pokemon')->first();

        $gotham2 = Empresa::where('nombre' , 'Gotham City Research2')->first();
        $bitcoin2 = Empresa::where('nombre' , 'Gotham City Research2')->first();
        $pescanova2 = Empresa::where('nombre' , 'Pescanova2')->first();
        $pokemon2 = Empresa::where('nombre' , 'Pokemon2')->first();


        $gotham3 = Empresa::where('nombre' , 'Gotham City Research3')->first();
        $bitcoin3 = Empresa::where('nombre' , 'Gotham City Research3')->first();
        $pescanova3 = Empresa::where('nombre' , 'Pescanova3')->first();
        $pokemon3 = Empresa::where('nombre' , 'Pokemon3')->first();

        $bitcoin->add_cliente($gotham);
        $bitcoin->add_cliente($pescanova);
        $bitcoin->add_cliente($pokemon);

        $bitcoin->add_cliente($gotham2);
        $bitcoin->add_cliente($pescanova2);
        $bitcoin->add_cliente($pokemon2);

        $bitcoin->add_cliente($gotham3);
        $bitcoin->add_cliente($pescanova3);
        $bitcoin->add_cliente($pokemon3);        


        $pescanova->add_cliente($gotham);
        $pescanova->add_cliente($bitcoin);
        $pescanova->add_cliente($pokemon);

        $gotham->add_cliente($pokemon);


    }

}


?>
