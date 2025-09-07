@extends('layouts.app')
@section('content')


<!-- Usamos TailWind para mostrar datos en tabla -->

<div class="flex justify-between items-center mb-4">
    {{-- Título --}}
    <h1 class="text-3xl font-bold text-blue-600">Listado de Clientes</h1>
    {{-- Botón para crear un nuevo cliente --}}
    {{--
    <a href="{{ route('clientes.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
        + Nuevo Cliente
    </a>
    --}}
    <button id="ntn-crear" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700" data-mode="crear">
    + Nuevo Cliente
</button>


</div>

<table id="clientes-table" class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
    </thead>
</table>

<!-- Modal de edición Cliente-->
<div id="modal-editar" class="fixed inset-0 bg-gray-800 bg-opacity- backdrop-blur-sm flex items-center justify-center z-50 hidden transition-opacity duration-600">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <h2 class="text-xl font-bold mb-4">Editar Cliente</h2>

        <form id="form-editar" data-mode="editar" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="modal-id" name="id">

            <div class="mb-4">
                <label for="editar-nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" id="editar-nombre" name="nombre" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label for="editar-email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="editar-email" name="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label for="editar-telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" id="editar-telefono" name="telefono" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" id="btn-cerrar-modal-editar" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Guardar</button>
            </div>
        </form>

        <!-- Botón de cierre en la esquina -->
        <button id="btn-cerrar-modal-editar-icono" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
    </div>
</div>

<!-- Modal para alta de cliente -->
<div id="modal-crear" class="fixed inset-0 bg-gray-800 bg-opacity-30 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <form id="form-crear" method="POST" data-mode="crear" action="{{ route('clientes.store') }}" class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
        @csrf
        <h2 class="text-xl font-bold mb-4">Nuevo Cliente</h2>

        <input type="text" name="nombre" id="crear-nombre" placeholder="Nombre" class="w-full mb-3 p-2 border rounded">
        <input type="email" name="email" id="crear-email" placeholder="Email" class="w-full mb-3 p-2 border rounded">
        <input type="text" name="telefono" id="crear-telefono" placeholder="Teléfono" class="w-full mb-3 p-2 border rounded">

        <div class="flex justify-end gap-2 mt-4">
            <button type="button" id="btn-cerrar-modal-crear" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancelar</button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Guardar</button>
        </div>
    </form>
</div>

@endsection

{{-- Script para inicializar DataTables --}}
@section('scripts')
<script>
// Inicialización de DataTables con procesamiento del lado del servidor
$(document).ready(function() {
    //Abrir modal de edición
    $('#clientes-table').DataTable({
        processing: true,
        serverSide: true,
       ajax: {
            url: "{{ route('clientes.data') }}",
            type: 'GET',
            dataSrc: function(json) {
                console.log('Respuesta JSON:', json);
                return json.data; // Vemos por consola la estructura de datos json.data recibida
            }
        },

        columns: [
            { data: 'id', name: 'id' },
            { data: 'nombre', name: 'nombre' },
            { data: 'email', name: 'email' },
            { data: 'telefono', name: 'telefono' },
            { // Columna de acciones (botones linea) .... Ver ClienteController.php -> getClientes()
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });
});

// Abrir modal de edición y cargar datos del cliente
$(document).on('click', '.btn-editar', function(e) {

    e.preventDefault();
    const id = $(this).data('id');

    $.get(`/clientes/${id}/json`, function(data) { // Ruta para obtener datos del cliente en formato JSON
        $('#modal-id').val(data.id);
        $('#editar-nombre').val(data.nombre);
        $('#editar-email').val(data.email);
        $('#editar-telefono').val(data.telefono);
        $('#form-editar').attr('action', `/clientes/${data.id}`); // Actualiza la acción del formulario
        $('#modal-editar')
            .removeClass('hidden opacity-0') // Asegura que el modal no esté oculto y sin opacidad
            .addClass('opacity-100 transition-opacity duration-600'); // Muestra el modal con transición
    });
});

// Abrir modal de creación
    $('#ntn-crear').on('click', function () {
        $('#crear-nombre, #crear-email, #crear-telefono').val('');
        $('#modal-crear').removeClass('hidden opacity-0').addClass('opacity-100');
    });

    // Cerrar modales
    $('#btn-cerrar-modal-editar').on('click', function () {
        $('#modal-editar').removeClass('opacity-100').addClass('opacity-0');
        setTimeout(() => $('#modal-editar').addClass('hidden'), 300);
    });
    $('#btn-cerrar-modal-crear').on('click', function () {
        $('#modal-crear').removeClass('opacity-100').addClass('opacity-0');
        setTimeout(() => $('#modal-crear').addClass('hidden'), 300);
    });


// Manejo del botón de eliminación de un cliente
$(document).on('click', '.btn-eliminar', function(e) { // Botón de eliminar
    e.preventDefault();
    const id = $(this).data('id');

    if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
        $.ajax({
            url: `/clientes/${id}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#clientes-table').DataTable().ajax.reload();
                alert('Cliente eliminado correctamente');
            },
            error: function(xhr) {
                alert('Error al eliminar el cliente');
            }
        });
    }
});
</script>
@endsection

