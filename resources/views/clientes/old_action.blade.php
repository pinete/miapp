{{-- Mostrar los botones editar y eliminar de las lineas de la tabla --}}
<div class='flex gap-x-2'>
    <button data-id="{{ $id }}" title="Editar" class="btn-editar inline-block px-3 py-1 text-sm font-semibold text-white bg-blue-600 rounded hover:bg-blue-700 active:scale-95 transform transition duration-100 ease-in-out mr-2 cursor-pointer">
        <img src="/icons/CRUD/Editar-Icono.png" alt="Editar" class="w-6 h-6 inline">
    </button>
    <button data-id="{{ $id }}" title="Eliminar" class="btn-eliminar inline-block px-3 py-1 text-sm font-semibold text-white bg-red-600 rounded hover:bg-red-700 active:scale-95 transform transition duration-100 ease-in-out mr-2 cursor-pointer">
        <img src="/icons/CRUD/Eliminar-Icono.png" alt="Eliminar" class="w-6 h-6 inline">
    </button>
</div>

