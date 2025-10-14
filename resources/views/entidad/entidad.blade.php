@extends('layouts.app')

@section('content')
    @php
        // Variables dinámicas
        $titulo = 'Listado de ' . ucfirst($entidad); // Título de la página
        $storeRoute = route('entidad.store', ['entidad' => $entidad]); // Ruta para crear nuevo registro
        $storeRouteAdjuntos = route('entidad.store', ['entidad' => 'adjuntos']); // Ruta para adjuntar archivos a un registro
        $jsonRoute = url("/{$entidad}/:id/json"); // Ruta para obtener datos en JSON
        $updateRoute = url("/{$entidad}/:id"); // Ruta para actualizar registro
        $deleteRoute = url("/{$entidad}/:id"); // Ruta para eliminar registro
        $tableId = $entidad . '-table'; // ID único para la tabla
    @endphp

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold text-blue-600">{{ $titulo }}</h1>
        <button
            id="btn-crear"
            data-mode="crear"
            data-entidad={{$entidad}}
            title="Nuevo registro"
            class="btn-crear inline-block px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded hover:bg-green-700 active:scale-95 transform transition duration-100 ease-in-out mr-2 cursor-pointer"
            data-mode="crear"
        >
            <img src="/icons/CRUD/Agregar-Icono.png" alt="Agregar" class="w-6 h-6 inline">
        </button>
    </div>


    {!! $dataTable->table(['id' => $tableId, 'class' => 'table table-auto table-bordered table-striped'], true) !!}
    {!! $dataTable->scripts() !!}

    <!-- Modal de edición -->
    {{--@include('entidad.modales.modal-editar', ['entidad' => $entidad, 'campos' => $campos]) --}}
    @include('entidad.modales.modal-editar-crear', ['modo' => 'editar', 'entidad' => $entidad]) 

    <!-- Modal de creación -->
    {{--@include('entidad.modales.modal-crear', ['entidad' => $entidad, 'campos'=>$campos, 'storeRoute' => $storeRoute])--}}
    @include('entidad.modales.modal-editar-crear', ['modo' => 'crear', 'entidad' => $entidad, 'storeRoute' => $storeRoute])

    <!-- Modal adjuntar -->
    @include('entidad.modales.modal-adjuntos', ['entidad' => 'adjuntos'])
@endsection

@section('scripts')

    <script>
        // listenerEntidadBlade y modalFunctions.js son importados desde app.js
        
        // Exponemos las variables para ser usadas en listenerEntidadBlade.js (hacemos que sean utilizables en JS)
        window.camposVisibles = @json($campos);
        window.camposOcultos = @json($camposOcultos);
        document.body.dataset.entidad = "{{ $entidad }}";
    </script>

@endsection

