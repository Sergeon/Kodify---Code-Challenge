<?php namespace App\Models;


/**
 * Representa relaciones comerciales entre empresas.
 *
 * Las empresas se relacionan unas con otras mediante relaciones comerciales,
 * representadas por el modelo Relacion, en la que una empresa es cliente o proveedora
 * de la otra. Esta relación es simétrica, si una empresa A es cliente de B, entonces B
 * es proveedor de A.
 *
 */
class Relacion extends \Eloquent{


    /**
     * A pesar del nombre acuerdos, esta relacion es referente a
     * la tabla pivot acuerdos_relaciones
     * @return [type] [description]
     */
    public function acuerdos(){
        return $this->belongsToMany('App\Models\Acuerdo' , 'acuerdos_relaciones' , 'relacion_id' , 'acuerdo_id' );
    }

    protected $table = 'relaciones';


    public function to_data_array(){
        return array('cliente_id' => $this->cliente_id , 'proveedor_id' => $this->proveedor_id );
    }


}
