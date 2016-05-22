<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use App\Classes\DButil;


class UsersTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->delete();

        User::crear_administrador(array(
            'username' => 'admin',
            'email'    => 'root@dokify.com',
            'password' => 'admin',
        ));







    }

}


?>
