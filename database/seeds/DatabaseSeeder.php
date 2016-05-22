<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //estas son los únicos datos maestros por el momento
        $this->call(EntrustTableSeeder::class);

        //además el sistema necesita un primer usuario administrador
        //(por otro lado, tal vez sería más lógico forzar su creación si al iniciar la app no hay
        //ningun administrador en la base de datos)
        $this->call(UsersTableSeeder::class);


        //NOTA: el seeder de tests/matura/ se carga estos datos y carga otros.
        //Hay que ejecutar php artisan db:seed para volver a cargar estos datos en la DB depués de
        //ejecutar los tests. 
        $this->call(EmpresasTableSeeder::class);
        $this->call(RelacionesTableSeeder::class);
    }
}
