<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreguntasController;
use App\Http\Controllers\PDFcontroller;
use App\Http\Controllers\EncuestasController;
use App\Http\Controllers\asignarPreguntaController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ConcesionesController;
use App\Http\Controllers\ConexionesController;
use App\Http\Controllers\VerificarController;
use App\Http\Controllers\Encuestas_PreguntasController;

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
Route::post('/verificado/{id}',[App\Http\Controllers\ContestarController::class, 'verificadoOrden'])->name('verificadoOrden');

Route::get('/verificadoAutomotriz1/{id}',[App\Http\Controllers\VerificarController::class, 'verificadoAutomotriz1']);

Route::get('/verificadoAutomotriz2/{id}',[App\Http\Controllers\VerificarController::class, 'verificadoAutomotriz2']);

Route::get('/verificadoAutomotriz3/{id}',[App\Http\Controllers\VerificarController::class, 'verificadoAutomotriz3']);

Route::get('/verificadoAutomotriz4/{id}',[App\Http\Controllers\VerificarController::class, 'verificadoAutomotriz4']);

Route::get('/verificadoAutomotriz5/{id}',[App\Http\Controllers\VerificarController::class, 'verificadoAutomotriz5']);

Route::get('/verificarAutomotriz6/{id}',[App\Http\Controllers\VerificarController::class, 'verificarAutomotriz6']);

Route::post('/contestar/{id}',[App\Http\Controllers\ContestarController::class, 'contestar'])->name('contestar');
Route::post('/detalle', [App\Http\Controllers\ContestarController::class, 'detalle'])->name('detalle');

Route::get('/', function () {
    return view('auth/login');
});


Auth::routes(['verify' => true]);//;
Route::post('/enviar',[App\Http\Controllers\ContestarController::class, 'enviar'])->name('enviar');

Route::get('/home', [App\Http\Controllers\EncuestasController::class, 'index'])->name('home')->middleware('verified');

Route::resource('/preguntas', PreguntasController::class)->names('preguntas');
Route::resource('/encuestas', EncuestasController::class)->names('encuestas');
Route::resource('/usuarios', UsersController::class)->names('usuarios');

Route::get('/automotriz1', [App\Http\Controllers\ConcesionesController::class, 'automotriz1']);
Route::get('/automotriz2', [App\Http\Controllers\ConcesionesController::class, 'automotriz2']);
Route::get('/automotriz3', [App\Http\Controllers\ConcesionesController::class, 'automotriz3']);
Route::get('/automotriz4', [App\Http\Controllers\ConcesionesController::class, 'automotriz4']);
Route::get('/automotriz5', [App\Http\Controllers\ConcesionesController::class, 'automotriz5']);
Route::get('/automotriz6', [App\Http\Controllers\ConcesionesController::class, 'automotriz6']);

Route::get('/automotriz1/users', [App\Http\Controllers\UsersController::class, 'automotriz1']);
Route::get('/automotriz2/users', [App\Http\Controllers\UsersController::class, 'automotriz2']);
Route::get('/automotriz3/users', [App\Http\Controllers\UsersController::class, 'automotriz3']);
Route::get('/automotriz4/users', [App\Http\Controllers\UsersController::class, 'automotriz4']);
Route::get('/automotriz5/users', [App\Http\Controllers\UsersController::class, 'automotriz5']);
Route::get('/automotriz6/users', [App\Http\Controllers\UsersController::class, 'automotriz6']);


Route::get('/asignar/{id}', [App\Http\Controllers\EncuestasController::class, 'Preguntasdisp']);
Route::get('/estadisticas/{id}', [App\Http\Controllers\EncuestasController::class, 'estadisticas']);
Route::get('/home/search',[App\Http\Controllers\PreguntasController::class,'search'])->name('search');
Route::get('/administracion',[App\Http\Controllers\UsersController::class,'index'])->name('administracion');
Route::get('/actulizar/{id}',[App\Http\Controllers\PreguntasController::class,'respuestasEdit']);
Route::get('/respuestas/{id}',[App\Http\Controllers\PreguntasController::class,'respuestas']);
Route::get('/asesor/{encuesta}/grafica/{id}',[App\Http\Controllers\EncuestasController::class,'grafica']);

Route::post('/detalle-diario/{id}', [App\Http\Controllers\EncuestasController::class, 'detalle_diario']);
Route::post('/resumen/{id}', [App\Http\Controllers\EncuestasController::class, 'resumen']);
Route::post('/encuesta/{id}', [App\Http\Controllers\EncuestasController::class, 'encuesta']);
Route::post('/asesores/{id}', [App\Http\Controllers\EncuestasController::class, 'asesores']);
Route::post('/agregar/{id}', [App\Http\Controllers\EncuestasController::class, 'agregarP']);
Route::post('/agregarR/{id}', [App\Http\Controllers\PreguntasController::class, 'agregarR']);
Route::post('/destroyR/{id}', [App\Http\Controllers\PreguntasController::class, 'destroyR']);
Route::post('/remover/{id}', [App\Http\Controllers\EncuestasController::class, 'removerP']);
Route::post('/removerR/{id}', [App\Http\Controllers\PreguntasController::class, 'removerR']);
Route::post('respuestasUpdate/{id}', [App\Http\Controllers\PreguntasController::class, 'respuestasupdate']);
Route::post('/preguntas/respuestasCreate/{id}', [App\Http\Controllers\PreguntasController::class, 'respuestasCreate']);
//Route::post('/status/{id}', [App\Http\Controllers\EncuestasController::class, 'statusE']);
Route::post('/statusP/{id}', [App\Http\Controllers\PreguntasController::class, 'statusP']);

/*Rutas ajax*/
Route::get('/ajax',[App\Http\Controllers\EncuestasController::class,'ajaxGrafica']);
Route::get('/encuestasAjax',[App\Http\Controllers\EncuestasController::class,'estadisticasAjax']);
Route::get('/asesoresAjax',[App\Http\Controllers\EncuestasController::class,'asesoresAjax']);
Route::get('/detalleAjax',[App\Http\Controllers\EncuestasController::class,'detalleAjax']);

Route::get('/search',[App\Http\Controllers\PreguntasController::class,'search'])->name('search');

/*Rutas para pdf's */


//Route::get('download', 'PDFController@download')->name('download');

Route::post('/encuestas/status/{id}', [App\Http\Controllers\EncuestasController::class, 'statusE'])->name('encuestas.status');