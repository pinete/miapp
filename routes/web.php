<?php
//use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EntidadController;
use App\DataTables\ClientesDataTable;

// Rutas básicas de Laravel. Pagina de bienvenida. Activa la vista resources/views/welcome.blade.php
Route::get('/', function () {
    return view('welcome');

});

// Opción inicial: rutas automáticas con resource.
// Funcionamiento básico, pero no permite personalizar las rutas ni añadir nuevas.
    /*Esto crea automáticamente rutas como:
            •	GET /clientes → listar
            •	GET /clientes/create → formulario
            •	POST /clientes → guardar
            •	GET /clientes/{id}/edit → editar
            •	PUT /clientes/{id} → actualizar
            •	DELETE /clientes/{id} → eliminar
    */
// Descomenta la línea siguiente para usar rutas automáticas con resource
//Route::resource('clientes', ClienteController::class);

/*
// Nueva opción (versión 2): Rutas personalizadas para el CRUD de clientes
Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index'); // Ruta para listar clientes
Route::get('/clientes/data', [ClienteController::class, 'getClientes'])->name('clientes.data'); // Ruta para obtener datos vía AJAX para DataTables
Route::get('/clientes/{id}/edit', [ClienteController::class, 'edit'])->name('clientes.edit'); // Ruta para mostrar el formulario de edición
Route::get('/clientes/{id}/json', [ClienteController::class, 'showJson'])->name('clientes.json'); // Ruta para obtener datos de un cliente específico en formato JSON
Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update'); // Ruta para actualizar el cliente
Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy'); // Ruta para eliminar un cliente
Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create'); // Ruta para mostrar el formulario de creación
Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store'); // Ruta para guardar un nuevo cliente
*/

// Rutas genéricas (version3) para manejar múltiples entidades (clientes, productos, órdenes, etc.)
// Estas rutas utilizan un controlador genérico EntidadController que maneja diferentes modelos según la entidad
//Route::get('/{entidad}', [EntidadController::class, 'index']); // Listar registros de la entidad
Route::get('/{entidad}/data', [EntidadController::class, 'getRegistrosEntidad']); // Obtener datos vía AJAX para DataTables
Route::get('/{entidad}/create', [EntidadController::class, 'create']); // Mostrar formulario de creación
//Route::post('/{entidad}', [EntidadController::class, 'store']); // Guardar nuevo registro
Route::post('/{entidad}/store', [EntidadController::class, 'store'])->name('entidad.store'); // Guardar nuevo registro
Route::get('/{entidad}/{id}/edit', [EntidadController::class, 'edit']); // Mostrar formulario de edición
Route::put('/{entidad}/{id}', [EntidadController::class, 'update']); // Actualizar registro
//Route::delete('/{entidad}/{id}', [EntidadController::class, 'destroy']); // Eliminar registro
Route::delete('/{entidad}/{id}', [EntidadController::class, 'destroy'])->name('entidad.destroy');
//Route::get('/{entidad}/{id}/json', [EntidadController::class, 'showJson']); // Obtener datos de un registro específico en formato JSON
Route::get('/{entidad}/{id}/json', [EntidadController::class, 'showJson'])->name('entidad.json'); // Obtener datos de un registro específico en formato JSON
Route::get('/{entidad}/{id}', [EntidadController::class, 'show']); // Mostrar detalles de un registro específico

Route::post('/{entidad}/store', [EntidadController::class, 'store'])->name('entidad.store');
Route::get('/{entidad}', [EntidadController::class, 'index'])->name('entidad.index');
