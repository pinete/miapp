<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Request se usa para manejar las peticiones HTTP
use Illuminate\Validation\ValidationException; // ValidationException se usa para manejar errores de validación
use App\Models\Cliente; // Modelo Cliente
use Yajra\DataTables\Facades\DataTables; // DataTables se usa para manejar tablas con paginación, búsqueda y ordenación
use App\DataTables\ClientesDataTable;


class ClienteController extends Controller
{
    /**
     * Muestra la lista de clientes.
     * @return \Illuminate\Http\Response
     */
    public function index(ClientesDataTable $dataTable)
    {
        return $dataTable->render('clientes.clientes');
    }




    /**
     * Muestra el formulario para crear un nuevo cliente.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd('Entró en el controlador create');

        return view('clientes.create');
    }


    /**
     * Guarda un nuevo cliente en la base de datos.
     * @param Request $request
     * @return \Illuminate\Http\Response
     * Esta función maneja tanto peticiones normales como AJAX.
     * El código para peticiones normales está comentado.
     * Para peticiones normales, redirige a la lista de clientes.
     *
     * Para peticiones AJAX, devuelve una respuesta JSON.
     * Usa validación para asegurar que los datos son correctos antes de guardar.
     * Maneja errores de validación y devuelve mensajes apropiados.
     */
    public function store(Request $request)
    {
        /*
        //Método básico sin validación ni respuesta JSON
        Cliente::create($request->all());
        return redirect()->route('clientes.index');
        */

        //Método con validación y respuesta JSON para peticiones AJAX

        //dd($request->method(), $request->all());
        //dd('Entró en el controlador store');

        try {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email',
            'telefono' => 'nullable|string|max:20',
        ]);

        Cliente::create($validated);

        return response()->json([
            'success' => true,
            'mensaje' => 'Cliente creado correctamente'
        ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }
    }


    /**
     * Muesta un cliente específico.
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        //dd('entró en el controlador show');
        return view('clientes.show', compact('cliente'));
    }


    /**
     * Proporciona los datos de un cliente específico en formato JSON.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     * Esta función es llamada vía AJAX para obtener los datos de un cliente específico en formato JSON.
     */
    public function showJson($id)
    {
        //dd('entró en el controlador showJson');
        $cliente = Cliente::findOrFail($id);
        return response()->json($cliente); //Así puedes hacer una petición AJAX y rellenar el modal sin salir de la vista.
    }


    /**
     * Muestra el formulario para editar un cliente específico.
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        //dd('entró en el controlador edit');
        $cliente = Cliente::findOrFail($id); //  Recupera el cliente desde la base de datos antes de enviarlo a la vista.
        return view('clientes.edit', compact('cliente')); // Pasa el cliente a la vista
    }

    /**
     *Actualiza un cliente específico en la base de datos.
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        /* Método básico sin validación ni respuesta JSON
        /* Método básico sin validación ni respuesta JSON
        $cliente = Cliente::findOrFail($id); // Recupera el cliente desde la base de datos
        $cliente->update($request->all()); // Actualiza el cliente con los datos del formulario
        return redirect()->route('clientes.index'); // Redirige a la lista de clientes
        */

        //dd($request->method(), $request->all());
        //dd('entró en el controlador update');
        // Método con validación y respuesta JSON para peticiones AJAX
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email,' . $id,
            'telefono' => 'nullable|string|max:20',
        ]);

        $cliente = Cliente::findOrFail($id);
        $cliente->update($validated);


        return response()->json([
            'success' => true,
            'mensaje' => 'Cliente actualizado correctamente'
        ]);
    }

    /**
     * Elimina un cliente específico de la base de datos.
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        //dd('entró en el controlador destroy');
        $cliente = Cliente::findOrFail($id); // Recupera el cliente desde la base de datos
        $cliente->delete(); // Elimina el cliente de la base de datos
        //return redirect()->route('clientes.index'); // Redirige a la lista de clientes
        return response()->json(['success' => true]); // Respuesta JSON para la petición AJAX
    }


    /**
     * Proporciona datos de clientes para DataTables y muestra los botones de acción.
     * @return \Illuminate\Http\JsonResponse
     * Esta función es llamada vía AJAX desde DataTables en la vista index.blade.php
     */
    public function getClientes()
    {
        //dd('entró en el controlador getClientes');
        $clientes = Cliente::query(); // Consulta base para obtener los clientes

        //Eloquent DataTables permite manipular los datos antes de enviarlos a DataTables vía AJAX.
        return DataTables::eloquent($clientes)
            // addColumn añade una columna de acciones con un botón de editar
            ->addColumn('action', function ($cliente) {
                return //Agrega dos botones: Editar y Eliminar
                    //'<button data-id="'.$cliente->id.'" title="Editar" class="btn-editar inline-block px-3 py-1 text-sm font-semibold text-white bg-blue-600 rounded hover:bg-blue-700 transition mr-2">Editar</button>' .
                    //'<button data-id="'.$cliente->id.'" class="btn-eliminar inline-block px-3 py-1 text-sm font-semibold text-white bg-red-600 rounded hover:bg-red-700 transition">Eliminar</button>';
                    '<button data-id="'.$cliente->id.'" title="Editar" class="btn-editar inline-block px-3 py-1 text-sm font-semibold text-white bg-blue-600 rounded hover:bg-blue-700 active:scale-95 transform transition duration-100 ease-in-out mr-2 cursor-pointer">'.
                        '<img src="/icons/CRUD/Editar-Icono.png" alt="Editar" class="w-6 h-6 inline">'.
                    '</button>'.
                    '<button data-id="'.$cliente->id.'" title="Eliminar" class="btn-eliminar inline-block px-3 py-1 text-sm font-semibold text-white bg-red-600 rounded hover:bg-red-700 active:scale-95 transform transition duration-100 ease-in-out mr-2 cursor-pointer">' .
                        '<img src="/icons/CRUD/Eliminar-Icono.png" alt="Eliminar" class="w-6 h-6 inline">' .
                    '</button>';
            })
        ->toJson(); // Devuelve los datos en formato JSON para DataTables
    }

}
