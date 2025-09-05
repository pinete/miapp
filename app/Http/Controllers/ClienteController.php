<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    /**
     * Muestra la lista de clientes.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));

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
        return redirect()->route('clientes.index'); // Redirige a la lista de clientes

    }
}
