<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Yajra\DataTables\Facades\DataTables;


class ClienteController extends Controller
{
    /**
     * Muestra la lista de clientes.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* Opción inicial: obtener todos los clientes y pasarlos a la vista
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
        */

        // Opción con DataTables: la vista se carga vacía y los datos se obtienen vía AJAX.
        // si estás usando DataTables con AJAX, no necesitas pasar $clientes a la vista.
        // De hecho, eso puede causar conflictos si la vista espera que los datos lleguen por AJAX. (ver index.blade.php)

        return view('clientes.index');
    }


    /**
     * Muestra el formulario para crear un nuevo cliente.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clientes.create');
    }


    /**
     * Guarda un nuevo cliente en la base de datos.
     * @param Request $request
     */
    public function store(Request $request)
    {
        Cliente::create($request->all());
        return redirect()->route('clientes.index');

    }


    /**
     * Muesta un cliente específico.
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
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
        $cliente = Cliente::findOrFail($id); // Recupera el cliente desde la base de datos
        $cliente->update($request->all()); // Actualiza el cliente con los datos del formulario
        return redirect()->route('clientes.index'); // Redirige a la lista de clientes

    }

    /**
     * Elimina un cliente específico de la base de datos.
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
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
        $clientes = Cliente::query(); // Consulta base para obtener los clientes

        //Eloquent DataTables permite manipular los datos antes de enviarlos a DataTables vía AJAX.
        return DataTables::eloquent($clientes)
            // addColumn añade una columna de acciones con un botón de editar
            ->addColumn('action', function ($cliente) {
                // return '<a href="/clientes/'.$cliente->id.'/edit" class="inline-block px-3 py-1 text-sm font-semibold text-white bg-blue-600 rounded hover:bg-blue-700 transition">Editar</a>'; // Enlace a la ruta de edición. Anterior versión de botón
                // return '<button data-id="'.$cliente->id.'" class="btn-editar inline-block px-3 py-1 text-sm font-semibold text-white bg-blue-600 rounded hover:bg-blue-700 transition">Editar</button>';  // Usamos un botón con data-id para manejarlo con JavaScript y abrir el modal
                return //Agrega dos botones: Editar y Eliminar
                    '<button data-id="'.$cliente->id.'" class="btn-editar inline-block px-3 py-1 text-sm font-semibold text-white bg-blue-600 rounded hover:bg-blue-700 transition mr-2">Editar</button>' .
                    '<button data-id="'.$cliente->id.'" class="btn-eliminar inline-block px-3 py-1 text-sm font-semibold text-white bg-red-600 rounded hover:bg-red-700 transition">Eliminar</button>';
        })
        ->toJson(); // Devuelve los datos en formato JSON para DataTables
    }

}
