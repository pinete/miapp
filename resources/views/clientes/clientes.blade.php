@extends('layouts.app')
@section('content')

<!-- Usamos TailWind para mostrar datos en tabla -->

<div class="flex justify-between items-center mb-4">
    {{-- Título --}}
    <h1 class="text-3xl font-bold text-blue-600">Listado de Clientes</h1>
    {{-- Botón para crear un nuevo cliente --}}
    <button id="btn-crear" title="Nuevo registro" class="inline-block px-4 py-2  text-sm font-semibold text-white bg-green-600 rounded hover:bg-green-700 active:scale-95 transform transition duration-100 ease-in-out mr-2 cursor-pointer" data-mode="crear">
        <img src="/icons/CRUD/Agregar-Icono.png" alt="Editar" class="w-6 h-6 inline">
    </button>
</div>

{!! $dataTable->table(['id' => 'clientes-table','class' => 'table table-bordered table-striped'], true) !!}
{!! $dataTable->scripts() !!}

<!-- Modal de edición Cliente-->
<div id="modal-editar" class="fixed inset-0 bg-gray-800 bg-opacity- backdrop-blur-sm flex items-center justify-center z-50 hidden transition-opacity duration-600">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <h2 class="text-xl font-bold mb-4">Editar Cliente</h2>

        <form id="form-editar" data-mode="editar" method="POST">
            @csrf

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
                <button type="button" id="btn-cerrar-modal-editar" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 active:scale-95 transform transition duration-100 ease-in-out mr-2 cursor-pointer">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-400 active:scale-95 transform transition duration-100 ease-in-out mr-2 cursor-pointer">Guardar</button>
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
            <button type="button" id="btn-cerrar-modal-crear" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 active:scale-95 transform transition duration-100 ease-in-out mr-2 cursor-pointer">Cancelar</button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-400 active:scale-95 transform transition duration-100 ease-in-out mr-2 cursor-pointer">Guardar</button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>

$(document).ready(function () {
    // Verifica si la variable tabla ya existe y si no la carga en global
    if (typeof window.tabla === 'undefined') {
        window.tabla = $('#clientes-table').DataTable();

        // Cargamos los botones de exportación
        // $('#clientes-table').DataTable().buttons().container().appendTo('#clientes-table_wrapper .col-md-6:eq(0)');
        window.tabla.buttons().container().appendTo('#clientes-table_wrapper .col-md-6:eq(0)');
    }

    // Abrir modal de edición y cargar datos del cliente
    $(document).on('click', '.btn-editar', function (e) {
        e.preventDefault();
        console.log('Script de btn-editar -  Abrir modal de edición y cargar datos del cliente')
        const id = $(this).data('id');

        $.get(`/clientes/${id}/json`, function (data) {
            $('#modal-id').val(data.id);
            $('#editar-nombre').val(data.nombre);
            $('#editar-email').val(data.email);
            $('#editar-telefono').val(data.telefono);
            $('#form-editar').attr('action', `/clientes/${data.id}`);
            $('#modal-editar')
                .removeClass('hidden opacity-0')
                .addClass('opacity-100 transition-opacity duration-600');
        });
    }); // Fin de btn-editar

    // Abrir modal de creación
    $('#btn-crear').on('click', function () {
        console.log('Script btn-crear - Abrir modal de creación')
        $('#crear-nombre, #crear-email, #crear-telefono').val('');
        $('#modal-crear')
            .removeClass('hidden opacity-0')
            .addClass('opacity-100 transition-opacity duration-600');
    }); // Fin btn-crear

    // Cerrar modal de edición
    $('#btn-cerrar-modal-editar').on('click', function () {
        console.log('script btn-cerrar-modal-editar de Cerrar Modales')
        $('#modal-editar').removeClass('opacity-100').addClass('opacity-0');
        setTimeout(() => $('#modal-editar').addClass('hidden'), 300);
    }); // Fin btn-cerrar-modal-editar

    // Cerrar modal de creación
    $('#btn-cerrar-modal-crear').on('click', function () {
         console.log('Script btn-cerrar-modal-crear de Cerrar Modales')
        $('#modal-crear').removeClass('opacity-100').addClass('opacity-0');
        setTimeout(() => $('#modal-crear').addClass('hidden'), 300);
    }); // Fin btn-cerrar-modal-crear

    // Manejo del botón de eliminación de un cliente
    $(document).on('click', '.btn-eliminar', function (e) {
         console.log('Script btn-eliminar - Manejo del botón de eliminación de un cliente')
        e.preventDefault();
        const id = $(this).data('id');

        // Confirmación antes de eliminar usando SweetAlert
        mostrarAlerta({
            titulo: 'Eliminar Cliente',
            texto: '¿Deseas eliminar este cliente?. Esta acción no se puede deshacer.',
            tipo: 'warning'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/clientes/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        tabla.ajax.reload();
                        mostrarNotificacion({ mensaje: 'Cliente eliminado correctamente', tipo: 'success' });
                    },
                    error: manejarErrorAJAX
                });
            }
        }); // Fin mostrarAlerta
    }) // Fin de $(document).on('click', '.btn-eliminar', ...

    // Envío de formularios de creación y edición
    $('form[data-mode]').on('submit', function (e) {
        e.preventDefault();
        console.log('Script form[data-mode] - Envío de formularios de creación y edición')

        const form = $(this);
        const mode = form.data('mode');
        const url = form.attr('action');
        const method = 'POST'; // Siempre usamos POST y manejamos PUT con _method
        const extraData = mode === 'editar' ? { _method: 'PUT' } : {};
        const data = form.serialize() + '&' + $.param(extraData);

        $.ajax({
            url: url,
            method: method, //POST
            data: data,

            beforeSend: function () {
            console.log('Enviando AJAX con método:', method);
            },


            //Nuevo método opcional de notificación: Usando las funciones de notificación y manejo de errores
            success: function (response) {
            console.log('Respuesta:', response);

                const modalId = mode === 'editar' ? '#modal-editar' : '#modal-crear';
                $(modalId).removeClass('opacity-100').addClass('opacity-0');
                setTimeout(() => $(modalId).addClass('hidden'), 300);

                tabla.ajax.reload();
                mostrarNotificacion({ mensaje: response.mensaje, tipo: 'success' });
            },
            error: manejarErrorAJAX

        }); // Fin de AJAX
    }); // Fin de form[data-mode]
}); // Fin del document.ready
</script>
@endsection
