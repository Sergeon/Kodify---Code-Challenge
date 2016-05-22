<?php namespace App\Models;


class Eloquent extends \Eloquent{



    public static function getColumns(){

        $class =  get_called_class();
        $instance = new $class;

        return $instance->fillable;


    }



}
