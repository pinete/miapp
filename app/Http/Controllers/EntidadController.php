<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Request se usa para manejar las peticiones HTTP
use Illuminate\Validation\ValidationException; // ValidationException se usa para manejar errores de validación
use App\Models\Adjunto; // Modelo Adjunto
use App\DataTables\EntidadDataTable;

/*
    NOTA:   1. Para crear una nueva tabla (migración) en laravel:
                php artisan make:migration create_nombre_tabla --create=nombre_tabla
                Ejemplo para crear una tabla documentos...
                    php artisan make:migration create_documentos_table --create=documentos
                    OJO: dentro del entorno Docker/Sail o WSL para tu proyecto Laravel sería...
                    ./vendor/bin/sail artisan make:migration create_documentos_tabla --create=documentos_tabla

            2. Definimos la estructura en la migración en la funcion up
                Por ejemplo:
                    public function up()
                    {
                        Schema::create('documentos', function (Blueprint $table) {
                            $table->id();
                            $table->string('titulo');
                            $table->text('descripcion')->nullable();
                            $table->string('mime');
                            $table->binary('contenido'); // si guardas el archivo en la base de datos
                            $table->timestamps(); //Fechas de creación y modificación
                        });
                    }

            3. Dentro del entorno, ejecutamos la migración:
                php artisan migrate
                OJO: dentro del entorno sería...
                ./vendor/bin/sail php artisan migrate

            4. Por último, crear el modelo: ./vendor/bin/sail php artisan make:model Articulo

            NOTA:   Si se necesitan validaciones, incorpora la entidad en el switch case de la
                    función getValidationRules

*/

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
            'adjuntos'   => 'Adjunto',
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
            'articulos' => ['codigo', 'nombre', 'pvp'],
            'adjuntos' => ['adjuntable_type','adjuntable_id','nombre', 'mime'],
            // Añade aqui nuevas entidades y sus campos visibles en los formularios
            default => [],
        };
    }

    /**
    * Devuelve los campos que han de desplegarse (+) para ser vistos de cada entidad en las tablas y formularios.
    * @param string $entidad
    * @return array
    */
    private function getCamposOcultos(string $entidad): array
    {
        return match ($entidad) {
            'clientes' => [],
            'proveedores' => [],
            'articulos' => ['tipoImp', 'porcImp', 'ctrlSerLot', 'ctrlStock', 'stockMin', 'stockMax'],
            'adjuntos' => [],
            // Añade aqui nuevas entidades y sus campos ocultos en los formularios
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
        $camposOcultos = $this->getCamposOcultos($entidad);

        $dataTableClass = 'App\\DataTables\\EntidadDataTable'; // Usar una clase genérica para todas las entidades
        if (!class_exists($dataTableClass)) {
            abort(404, "No se encontró el DataTable para la entidad: $entidad");
        }

        $dataTable = new \App\DataTables\EntidadDataTable($modelo, $entidad, $campos, $camposOcultos);
        // Define la ruta para el almacenamiento (store) basada en la entidad

        $view = 'entidad.entidad'; // Vista genérica para todas las entidades (resources/views/entidad/entidad.blade.php)

        return $dataTable->render($view,[
            'entidad' => $entidad,
            'storeRoute' =>route('entidad.store', ['entidad' => $entidad]),
            'campos'  => $campos,
            'camposOcultos' =>$camposOcultos
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
            case 'articulos':
                return [
                    'codigo' => 'required|string|max:255|unique:articulos,codigo' . ($id ? ',' . $id : ''),
                    'nombre' => 'required|string|max:255',
                    'pvp' => 'nullable|numeric',
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
        $modelClass = 'App\\Models\\' . $modelo;
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
     * @param string $entidad (la entidad a actualizar, por ejemplo, 'clientes')
     * @return JsonResponse $dataTable incluyendo camposVisibles y camposOcultos
     * Esta función es llamada vía AJAX desde DataTables en la vista index.blade.php
     */
public function getRegistrosEntidad(string $entidad)
// Simplificamos la función y trasladamos los botones al método dataTable() dentro de EntidadDataTable
{
   
    $modelo = $this->getModelClass($entidad);
    $camposVisibles = $this->getCamposVisibles($entidad);
    $camposOcultos  = $this->getCamposOcultos($entidad);

    $dataTable = new EntidadDataTable($modelo, $entidad, $camposVisibles, $camposOcultos);

    //dd(\App\Models\Articulo::find(1)->toArray());

    return $dataTable->dataTable($dataTable->query())
                     ->with([
                         'camposVisibles' => $camposVisibles,
                         'camposOcultos' => $camposOcultos
                     ])
                     ->toJson();

}

    /**
     * Adjunta un archivo a un registro de una entidad
     */
    public function adjuntarArchivo(Request $request)
    {
        // Debug inicial
        \Log::info('Archivo recibido:', ['archivo' => $request->file('archivo')]);

        if (!$request->hasFile('archivo')) {
            \Log::error('Archivo no detectado por Laravel');
            return response()->json(['error' => 'Archivo no recibido'], 400);
        }

        try {
            $request->validate([
                'archivo' => 'required|file|max:5120|mimes:pdf,doc,docx,txt,jpg,png',
                'entidad' => 'required|string',
                'id' => 'required|integer',
            ]);

            return $this->adjuntarA($request->entidad, $request->id, $request->file('archivo'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validación fallida:', $e->errors());

            return response()->json([
                'success' => false,
                'mensaje' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error inesperado al adjuntar archivo: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'mensaje' => 'Error del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Adjunta un archivo a cualquier entidad usando morphMany.
     */
    private function adjuntarA(string $entidad, int $id, \Illuminate\Http\UploadedFile $archivo): \Illuminate\Http\JsonResponse
    {
        $modeloClass = 'App\\Models\\' . $this->getModelClass($entidad);
        // Verificación de existencia del modelo
        if (!class_exists($modeloClass)) {
            \Log::error("Modelo no encontrado para entidad: $entidad");
            return response()->json(['error' => 'Entidad no válida'], 400);
        }

        $registro = $modeloClass::findOrFail($id);
        // Verificación de existencia del registro
        if (!$registro) {
            \Log::error("Registro no encontrado: entidad=$entidad, id=$id");
            return response()->json(['error' => 'Registro no encontrado'], 404);
        }

        // Verificación de relación adjuntos
        if (!method_exists($registro, 'adjuntos')) {
            \Log::error("La relación adjuntos no está definida en el modelo $modeloClass");
            return response()->json(['error' => 'Relación adjuntos no definida'], 500);
        }

        // Verifica si ya existe un adjunto con ese nombre
        $existe = $registro->adjuntos()->where('nombre', $archivo->getClientOriginalName())->exists();
        if ($existe) {
            return response()->json(['error' => 'Ya existe un archivo con ese nombre'], 409);
        }

        // Guarda el archivo en la base de datos
        $registro->adjuntos()->create([
            'nombre' => $archivo->getClientOriginalName(),//'nombre' => $archivo->hashName(), // Para evitar duplicados
            'mime' => $archivo->getMimeType(), //'mime' => $archivo->extension(),
            'contenido' => file_get_contents($archivo->getRealPath()), //'contenido' => Storage::get($archivo->hashName()),
        ]);

        return response()->json(['success' => true]);
    }


    /**
     * Muestra un archivo adjunto.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function verAdjunto($id)
    {
        $adjunto = Adjunto::findOrFail($id);
        return response($adjunto->contenido)
            ->header('Content-Type', $adjunto->mime)
            ->header('Content-Disposition', 'inline; filename="' . $adjunto->nombre . '"');
    }
}
