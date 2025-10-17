@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-blue-600">Bienvenido al CRUD Genérico</h1>
        <p class="mt-2 text-gray-700 text-sm">
            Esta aplicación permite gestionar cualquier tabla creada en tu base de datos MySQL mediante Laravel.
            Cada nueva entidad (tabla) mostrará sus registros automáticamente (usando Datatable), incorporando acciones CRUD y la opción de adjuntar archivos.
        </p>
    </div>

    <div x-data="{ pasoActivo: null }" class="space-y-4">
    @foreach([
        'PASO 1' => 'Para crear una nueva tabla (migración) en Laravel ejecuta:
            <br>
                <div class="text-center font-bold">
                    <code>
                    php artisan make:migration create_nombre_tabla --create=nombre_tabla
                    </code>
                </div>
            <br>Ejemplo: Creamos una tabla llamada "documentos" <br>
                <div class="text-center font-bold">
                    <code>
                        php artisan make:migration create_documentos_tabla --create=documentos_tabla
                    </code><br>
                </div>
                En Docker/Sail: Si trabajamos en un contenedor docker, ejecutamos desde el contenedor
                <div class="text-center font-bold">
                    <br><code>
                        ./vendor/bin/sail php artisan make:migration create_documentos_tabla --create=documentos_tabla
                    </code>
                </div>',
        'PASO 2' => 'Define la estructura en la función 
            <span class="font-bold"> <code> up() </code> </span> 
            del archivo de migración recien creado en 
            <span class="font-bold"> "./database/migrations"</span>: Por ejemplo, para una tabla "clientes"...
            <div class="font-bold">
                <br>
                <code>
                    <pre>
                        public function up(): void
                        {
                            Schema::create("clientes", function (Blueprint $table) {
                                $table->id();
                                $table->string("nombre");
                                $table->string("email")->unique();
                                $table->string("telefono")->nullable();
                                $table->timestamps();
                            });
                        }
                    </pre>
                </code>
            </div>',
            'PASO 3' => 'Crea el modelo:
            <code>
                <div class="text-center font-bold">
                    <br> php artisan make:model NombreDelModelo<br>
                </div>
            </code>
            En un entorno Dockker... :
            <code>
                <div class="text-center font-bold">
                    <br>./vendor/bin/sail php artisan make:model NombreDelModelo<br>
                </div>
            </code>
            <br>
            <div class="text-center text-red-500">
                <span class = " font-bold"> 
                    Recuerda: 
                </span>
                el nombre del modelo debe ser el nombre de la tabla en singular y la primera letra en mayúsculas. (Ej: Tabla articulos ... Modelo Articulo)
            </div>
            <br>
           
            Tras crear el modelo, modifícalo para seleccionar los campos  protegidos y la relación polimorfica de la tabla con sus datos adjuntos
            <br> Por ejemplo, en un modelo Cliente...: <br>
            <div class="font-bold">
                <code><pre>
                    class Cliente extends Model 
                    {
                        protected $fillable = "nombre", "email", "telefono" ]; 

                        public function adjuntos() { 
                            return $this->morphMany(Adjunto::class,"adjuntable");
                        }
                    }            
                </pre></code>
            </div>
            ',
        'PASO 4' => 'Ejecuta la migración. Ésto creará la tabla en la base de datos:
            <code>
                <div class="text-center font-bold">
                    <br>
                        php artisan migrate
                    <br>
                </div>
            </code>
            En Docker/Sail:
            <code>
                <div class="text-center font-bold">
                    <br>
                        ./vendor/bin/sail php artisan migrate
                </div>
            </code>',
        
        'PASO 5' => 'Para editar/modificar y crear se requiere de validaciones. Incorpora la entidad (la nueva tabla creada)  en el switch case de la función 
            <code>
                <span class = "font-bold"> 
                    getValidationRules 
                </span>
            </code> 
                del controlador
            <code>
                <span class = "font-bold"> 
                    ./app/Http/Controllers/EntidadController.php 
                </span>
            </code>
            y define las reglas de los campos de la nueva entidad. (Puedes guiarte con las validaciones que ya existen).',
        'PASO 6' => 'Relaciona tabla => modelo en la función 
                    <code>
                        <span class = "font-bold"> 
                            getEntidadesModelos
                        </span>
                    </code> de
                    <code>
                        <span class = "font-bold"> 
                            ./app/Http/Controllers/EntidadController.php
                        </span>
                    </code> 
                    para que la app sepa qué modelo usar para cada tabla de la DB.',
        'PASO 7' => 'Finalmente añadiremos el botón del menú, que permitirá mostrar los registros de la nueva tabla, en 
            <span class="font-bold">‘.views/layouts/menu.blade.php’.</span> 
            Simplemente añade debajo de los botones otro botón igual indicando la entidad y el texto que debe aparecer dentro del mismo
            <div class="font-bold">
                <pre><code>
                &lt;a href="@verbatim{{ route(\'entidad.index\', [\'entidad\' => \'Nombre_de_la_tabla\']) }}@endverbatim" class="px-4 py-2 bg-gray-700 rounded hover:bg-gray-600 transition"&gt;
                    Texto_a_mostrar
                &lt;/a&gt;</code></pre>
            </div>'
    ] as $paso => $contenido)
        <div class="border border-gray-300 rounded-md">
            <button @click="pasoActivo = pasoActivo === '{{ $paso }}' ? null : '{{ $paso }}'"
                class="w-full text-left px-4 py-2 bg-gray-100 hover:bg-gray-200 font-semibold text-blue-700 transition">
                {{ $paso }}
            </button>
            <div x-show="pasoActivo === '{{ $paso }}'" x-transition
                 class="px-4 py-3 text-sm text-gray-800 bg-white border-t border-gray-200">
                {!! $contenido !!}
            </div>
        </div>
    @endforeach

@endsection