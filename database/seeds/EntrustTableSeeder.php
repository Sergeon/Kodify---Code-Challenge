<?php

use Illuminate\Database\Seeder;

use App\Models\Permission;
use App\Models\Role;


class EntrustTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('permissions')->delete();
        DB::table('roles')->delete();
        DB::table('permission_role')->delete();
        DB::table('role_user')->delete();


        $crear_empresas = $this->crear_crear_empresas_permission();
        $crear_usuarios = $this->crear_crear_usuarios_permission();
        $crear_administradores = $this->crear_crear_administradores_permission();
        $listar_info_sistema = $this->crear_listar_info_sistema_permission();
        $listar_info_simple = $this->crear_listar_info_simple_permission();
        $crear_acuerdos = $this->crear_crear_acuerdos_permission();
        $aceptar_acuerdos = $this->crear_aceptar_acuerdos_permission();
        $crear_relaciones = $this->crear_crear_relaciones_permission();


        $admin = $this->crear_admin_role();
        $empresa = $this->crear_empresa_role();


        $admin->attachPermissions(array($crear_empresas , $crear_usuarios , $crear_administradores , $listar_info_sistema , $crear_relaciones , $crear_acuerdos ));
        $empresa->attachPermissions(array( $listar_info_simple  ,  $aceptar_acuerdos ));



    }



    private function crear_crear_empresas_permission(){

        $crear_empresas = new Permission();
        $crear_empresas->name = 'crear_empresas';
        $crear_empresas->display_name = 'Crear nuevas empresas';
        $crear_empresas->description = 'Crear nuevas empresas asociadas a un nuevo usuario.';

        $crear_empresas->save();

        return $crear_empresas;
    }


    private function crear_crear_administradores_permission(){

        $crear_administradores = new Permission();
        $crear_administradores->name = 'crear_administradores';
        $crear_administradores->display_name = 'Crear administradores del sistema';
        $crear_administradores->description = 'Crear nuevos usuarios administradores que pueden listar toda la información del sistema. Los usuarios administradores no están asociados a ninguna empresa.';

        $crear_administradores->save();

        return $crear_administradores;
    }


    private function crear_crear_usuarios_permission(){

        $crear_usuarios = new Permission();

        $crear_usuarios->name = 'crear_usuarios';
        $crear_usuarios->display_name = 'Crear usuarios';
        $crear_usuarios->description = 'Crear usuarios simples del sistema, como por ejemplo crear cuentas de empresa.';

        $crear_usuarios->save();

        return $crear_usuarios;
    }


    private function crear_listar_info_sistema_permission(){

        $listar_info_sistema = new Permission();
        $listar_info_sistema->name = 'listar_info_sistema';
        $listar_info_sistema->display_name = 'Listar información del sistema';
        $listar_info_sistema->description = 'Listar toda la información del Sistema, es decir, ver los clientes, proveedores y acuerdos de cualquier empresa';

        $listar_info_sistema->save();

        return $listar_info_sistema;

    }

    private function crear_listar_info_simple_permission(){

        $listar_info_simple = New Permission();
        $listar_info_simple->name = 'listar_info_simple';
        $listar_info_simple->display_name = 'Listar información simple';
        $listar_info_simple->description = 'Listar información de la propia empresa: permite ver los clientes, proveedores y acuerdos de la propia empresa asociada a esta cuenta de usuario';

        $listar_info_simple->save();

        return $listar_info_simple;
    }

    private function crear_crear_acuerdos_permission(){


        $crear_acuerdos = New Permission();

        $crear_acuerdos->name  = 'crear_acuerdos';
        $crear_acuerdos->display_name = 'Crear acuerdos';
        $crear_acuerdos->description = 'Crear acuerdos de una empresa con varios de sus propios clientes y proveedores.';

        $crear_acuerdos->save();

        return $crear_acuerdos;
    }


    private function crear_aceptar_acuerdos_permission(){

        $aceptar_acuerdos = new Permission();

        $aceptar_acuerdos->name = 'aceptar_acuerdos';
        $aceptar_acuerdos->display_name = 'Aceptar Acuerdos';
        $aceptar_acuerdos->description = 'Permite a una empresa aceptar un acuerdo preliminar en el que está involucrada';

        $aceptar_acuerdos->save();

        return $aceptar_acuerdos;
    }


    private function crear_admin_role(){


        $admin = new Role();
        $admin->name = 'admin';
        $admin->display_name = 'Administrador';
        $admin->description = ' Crear nuevos usuarios y acceder a toda la información del sistema.';

        $admin->save();

        return $admin;

    }


    private function crear_empresa_role(){


        $empresa = new Role();
        $empresa->name = 'empresa';
        $empresa->display_name = 'Empresa';
        $empresa->description = 'Acceder a toda la información de la propia empresa y crear acuerdos con sus clientes y proveedores.';

        $empresa->save();

        return $empresa;
    }


    private function crear_crear_relaciones_permission(){

        $crear_relaciones = new Permission();
        $crear_relaciones->name = 'crear_relaciones';
        $crear_relaciones->display_name = 'Crear relaciones entre empresas';
        $crear_relaciones->description = 'Definir qué empresas son clientes y/o proveedores de otras empresas.';

        $crear_relaciones->save();

        return $crear_relaciones;


    }


}//end entrust seeder class


?>
