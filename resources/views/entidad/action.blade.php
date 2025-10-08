{{-- Mostrar los botones editar, eliminar y adjuntar de las lineas de la tabla --}}
@php
    /** @var \Illuminate\Database\Eloquent\Model $row */
@endphp

<div class='flex gap-x-1 min-w-[120px] justify-center'>
    <button
        data-id="{{ $row->id }}"
        data-mode="editar"
        title="Editar"
        class="btn-editar inline-block px-3 py-1 text-sm font-semibold text-white bg-blue-600 rounded hover:bg-blue-700 active:scale-95 transform transition duration-100 ease-in-out mr-1 cursor-pointer"
    >
        <img src="/icons/CRUD/Editar-Icono.png" alt="Editar" class="w-6 h-6 inline">
    </button>
    <button
        data-id="{{ $row->id }}"
        data-mode="eliminar"
        title="Eliminar"
        class="btn-eliminar inline-block px-3 py-1 text-sm font-semibold text-white bg-red-600 rounded hover:bg-red-700 active:scale-95 transform transition duration-100 ease-in-out mr-1 cursor-pointer"
    >
        <img src="/icons/CRUD/Eliminar-Icono.png" alt="Eliminar" class="w-6 h-6 inline">
    </button>

    <button
        data-id="{{ $row->id }}"
        data-mode="adjuntar"
        title="Adjuntar archivo"
        data-entidad="{{ $entidad }}"
        class="btn-adjuntar inline-block px-3 py-1 text-sm font-semibold text-white bg-gray-600 rounded hover:bg-gray-700 active:scale-95 transform transition duration-100 ease-in-out mr-1 cursor-pointer"
    >
        <!--<i class="fas fa-paperclip"></i> -->
        <img src="/icons/CRUD/icons8-adjuntar-100.png" alt="Eliminar" class="w-6 h-6 inline">
    </button>
</div>

