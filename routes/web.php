<?php
//use Illuminate\Support\Facades\Route;xs..
//use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EntidadController;
//use App\DataTables\ClientesDataTable;
use App\Http\Controllers\AdjuntoController;

// Rutas básicas de Laravel. Pagina de bienvenida. Activa la vista resources/views/welcome.blade.php
Route::get('/', function () {
    return view('welcome');
});

// Rutas para la gestión de Adjuntos de una entidad
Route::get('/adjuntos', [AdjuntoController::class, 'filtrados'])->name('adjuntos.filtrados');
Route::delete('/adjuntos/{id}', [AdjuntoController::class, 'destroy'])->name('adjuntos.destroy');
Route::get('/adjuntos/{id}/descargar', [AdjuntoController::class, 'descargar'])->name('adjuntos.descargar');


// Rutas para la gestión dinámica de entidades
Route::get('/{entidad}/data', [EntidadController::class, 'getRegistrosEntidad']); // Obtener datos vía AJAX para DataTables
//Route::get('/{entidad}/create', [EntidadController::class, 'create']); // Mostrar formulario de creación
Route::post('/{entidad}/store', [EntidadController::class, 'store'])->name('entidad.store'); // Guardar nuevo registro
//Route::get('/{entidad}/{id}/edit', [EntidadController::class, 'edit']); // Mostrar formulario de edición
Route::put('/{entidad}/{id}', [EntidadController::class, 'update']); // Actualizar registro
Route::delete('/{entidad}/{id}', [EntidadController::class, 'destroy'])->name('entidad.destroy'); // Eliminar registro
Route::get('/{entidad}/{id}/json', [EntidadController::class, 'showJson'])->name('entidad.json'); // Obtener datos de un registro específico en formato JSON
//Route::get('/{entidad}/{id}', [EntidadController::class, 'show']); // Mostrar detalles de un registro específico

Route::get('/{entidad}', [EntidadController::class, 'index'])->name('entidad.index'); // Listar registros de una entidad específica
Route::get('/{entidad}/estructura', [EntidadController::class, 'estructuraJson'])->name('entidad.estructura'); // Para crear el json de la estructura de la entidad

// Omitimos estas rutas para evitar entrar "a saco" en la tabla Adjuntos y poder borrar, modificar y crear 
// registros sin control de la aplicación (son registros vinculados a otras entidades)
Route::post('/adjuntos', [EntidadController::class, 'adjuntarArchivo'])->name('entidad.adjuntar'); // Adjuntar archivo a un registro
// Route::get('/adjuntos/{id}', [EntidadController::class, 'verAdjunto']);


