<?php
/*Esto crea automáticamente rutas como:
•	GET /clientes → listar
•	GET /clientes/create → formulario
•	POST /clientes → guardar
•	GET /clientes/{id}/edit → editar
•	PUT /clientes/{id} → actualizar
•	DELETE /clientes/{id} → eliminar
*/

//use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('clientes', ClienteController::class);
