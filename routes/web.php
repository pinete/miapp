<?php
//use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EntidadController;
use App\DataTables\ClientesDataTable;

// Rutas básicas de Laravel. Pagina de bienvenida. Activa la vista resources/views/welcome.blade.php
Route::get('/', function () {
    return view('welcome');
});

Route::get('/{entidad}/data', [EntidadController::class, 'getRegistrosEntidad']); // Obtener datos vía AJAX para DataTables
Route::get('/{entidad}/create', [EntidadController::class, 'create']); // Mostrar formulario de creación
Route::post('/{entidad}/store', [EntidadController::class, 'store'])->name('entidad.store'); // Guardar nuevo registro
Route::get('/{entidad}/{id}/edit', [EntidadController::class, 'edit']); // Mostrar formulario de edición
Route::put('/{entidad}/{id}', [EntidadController::class, 'update']); // Actualizar registro
Route::delete('/{entidad}/{id}', [EntidadController::class, 'destroy'])->name('entidad.destroy');
Route::get('/{entidad}/{id}/json', [EntidadController::class, 'showJson'])->name('entidad.json'); // Obtener datos de un registro específico en formato JSON
Route::get('/{entidad}/{id}', [EntidadController::class, 'show']); // Mostrar detalles de un registro específico
Route::post('/{entidad}/store', [EntidadController::class, 'store'])->name('entidad.store');
Route::get('/{entidad}', [EntidadController::class, 'index'])->name('entidad.index');