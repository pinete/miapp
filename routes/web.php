<?php
//use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

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

// Nueva opción (versión 2): Rutas personalizadas para el CRUD de clientes
Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index'); // Ruta para listar clientes
Route::get('/clientes/data', [ClienteController::class, 'getClientes'])->name('clientes.data'); // Ruta para obtener datos vía AJAX para DataTables
Route::get('/clientes/{id}/edit', [ClienteController::class, 'edit'])->name('clientes.edit'); // Ruta para mostrar el formulario de edición
Route::get('/clientes/{id}/json', [ClienteController::class, 'showJson'])->name('clientes.json'); // Ruta para obtener datos de un cliente específico en formato JSON
Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update'); // Ruta para actualizar el cliente
Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy'); // Ruta para eliminar un cliente
Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create'); // Ruta para mostrar el formulario de creación
Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store'); // Ruta para guardar un nuevo cliente

