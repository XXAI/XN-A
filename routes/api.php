<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/signin','API\Auth\LoginController@doLogin');
Route::get('/perfil','API\Auth\PerfilController');

Route::post('/cargar_archivos', 'CargarArchivosController@cargarArchivoNomina');

Route::get('/generar_layouts/{$batch}', 'GenerarLayoutsController@generarPorBatch');

Route::apiResource('/batch','API\BatchController');