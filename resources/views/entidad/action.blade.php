{{-- Mostrar los botones editar, eliminar y adjuntar de las lineas de la tabla --}}
@php
    /** @var \Illuminate\Database\Eloquent\Model $row */
@endphp

<div class='flex gap-x-1 min-w-[120px] justify-center'>
    <button
        data-id="{{ $row->id }}"
        data-mode="editar"
        data-entidad={{$entidad}}
        title="Editar"
        class="btn-editar group relative inline-block px-3 py-1 text-sm font-semibold text-white bg-blue-600 rounded hover:bg-blue-700 active:scale-95 transform transition duration-100 ease-in-out mr-1 cursor-pointer"
    >
        <img src="/icons/CRUD/Editar-Icono.png" alt="Editar" class="w-6 h-6 inline">
        {{--    
                Añadimos la clase 'group' al botón y usamos <span> para mostrar información complementaria 
                de la acción Editar Registro al pasar el cursor sobre el botón 
        --}}
        <span class="absolute bottom-full pointer-events-none left-1/2 transform -translate-x-1/2 -translate-y-1 bg-gray-800 text-white text-xs rounded px-2 py-1 whitespace-nowrap z-50 opacity-0 group-hover:opacity-100 transition">
            Editar/Actualizar registro
        </span>
    </button>
    <button
        data-id="{{ $row->id }}"
        data-mode="eliminar"
        data-entidad={{$entidad}}
        title="Eliminar"
        class="btn-eliminar group relative inline-block px-3 py-1 text-sm font-semibold text-white bg-red-600 rounded hover:bg-red-700 active:scale-95 transform transition duration-100 ease-in-out mr-1 cursor-pointer"
    >
        <img src="/icons/CRUD/Eliminar-Icono.png" alt="Eliminar" class="w-6 h-6 inline">
        <span class="absolute bottom-full pointer-events-none left-1/2 transform -translate-x-1/2 -translate-y-1 bg-gray-800 text-white text-xs rounded px-2 py-1 whitespace-nowrap z-50 opacity-0 group-hover:opacity-100 transition">
            Eliminar registro
        </span>
    </button>

    <button
        data-id="{{ $row->id }}"
        data-mode="adjuntar"
        title="Adjuntar archivo"
        data-entidad="{{ $entidad }}"
        class="btn-adjuntar group relative inline-block px-3 py-1 text-sm font-semibold text-white bg-gray-600 rounded hover:bg-gray-700 active:scale-95 transform transition duration-100 ease-in-out mr-1 cursor-pointer"
    >
        <img src="/icons/CRUD/icons8-adjuntar-100.png" alt="Eliminar" class="w-6 h-6 inline">
        <span class="absolute bottom-full pointer-events-none left-1/2 transform -translate-x-1/2 -translate-y-1 bg-gray-800 text-white text-xs rounded px-2 py-1 whitespace-nowrap z-50 opacity-0 group-hover:opacity-100 transition">
            Adjuntar documento/imagen al registro
        </span>
    </button>
</div>

