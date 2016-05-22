<?php namespace App\Http\Controllers;


use App\Models\Empresa;
use App\Models\Relacion;
use App\Models\Acuerdo;

use Illuminate\Support\Facades\Input as Input;


class EmpresaController extends AppController{

    private $empresa;
    private $offset = 4;
    private $page = 1;
    private $skip = 0;
    private $count;


    public function __construct(){

        $user = \Auth::user();
        if($user)
            $this->empresa = Empresa::where('user_id' , $user->id)->first();


        return parent::__construct();
    }

    /***********
    API
    ************/

    /**
     * Landing con el listado de relaciones de la empresa y la información de la empresa
     * @return View
     */
    public function getIndex(){

        $user = \Auth::user();

        if(!$user)
            return \Redirect::to('/login');

        if(!$this->empresa)
            return \Redirect::to('/login');


        $listado_data = $this->get_listado_relaciones(  );

        $listado_data['pagination'] = $this->get_paginacion();
        $listado = \View::make('partials/database/listado' , $listado_data );

        return \View::make('pages/empresa/index' , array('empresa' => $this->empresa , 'listado' => $listado ));
    }



    /**
     * Devuelve el listado pedido por ajax.
     * Listener en app.js
     * @return string render() de la vista con el listado de datos.
     */
    public function ajaxListadoRelaciones(){

        $options = Input::all();

        if(isset($options['page'])){
            $this->page = $options['page'];
            $this->skip = ($this->page - 1 ) * $this->offset;
        }


        $listado_data = $this->get_listado_relaciones( $options );

        $listado_data['pagination'] = $this->get_paginacion( );
        $listado = \View::make('partials/database/listado' , $listado_data );



        return array('html' => $listado->render() );
    }


    /***********
    PRIVATE
    **********/

    /**
     * Lee de la table relaciones y devuelve datos para el listado en función de la empresa
     * que somos y de las opciones exigidas.
     * @param  array  $options Opciones para filtrar los datos
     * @return Collection datos para pintar el listado
     */
    private function get_listado_relaciones( $options = array() ){

        if(isset($options['relacion']) && $options['relacion'] == 'clientes'){
            $relaciones_query = Relacion::where('proveedor_id' , $this->empresa->id)->skip( $this->skip )->take( $this->offset );
            $this->count = Relacion::where('proveedor_id' , $this->empresa->id)->count();

        }
        else if ( isset($options['relacion']) && $options['relacion'] == 'proveedores'){
            $relaciones_query = Relacion::where('cliente_id' , $this->empresa->id)->skip( $this->skip )->take( $this->offset );
            $this->count = Relacion::where('cliente_id' , $this->empresa->id)->count();
        }
        else{
            $relaciones_query = Relacion::where('proveedor_id' , $this->empresa->id )->orWhere('cliente_id' , $this->empresa->id )->skip( $this->skip )->take( $this->offset );
            $this->count = Relacion::where('proveedor_id' , $this->empresa->id )->orWhere('cliente_id' , $this->empresa->id )->count();
        }


        $relaciones_query = $relaciones_query->get();

        $ordenar_por_nombre = isset($options['nombre']) && $options['nombre'] == 'true' ? true : false;
        return $this->make_listado_relaciones($relaciones_query ,  $ordenar_por_nombre  );
    }



    private function make_listado_relaciones( $relaciones  , $ordenar_por_nombre = false){

        $keys = array('empresa' , 'relacion');

        foreach($relaciones as &$relacion){

            if($relacion->cliente_id == $this->empresa->id ){
                $relacion->empresa = Empresa::find($relacion['proveedor_id'])->nombre;
                $relacion->relacion = 'Proveedor';
            }
            else{
                $relacion->empresa = Empresa::find($relacion['cliente_id'])->nombre;
                $relacion->relacion = 'Cliente';
            }
        }

        if($ordenar_por_nombre)
            $relaciones = $relaciones->sortBy('empresa');

        $util['model_name'] = 'Relacion';
        $util['empresa_objetivo'] = \Auth::user()->empresa->id;

        return array(
            'values' => $relaciones,
            'actions' => array(),
            'keys' => $keys,
            'util' => $util
        );

    }






    /**
     * Devuelve los datos para el link de cada página en el componente de paginación.
     * @return [type] [description]
     */
    private function calcular_paginacion(   ){



        $numero_paginas = round($this->count / 4 );
        $listado_paginacion_data = array();

        $min = $this->page - 2 > 0 ?   $this->page - 2   : 1 ;
        $max = $this->page + 2 > $numero_paginas ? $numero_paginas : $this->page + 2;


        for($i = $min ; $i <= $max ; $i++ ){
            if($i == $this->page )
                $listado_paginacion_data[$this->page]['class'] = 'active';
            else
                $listado_paginacion_data[$i]['class'] = ' ';
        }

        return $listado_paginacion_data;

    }

    private function get_paginacion(   ){

        $pagination = $this->calcular_paginacion( );

        return \View::make('partials/database/pagination' , array('pagination' => $pagination ));

    }



}
?>
