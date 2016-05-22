<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/********
GET
********/


//--->Login
Route::get('/', array('uses' => 'HomeController@getIndex'));
Route::get('login', array('uses' => 'HomeController@showLogin'));


//---> Empresa
Route::get('empresa' , array('uses' => 'EmpresaController@getIndex'));


/********
REST
********/

//--->Login
Route::post('login', array('uses' => 'HomeController@doLogin'));

//---> Empresa
Route::post( 'empresa/ajax-listado-relaciones' , array( 'uses' => 'EmpresaController@ajaxListadoRelaciones' ));
