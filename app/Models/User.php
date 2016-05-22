<?php

 namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * Representa usuarios con la capacidad de autenticarse en el sistema
 * y de poseer roles y permisos.
 *
 * Un usuario puede o no estar relacionados con empresas. Los usuarios no
 * relacionados con empresas son administradores, mientras que los usuarios
 * asociados a una empresa no lo son.
 *
 *
 * Un administrador puede crear usuarios y empresas, borrarlos, crear, borar y editar
 * información de empresas y de sus relaciones comerciales, y crear nuevos acuerdos entre empresas.
 *
 * El único permiso espécifico que tienen las empresas y que los administradores no tienen,
 * es la capacidad de aceptar acuerdos.
 */
class User extends Authenticatable
{

    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function events(){
        return $this->hasMany('Event');
    }

    public function empresa(){
        return $this->hasOne('App\Models\Empresa'); //hasOne in both models? or there should be any belongsTo ?
    }

    public static function getColumns(){
        return array('username' , 'email');
    }


    /**
     * Estos deben ser los dos únicos puntos de entrada de la clase para crear entidades,
     * de tal modo que un cliente no pueda crear entidades empresa/usuario que no estén bien
     * relacionadas entre sí.
     *
     */
    public static function crear_cuenta_empresa($data){
        return self::crear_usuario($data , false );
    }


    public static function crear_administrador($data){
        return self::crear_usuario($data , true );
    }





    /**
     * Crea administradores o empresas en función de los parámetros
     * @param  Array  $data  propiedades del usuario/empresa
     * @param  bool $admin indica si se debe crear un administrador
     * @return User El usuario recién creado
     */
    private static function crear_usuario(  Array $data  ,  $admin = false ){

        if(empty($data))
            throw new \Exception(  "No puedes crear usuarios sin datos!");

        if(isset($data['nif']) && $admin )
            throw new \Exception("debes usar User::crear_empresa() para crear cuentas de empresa");

        $user = self::create(array(
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => \Hash::make($data['password']),
        ));

        $empresa = null;

        try{

            if($admin){
                $role = Role::where('name' , 'admin')->first();
                $user->attachRole($role);
            }
            else{
                $role = Role::where('name' , 'empresa')->first();
                $user->attachRole($role);


                $empresa = new Empresa();
                $empresa->nombre = $data['nombre'];
                $empresa->nif = $data['nif'];
                $empresa->sede_social = $data['sede_social'];
                $empresa->user_id = $user->id;

                $empresa->save();

            }
        }
        catch(\Exception $ex){

            //Si hay algún fallo después de crear el user, no queremos dejar el usuario
            //flotando en el sistema sin que su empresa asociada haya sido creada o sin los
            //roles adecuados si era un administrador.

            $user = self::where('username' , $data['username'])->first();
            if(! empty($user) )
                $user->delete();

            throw $ex;
        }


        /*Igual que en el catch anterior, si ha habido algún tipo de fallo que
        compromente la integridad del sistema, no queremos salvar las entidades*/
        if( (!$admin) &&  (!$empresa->user->id === $user->id) ) {
            $user->delete();
            $empresa->delete();

            throw new \Exception("Ha habido algún problema creando una cuenta de empresa, en DButil::crear_usuario(). ");
        }

        return $user;

    }










}
