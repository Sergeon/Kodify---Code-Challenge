<?php namespace App\Http\Controllers;



class AppController extends Controller{

    public function __construct(){

        $this->middleware('auth');

        $this->middleware('empresa');

    }
}
