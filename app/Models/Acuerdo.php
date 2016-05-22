<?php namespace App\Models;


use Illuminate\Support\Collection;

/**
 *
 *Un acuerdo representa una asociación comercial entre empresas en sentido
 *descendente, es decir: un proveedor A de su cliente B, que a su vez es proveedor
 *de una empresa C, pueden formar un acuerdo A->B->C , donde la relación proveedor-cliente
 *es transitiva. Pero si por ejemplo C es también proveedor de A, no se puede dar el acuerdo
 *A->B->C->A , dado que el último enlace rompe el carácter descendiente de la relación
 *(lo cual no quita que el sistema permita que A sea proveedor y cliente de C al mismo tiempo:
 *puede serlo en el sistema, pero no en un mismo acuerdo comercial ).
 *
 * Se puede considerar que un acuerdo entre dos o varias empresas está a su vez
 * compuesto por acuerdos simples de tipo cliente/proveedor (que están representados
 * por el modelo Relacion).
 *
 * La tabla acuerdos_relaciones representa estas uniones y enlaza acuerdos con relaciones comerciales.
 * Por ejemplo si Bitcoin es proveedor de Gotham , y Gotham es proveedor de Pokemon,
 * un acuerdo como Bitcoin->Gotham->Pokemon estará formado por esas dos relaciones comerciales.
 *
 * Esa tabla permite obtener el orden de las jerarquías si se exige que los acuerdos
 * estén formados siempre por relaciones descencientes.
 *
 *
 * Además, las empresas tienen que aceptar activamente los acuerdos. Para representar
 * esto, se utiliza la tabla empresas_aceptan_acuerdos.
 */
class Acuerdo extends \Eloquent{

    protected $fillable = [
        'nombre','descripcion',
    ];

    public function relaciones(){
        return $this->belongsToMany('App\Models\Relacion' , 'acuerdos_relaciones' , 'acuerdo_id' , 'relacion_id');
    }

    public function empresas_aceptan(){
        return $this->belongsToMany('App\Models\Empresa' , 'empresas_aceptan_acuerdos' , 'acuerdo_id' , 'empresa_id');
    }


    /**********
    METODOS DE CREACION
    **********/

    /**
     * Comprueba si un conjunto de relaciones es apto para formar un acuerdo.
     *@param Collection $relaciones Un conjuntode relaciones.
     * @return mixed  Si un acuerdo compuesto por estas relaciones es válido, devuelve un array reprensentando el acuerdo [id_empresa1 , id_empresa2 ... ]
     * en el que cada empresa es proveedora de la siguiente en ese acuerdo.
     *
     * En caso contrario, devuelve boolean false.
     */
     private static function asegurar_acuerdo( Collection $relaciones ){

         $aux = array();
         foreach($relaciones as $rel)
             $aux[] = $rel->to_data_array();

         return self::analizar_acuerdo($aux , array() , array() );
     }

    /**
     * Lógica para analizar acuerdos
     * Método recursivo. La primera llamada pasa un array $data de relaciones. En cada llamada se procesa una
     * relacion, se quita de $data y se añade la información generada a $lista y $cache, que se utiliza para encontrar
     * el siguiente cliente o proveedor del acuerdo enlazado con el anterior que se haya añadido a $lista.
     * La última llamada devuelve $lista con la información definitiva del acuerdo válido, o lanza una excepción
     * si termina el proceso y el acuerdo no se ha validado.
     *
     * Reglas de validación:
     * Un acuerdo es válido si, y solo si, está compuesto por empresas que están ligadas entre sí en orden cliente-proveedor. Esto implica
     * que una empresa no puede ser dos veces cliente o dos veces proveedor dentro del mismo acuerdo.
     */
    private static function analizar_acuerdo( Array $data ,  Array $lista = array() ,  Array $cache = array()  ){


        if(empty($data)){
            if(empty($lista))
                throw new \Exception("argumentos inconsistentes");
            return $lista; //hemos llegado al final. devolvemos el array con la informacion.
        }

        if(empty($cache)){ //estamos leyendo el primer elemento de $data.

            $lista = array(   $data[0]['proveedor_id'] , $data[0]['cliente_id'] );
            $cache = array(  'proveedor_id' => $lista[0] , 'cliente_id' => $lista[1]);
            array_shift($data);

            return self::analizar_acuerdo( $data , $lista , $cache);
        }
        foreach($data as $relacion){

            if( ($relacion['cliente_id'] == $cache['cliente_id']) || ( $relacion['proveedor_id'] == $cache['proveedor_id'] )  )
                throw new \Exception("la misma empresa no puede ser varias veces cliente o proveedor en un mismo acuerdo");

            if($relacion['cliente_id'] == $cache['proveedor_id']){ //hemos encontrado al siguiente proveedor en data, que será el proveedor de este cliente.
                //ahora tenemos que empujarlo al array por delante, puesto que es proveedor del último proveedor conocido.
                array_shift($data);
                array_unshift($lista , $relacion['proveedor_id']);

                return self::analizar_acuerdo( $data , $lista , $relacion   );

            }

            if($relacion['proveedor_id'] == $cache['cliente_id']){
                //aqui encontramos que nuestro cliente es proveedor en otra tupla:

                array_shift($data);
                array_push( $lista , $relacion['cliente_id']);

                return self::analizar_acuerdo( $data  ,  $lista   , $relacion   );
            }

        }

        throw new \Exception( "El acuerdo que estás intentando crear tiene relaciones comerciales que no están enlazadas entre sí, y por tanto no es válido");

    }


    /**
     * Crear un acuerdo entre empresas, añadiendo tuplas a las tablas de acuerdos y
     * relaciones_acuerdos.
     * @param  array $data_acuerdo    Información general del acuerdo: nombre y descripción.
     * @param  array $data_relaciones Array de entidades Relacion que se añaden al acuerdo.
     * @return Acuerdo  Un acuerdo relacionando las empresas parasadas en $empresas y con los datos generales de $data_acuerdo
     */
    public static function crear_acuerdo( Array $data_acuerdo , Array $empresas  ){

        $relaciones = self::lista_empresas_to_acuerdo($empresas);

        //TODO una simple llamada ya lanza una excepcion, no hace falta añadir un if/ más.
        if(!self::asegurar_acuerdo($relaciones))
            throw new \Exception("El acuerdo que estás intentando crear no es válido");

        $acuerdo = new Acuerdo();
        $acuerdo->nombre = $data_acuerdo['nombre'];
        $acuerdo->descripcion = $data_acuerdo['descripcion'];

        $id = $acuerdo->save();

        $acuerdo = Acuerdo::where('nombre' , $data_acuerdo['nombre'])->first();


        try{
            foreach($relaciones as $rel)
                $acuerdo->relaciones()->attach($rel);
        }
        catch(\Exception $ex){
            if(isset($acuerdo))
                $acuerdo->delete();
            throw $ex;
        }
        return $acuerdo;
    }



    /**
     * A partir de una lista de empresas genera el array de relaciones que reprensentan
     * esas relaciones.
     * @param  array $lista Un conjunto de relaciones
     * @param  array  $cache Array de relaciones -solo se pasa en llamadas recursivas-
     * @return Array
     */
    private static function lista_empresas_to_acuerdo(array $lista , $cache = array()  ){

        if(empty($lista))
            throw new \Exception("un acuerdo comercial tiene que tener empresas");

        if(count($lista  ) < 2){

            if(count($cache) == 0)
                throw new Exception("No hay suficientes empresas para formar el acuerdo");

            return collect($cache);
        }

        $rel = Relacion::where('proveedor_id' , $lista[0] )->where('cliente_id' , $lista[1])->first();
        $cache[] = $rel;
        array_shift($lista);
        return self::lista_empresas_to_acuerdo( $lista , $cache );
    }


    /***************
    GETTERS
    ***************/

    /**
     * Devuelve las empresas de un acuerdo en un orden determinado cliente->proveedor o proveedor->cliente
     * //TODO hardcoded query?
     * @param  App\Models\Acuerdo $acuerdo
     * @param bool $reverse Indica si debe devolver los proveedores primero
     * @return Array array de App\Models\Empresa
     */
    public function get_empresas( $reverse = false ){

        $relaciones = $this->relaciones()->get()->toArray();
        $empresas_id = array();
        $empresas = array();

        foreach($relaciones as $rel){
            $empresas_id[] = $rel['cliente_id'];
            $empresas_id[] = $rel['proveedor_id'];
        }

        $empresas_id = array_unique($empresas_id);


        foreach($empresas_id as &$empresa_id){
            $empresas[] = Empresa::find($empresa_id);
        }
        if($reverse)
            return array_reverse($empresas);

        return $empresas;
    }


    /**
     * Indica si todas las empresas del acuerdo han aceptado el acuerdo
     * @param $empresas Si se pasa un array de empresas indica si solo esas empresas han aceptado este acuerdo.
     * Pasar ese parámetro permite evitar una query extra a la DB, si ya hemos obtenido las empresas del acuerdo con anterioridad.
     * @return boolean
     */
    public function is_aceptado_por_todos( $empresas = array() ){

        if(empty($empresas))
            $empresas = $this->get_empresas();

        $aceptado = true;

        foreach($empresas as $empresa)
            if( ! $empresa->acuerdo_es_aceptado($this))
                $aceptado = false;

        return $aceptado;

    }


    /**
     * Genera una representación de un acuerdo para poder renderizar tarjetas
     * @param  bool $mostrar_boton_aceptar
     * @param  [type] $aceptado              [description]
     * @return Array información del acuerdo
     */
    private function to_data( $mostrar_boton_aceptar = false , $aceptado = false ){

        $empresas = $this->get_empresas();

        $data = array( 'acuerdo' => $this, 'empresas' => $empresas  , 'mostrar_boton_aceptar' => $mostrar_boton_aceptar , 'aceptado' => $aceptado );

        if(\Auth::user()->hasRole('admin'))
            $data['mostrar_boton_borrar'] = true;

        return $data;

    }



    /**
     * Nueva versión para trabajar con listados. En el futuro debe ser la única y quitar la anterior TODO renombrar
     * Genera una representación de un acuerdo para poder renderizar tarjetas
     * @param  [type] $aceptado  Indica si la empresa que está pintando el listado ha aceptado el acuerdo o no. Solo se lee en caso de que la
     * perspectiva del usuario sea la de una cuenta de empresa.
     * @return Array información del acuerdo
     */
    public function new_to_data( $aceptado = false ){

        $empresas =  $this->get_empresas( true ) ;

        $nombres_empresa = array();

        foreach($empresas as $empresa)
            $nombres_empresa[] = $empresa->nombre . " | "; //TODO se podría hacer una vista solo para esto.

        $nombres_empresa = implode(' ' , $nombres_empresa );



        $data = array( 'nombre' => $this->nombre , 'descripcion' => $this->descripcion , 'id' => $this->id ,  'empresas' => $nombres_empresa   , 'aceptado' => $aceptado );

        if(\Auth::user()->hasRole('admin')){
            $data['mostrar_boton_borrar'] = true;
            $data['estado'] = $this->is_aceptado_por_todos( $empresas ) ? "Aceptado" : "En Espera";
        }
        else{
            $data['estado'] = $aceptado ? 'Aceptado' : 'Pendiente';
            $data['aceptado'] = $aceptado;
            $data['mostrar_boton_aceptar'] = $aceptado; //refactor en el listado.
        }

        return $data;
    }



    /**
     * renderiza un acuerdo usando partials/database/tarjeta-acuerdo
     * @param  [type] $mostrar_boton_aceptar [description]
     * @param  [type] $aceptado              [description]
     * @return [type]                        [description]
     */
    public function to_tarjeta($mostrar_boton_aceptar = false , $aceptado = false ){
        return \View::make('partials/database/tarjeta-acuerdo' , $this->to_data(  $mostrar_boton_aceptar , $aceptado ) );
    }





}
