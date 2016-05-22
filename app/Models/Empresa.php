<?php

namespace App\Models;


/**
 * representa empresas en el sistema. Las entidades de Empresa
 * están siempre relacionadas con una cuenta de usuario.
 *
 * Las empresas están relacionadas entre sí mediante relaciones comerciales (ver modelo Relacion )
 *
 */
class Empresa extends Eloquent {

    /*********
    ELOQUENT SETUP
    *********/

    protected $fillable = [
        'nombre' , 'sede_social' , 'nif'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function clientes(){
        return $this->belongsToMany('App\Models\Empresa' , 'relaciones' , 'proveedor_id' , 'cliente_id');
    }

    public function proveedores(){
        return $this->belongsToMany('App\Models\Empresa' , 'relaciones' , 'cliente_id' , 'proveedor_id');
    }

    public function acuerdos_aceptados(){
        return $this->belongsToMany('App\Models\Acuerdo' , 'empresas_aceptan_acuerdos' , 'empresa_id' , 'acuerdo_id');
    }





    public function get_non_proveedores(){
        return $this->get_non('proveedores');
    }

    public function get_non_clientes(){
        return $this->get_non('clientes');
    }

    private function get_non($type){

        $all = $this->get();
        $collection = $type == 'clientes' ? $this->clientes : $this->proveedores;
        $self = Empresa::where('nombre' , $this->nombre)->get();


        return $all->diff($collection)->diff($self);

    }

    public function get_empresas_select_options( $type = 'clientes'  ){

        if($type == 'clientes')
            $raw = $this->get_non_clientes();
        else
            $raw = $this->get_non_proveedores();

        $data = array();


        foreach($raw as $row){
            $data[$row->id] =  $row->nombre ;
        }

        return \View::make('partials/html/select-options' , array(  'select_data' => $data ) );
    }

    private function borrar_relacion( Empresa $empresa , $relacion){

        try{
            if($relacion == 'cliente')
                $this->clientes()->detach($empresa->id);
            else if($relacion == 'proveedor')
                $this->proveedores()->detach($empresa->id);
            else
                throw new \Exception("Se ha intentado borrar un tipo de relación que no existe");
        }
        catch(\Exception $ex){
            //logging?
            throw $ex;
        }
    }

    public function borrar_cliente( Empresa $cliente){
        $this->borrar_relacion($cliente , 'cliente');
    }

    public function borrar_proveedor( Empresa $proveedor){
        $this->borrar_relacion($proveedor , 'proveedor');
    }



    private function add_relacion($rel , $is_cliente ){

        if($this->id == $rel->id ){
            Throw new \Exception( "No puedes relacionar a una empresa consigo misma como cliente o proveedor");
        }

        if ($is_cliente)
            $this->clientes()->attach($rel->id);
        else
            $this->proveedores()->attach($rel->id);
    }

    public function add_cliente( Empresa $cliente){
        $this->add_relacion($cliente , true );
    }

    public function add_proveedor(Empresa $proveedor){
        $this->add_relacion($proveedor , false );
    }




    public function es_proveedor_de($target  ){
        return $this->clientes()->get()->contains($target);
    }

    public function es_cliente_de($target ){
        return $this->proveedores()->get()->contains($target);
    }





    /**
     * Devuelve empresas que sean proveedores de al menos otra empresa.
     * @return Collection
     */
    public static function get_solo_proveedores(){

        return self::whereIn('id' ,\DB::table('relaciones')->pluck('proveedor_id') )->get();

    }



    /**
     *
     * @param  bool $filtrar indica si debe filtar los acuerdos o devolverlos todos
     * @param  bool $aceptados En caso de $filtrar, indica si debe devolver acuerdos aceptados o por aceptar.
     * @return [type]            [description]
     */
    private function get_acuerdos( $filtrar = false , $aceptados = false ){

        $relaciones = Relacion::where('cliente_id' , $this->id )->orWhere('proveedor_id' , $this->id)->get();
        $acuerdos = array();
        $id_storage = array();

        foreach($relaciones as $rel )
            foreach($rel->acuerdos()->get() as $ac){
                if(in_array(  $ac->id , $id_storage ))
                    continue;

                    if($filtrar && $aceptados){//el cliente quiere SOLAMENTE ACUERDOS ACEPTADOS

                        if($this->acuerdo_es_aceptado($ac)){
                            $acuerdos[] = $ac;
                            $id_storage[] = $ac->id;
                        }

                    }
                    else if($filtrar){ //el cliente quiere acuerdos NO ACEPTADOS

                        if(! $this->acuerdo_es_aceptado($ac)){
                            $acuerdos[] = $ac;
                            $id_storage[] = $ac->id;
                        }
                    }
                    else{ //el cliente quiere TODOS LOS ACUERDOS
                        $acuerdos[] = $ac;
                        $id_storage[] = $ac->id;
                    }
            }//each acuerdos

        return $acuerdos;

    }



    public function get_acuerdos_aceptados(){
        $res = $this->get_acuerdos(true , true );

        return $res;
    }

    public function get_acuerdos_por_aceptar(){
        return $this->get_acuerdos(true , false );
    }

    public function get_todos_acuerdos(){
        return $this->get_acuerdos();
    }


    public function aceptar_acuerdo( $acuerdo ){
        $this->acuerdos_aceptados()->attach($acuerdo);
    }

    public function renegar_acuerdo( $acuerdo ){
        $this->acuerdos_aceptados()->detach($acuerdo);

        //DB::table('empresas_aceptan_acuerdos')->where('empresa_id' , $this->id )->where('acuerdo_id' , $acuerdo->id)->delete();

    }


    public function acuerdo_es_aceptado($acuerdo){

        return  count(\DB::select("select * from empresas_aceptan_acuerdos where empresa_id = $this->id and acuerdo_id = $acuerdo->id")) == 1;
    }


}
