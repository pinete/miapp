<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Request se usa para manejar las peticiones HTTP
use Illuminate\Validation\ValidationException; // ValidationException se usa para manejar errores de validación
use Yajra\DataTables\Facades\DataTables; // DataTables se usa para manejar tablas con paginación, búsqueda y ordenación


class EntidadController extends Controller
{
    /**
    * Devuelve el nombre (singular) del modelo según la entidad.
    * @param string $entidad (la entidad en plural, por ejemplo, 'clientes').
    * @return string (el nombre del modelo en singular, por ejemplo, 'Cliente').
    */
    private function getModelClass(string $entidad): string
    {
        //Relación entre entidades y modelos
        $mapa = [
            'clientes'   => 'Cliente',
            'proveedores'=> 'Proveedor',
            'articulos'  => 'Articulo',
            // Añade aquí tus otras entidades... 'entidad' => 'Modelo',
        ];
        if (! isset($mapa[$entidad])) {
            abort(404, "Entidad desconocida: $entidad");
        }
        return $mapa[$entidad];
    }

    /**
     * Devuelve los campos visibles para cada entidad en las tablas y formularios.
     * @param string $entidad
     * @return array
     */
    private function getCamposVisibles(string $entidad): array
    {
        return match ($entidad) {
            'clientes' => ['nombre', 'email', 'telefono'],
            'proveedores' => ['nombre', 'cif', 'email', 'telefono'],
            'articulos' => ['nombre', 'codigo', 'precio', 'stock'],
            // Añade aqui nuevas entidades y sus campos visibles en los formularios
            default => [],
        };
    }


    /**
     * Muestra la lista de la entidad dinámica (por ejemplo clientes, proveedores).
     * @param string $entidad (la entidad a mostrar, por ejemplo, 'clientes').
     * @return \Illuminate\Http\Response
     */
    public function index($entidad)
    {
        // Construye el nombre completo de la clase DataTable basada en la entidad
        $modelo = $this->getModelClass($entidad);
        $campos = $this->getCamposVisibles($entidad);

        $dataTableClass = 'App\\DataTables\\EntidadDataTable'; // Usar una clase genérica para todas las entidades
        if (!class_exists($dataTableClass)) {
            abort(404, "No se encontró el DataTable para la entidad: $entidad");
        }

        $dataTable = new \App\DataTables\EntidadDataTable($modelo, $entidad, $campos);
        // Define la ruta para el almacenamiento (store) basada en la entidad

        $view = 'entidad.entidad'; // Vista genérica para todas las entidades (resources/views/entidad/entidad.blade.php)

        return $dataTable->render($view,[
            'entidad' => $entidad,
            'storeRoute' =>route('entidad.store', ['entidad' => $entidad]),
            'campos'  => $campos
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo registro de la entidad.
     * @return \Illuminate\Http\Response
     */
    public function create($viewBlade)
    {
        return view($viewBlade);
    }

    /**
     * Función para obtener reglas de validación dinámicamente según la entidad
     * @param string $entidad
     * @param int|null $id (opcional, para reglas de actualización)
     * @return array
    */
    private function getValidationRules($entidad, $id = null)
    {
        switch ($entidad) {
            case 'clientes':
                return [
                    'nombre' => 'required|string|max:255',
                    'email' => 'required|email|unique:clientes,email' . ($id ? ',' . $id : ''),
                    'telefono' => 'nullable|string|max:20',
                ];
            case 'proveedores':
                return [
                    'nombre' => 'required|string|max:255',
                    'cif' => 'required|string|unique:proveedores,cif' . ($id ? ',' . $id : ''),
                    'email' => 'required|email|unique:proveedores,email' . ($id ? ',' . $id : ''),
                    'telefono' => 'nullable|string|max:20',
                ];
            // Añade más entidades aquí
            default:
                return [];
        }
    }

    /**
     * Guarda un nuevo registro de la entidad en la base de datos.
     * @param Request $request
     * @return \Illuminate\Http\Response
     * Esta función maneja peticiones AJAX y devuelve una respuesta JSON.
     * Usa validación para asegurar que los datos son correctos antes de guardar.
     * Maneja errores de validación y devuelve mensajes apropiados.
     */
    public function store(Request $request, $entidad)
    {
        try {
            $validated = $request->validate($this->getValidationRules($entidad));
            //$modelo = ucfirst(Str::singular($entidad));
            $modelo = $this->getModelClass($entidad);
            $modelClass = 'App\\Models\\' . $modelo;

            if (!class_exists($modelClass)) {
            return response()->json(['error' => "Modelo no encontrado: $modelo"], 500);
            }

            $modelClass::create($validated);

            return response()->json([
                'success' => true,
                'mensaje' => ucfirst($entidad) . ' creado correctamente'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
             \Log::error('Error al guardar proveedor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'mensaje' => 'Error del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Muesta un registro específico de la entidad.
     * @param string $id
     * @param string $entidad
     * @return \Illuminate\Http\Response
     */
    public function show(string $id, string $entidad)
    {
        //$modelo = ucfirst(Str::singular($entidad)); // Convierte 'clientes' a 'Cliente', 'proveedores' a 'Proveedor', etc.
        $modelo = $this->getModelClass($entidad);
        $modelClass = 'App\\Models\\' . ucfirst($modelo);
        $registro = $modelClass::findOrFail($id); // Recupera el registro de la entidad desde la base de datos antes de enviarlo a la vista.
        return view($entidad .'.show', compact('registro')); // Pasa el registro a la vista
    }

    /**
     * Proporciona los datos de un cliente específico en formato JSON.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     * Esta función es llamada vía AJAX para obtener los datos de un cliente específico en formato JSON.
     */
    public function showJson(string $entidad, string $id)
    {
        //$modelo = ucfirst(Str::singular($entidad)); // Ej: 'clientes' → 'Cliente'
        $modelo = $this->getModelClass($entidad);
        $modelClass = 'App\\Models\\' . $modelo;

        if (!class_exists($modelClass)) {
            return response()->json(['error' => "Modelo no encontrado: $modelo"], 500);
        }

        try {
            $registro = $modelClass::findOrFail($id);
            return response()->json($registro);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     *Actualiza un registro específico en la base de datos.
     * @param Request $request (los datos enviados desde el formulario de edición)
     * @param string $entidad (la entidad a actualizar, por ejemplo, 'clientes')
     * @param string $id (el ID de la entidad a actualizar)
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $entidad, $id)
    {
        $validated = $request->validate($this->getValidationRules($entidad, $id));
        //$modelo = ucfirst(Str::singular($entidad)); // Convierte 'clientes' a 'Cliente', 'proveedores' a 'Proveedor', etc.
        $modelo = $this->getModelClass($entidad);
        $modelClass = 'App\\Models\\' . $modelo;
        $registro = $modelClass::findOrFail($id);
        $registro->update($validated);

        return response()->json([
            'success' => true,
            'mensaje' => ucfirst($entidad) . ' actualizado correctamente'
        ]);
    }

    /**
     * Elimina un registro específico de la base de datos.
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $entidad, string $id)
    {
        // $modelo = ucfirst(Str::singular($entidad));
        $modelo = $this->getModelClass($entidad);
        $modelClass = 'App\\Models\\' . $modelo;

        if (!class_exists($modelClass)) {
            return response()->json(['error' => "Modelo no encontrado: $modelo"], 500);
        }

        try {
            $registro = $modelClass::findOrFail($id);
            $registro->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Proporciona datos de la entidad para DataTables y muestra los botones de acción.
     * @return \Illuminate\Http\JsonResponse
     * Esta función es llamada vía AJAX desde DataTables en la vista index.blade.php
     */
    public function getRegistrosEntidad(string $modelo)
    {
        //dd('entró en el controlador getClientes');
        $registros = ucfirst($modelo)::query(); // Consulta base para obtener los registros de la entidad

        //Eloquent DataTables permite manipular los datos antes de enviarlos a DataTables vía AJAX.
        return DataTables::eloquent($registros)
            // addColumn añade una columna de acciones con un botón de editar
            ->addColumn('action', function ($registro) {
                return //Agrega dos botones: Editar y Eliminar
                    //'<button data-id="'.$cliente->id.'" title="Editar" class="btn-editar inline-block px-3 py-1 text-sm font-semibold text-white bg-blue-600 rounded hover:bg-blue-700 transition mr-2">Editar</button>' .
                    //'<button data-id="'.$cliente->id.'" class="btn-eliminar inline-block px-3 py-1 text-sm font-semibold text-white bg-red-600 rounded hover:bg-red-700 transition">Eliminar</button>';
                    '<button data-id="'.$registro->id.'" title="Editar" class="btn-editar inline-block px-3 py-1 text-sm font-semibold text-white bg-blue-600 rounded hover:bg-blue-700 active:scale-95 transform transition duration-100 ease-in-out mr-2 cursor-pointer">'.
                        '<img src="/icons/CRUD/Editar-Icono.png" alt="Editar" class="w-6 h-6 inline">'.
                    '</button>'.
                    '<button data-id="'.$registro->id.'" title="Eliminar" class="btn-eliminar inline-block px-3 py-1 text-sm font-semibold text-white bg-red-600 rounded hover:bg-red-700 active:scale-95 transform transition duration-100 ease-in-out mr-2 cursor-pointer">' .
                        '<img src="/icons/CRUD/Eliminar-Icono.png" alt="Eliminar" class="w-6 h-6 inline">' .
                    '</button>';
            })
        ->toJson(); // Devuelve los datos en formato JSON para DataTables
    }

}
