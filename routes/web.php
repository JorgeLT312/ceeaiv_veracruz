<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\CapturaInicialController;
use App\Http\Controllers\PrimerContactoController;
use App\Http\Controllers\RevController;
use App\Http\Controllers\RenaviController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\VideosController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/email', function() {
	return view('emails.bienvenida', ['data'=>'']);
});
Route::middleware('auth')->group(function() {

Route::get('/', [CapturaInicialController::class, 'index']);

Route::put('/perfil/update', [PerfilController::class, 'update'])->name('perfil.update');
Route::post('/perfil/updatePassword', [PerfilController::class, 'update'])->name('update.password');
Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');

Route::group(['middleware' => ['role:Admin']], function () {
	Route::get('usuarios', [UserController::class, 'index'])->name('usuarios.index');
	Route::post('usuarios', [UserController::class, 'store'])->name('usuarios.store');
	Route::patch('usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');
	Route::post('/usuario/desactivar', [UserController::class, 'deactivate']);
	Route::post('/usuario/activar', [UserController::class, 'activate']);
	Route::post('/usuario/updatePassword', [UserController::class, 'updatePassword'])->name('updateUsuario.password');
});

Route::get('captura-inicial', [CapturaInicialController::class, 'index'])->name('captura.index');
Route::post('captura-inicial', [CapturaInicialController::class, 'store'])->name('captura.store');
Route::get('captura-inicial/{id}', [CapturaInicialController::class, 'edit'])->name('captura.edit');
Route::patch('captura-inicial/{captura}', [CapturaInicialController::class, 'update'])->name('captura.update');
Route::delete('captura-inicial/{id}', [CapturaInicialController::class, 'destroy'])->name('captura.destroy');

Route::group(['middleware' => ['role:Operativo|Nivel Superior', 'can:viewAny,App\Models\PrimerContacto']], function () {
	Route::get('primer-contacto', [PrimerContactoController::class, 'index'])->name('contacto.index');
});

Route::get('primer-contacto/{id}', [PrimerContactoController::class, 'edit'])->name('contacto.edit');
Route::patch('primer-contacto/{generales_id}', [PrimerContactoController::class, 'update'])->name('contacto.update');
Route::post('primer-contacto/file', [PrimerContactoController::class, 'subirArchivo'])->name('contacto.file');
Route::get('primer-contacto/check-url/{generalesId}', [PrimerContactoController::class, 'verificarArchivo'])->name('verificar-archivo');
Route::get('primer-contacto/telefonos/{id}', [PrimerContactoController::class, 'telefonos'])->name('contacto.telefonos');
Route::get('primer-contacto/familiares/{id}', [PrimerContactoController::class, 'familiares'])->name('contacto.familiares');
Route::get('primer-contacto/imprimir-primer-contacto/{tipo_formato}/{generalesId}', [PrimerContactoController::class,'imprimirPrimerContacto'])->name('imprimir-primer-contacto');

Route::post('ocupaciones', [PrimerContactoController::class, 'store_ocupacion']);

Route::group(['middleware' => ['role:Operativo|Nivel Superior', 'can:viewAny,App\Models\RegistrosEstatales']], function () {
	Route::get('rev', [RevController::class, 'index'])->name('rev.index');
});

Route::get('rev/{id}', [RevController::class, 'edit'])->name('rev.edit');
Route::post('rev', [RevController::class, 'store'])->name('rev.store');
Route::patch('rev/{generales_id}', [RevController::class, 'update'])->name('rev.update');
Route::post('rev/file', [RevController::class, 'subirArchivo'])->name('rev.file');
Route::get('rev/imprimir-rev/{tipo_formato}/{generalesId}', [RevController::class,'imprimirRev'])->name('imprimir-rev');
Route::post('rev/status/{generales_id}', [RevController::class, 'changeStatus'])->name('rev.status');
Route::get('rev/descarga-completo/{generalesId}', [RevController::class,'descargaCompleto'])->name('descarga-completo');

Route::group(['middleware' => ['role:Operativo|Nivel Superior', 'can:viewAny,App\Models\Renavi']], function () {
	Route::get('renavi', [RenaviController::class, 'index'])->name('renavi.index');
});

Route::get('renavi/{id}', [RenaviController::class, 'edit'])->name('renavi.edit');
Route::post('renavi', [RenaviController::class, 'store'])->name('renavi.store');
Route::patch('renavi/{generales_id}', [RenaviController::class, 'update'])->name('renavi.update');
Route::patch('renavi/registrar-folio/{generales_id}', [RenaviController::class, 'registerInvoice'])->name('renavi.invoice');
Route::get('excelRenavi', [RenaviController::class,'excelRenavi'])->name('excelRenavi');

Route::get('notificaciones', [NotificacionesController::class, 'index'])->name('notificaciones.index');
Route::post('notificaciones', [RevController::class, 'store'])->name('notificaciones.store');
Route::get('obtenerNotificaciones', [NotificacionesController::class, 'obtenerNotificaciones'])->name('notificaciones.obtenerNotificaciones');


Route::group(['middleware' => ['role:Nivel Superior|Operativo|Admin']], function () {
	Route::get('reportes', [ReportesController::class, 'index'])->name('reportes.index');	
	Route::get('reportes/imprimir-reporte/{generalesId}', [ReportesController::class,'imprimirReporte'])->name('imprimir-reporte');
});

Route::get('videotutoriales', [VideosController::class, 'index']);

});

