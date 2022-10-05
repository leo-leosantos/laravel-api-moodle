<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/listamatricula', 'MatriculaController@index')->name('listramatricula');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/matricular/alunno/{id}', 'MatriculaController@matricular')->name('matricularalunno');


Route::get('/matricular/alunno/crear/nuevo', 'MatriculaController@crearnuevoregistro')->name('crearmatricula');
Route::post('/matricular/alunno/crear/nuevousuario','MatriculaController@guardanuevousuario')->name('guardanuevousuario');



Route::get('/matricular/eliminar/alunno/{id}', 'MatriculaController@eliminar')->name('eliminaralunno');




