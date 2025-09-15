@extends('layouts.app')

@section('content')
@php
    // Variables dinámicas
    $titulo = 'Listado de ' . ucfirst($entidad);
    $storeRoute = route('entidad.store', ['entidad' => $entidad]);
    $jsonRoute = url("/{$entidad}/:id/json");
    $updateRoute = url("/{$entidad}/:id");
    $deleteRoute = url("/{$entidad}/:id");
    $tableId = $entidad . '-table';
@endphp

<div class="flex justify-between items-center mb-4">
    <h1 class="text-3xl font-bold text-blue-600">{{ $titulo }}</h1>
    <button id="btn-crear" title="Nuevo registro" class="inline-block px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded hover:bg-green-700 active:scale-95 transform transition duration-100 ease-in-out mr-2 cursor-pointer" data-mode="crear">
        <img src="/icons/CRUD/Agregar-Icono.png" alt="Agregar" class="w-6 h-6 inline">
    </button>
</div>

{!! $dataTable->table(['id' => $tableId, 'class' => 'table table-bordered table-striped'], true) !!}
{!! $dataTable->scripts() !!}

<!-- Modal de edición -->
@include('entidad.modales.modal-editar', ['entidad' => $entidad])

<!-- Modal de creación -->
@include('entidad.modales.modal-crear', ['entidad' => $entidad, 'storeRoute' => $storeRoute])
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    const entidad = "{{ $entidad }}";
    const tablaId = "#{{ $tableId }}";
    const tabla = $(tablaId).DataTable();
    tabla.buttons().container().appendTo(tablaId + '_wrapper .col-md-6:eq(0)');

    // Abrir modal de edición
    $(document).on('click', '.btn-editar', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        $.get(`/${entidad}/${id}/json`, function (data) {
            $('#modal-id').val(data.id);
            $('#editar-nombre').val(data.nombre);
            $('#editar-email').val(data.email);
            $('#editar-telefono').val(data.telefono);
            $('#form-editar').attr('action', `/${entidad}/${data.id}`);
            $('#modal-editar').removeClass('hidden opacity-0').addClass('opacity-100 transition-opacity duration-600');
        });
    });

    // Abrir modal de creación
    $('#btn-crear').on('click', function () {
        $('#crear-nombre, #crear-email, #crear-telefono').val('');
        $('#modal-crear').removeClass('hidden opacity-0').addClass('opacity-100 transition-opacity duration-600');
    });

    // Cerrar modales
    $('#btn-cerrar-modal-editar, #btn-cerrar-modal-crear').on('click', function () {
        const modalId = $(this).closest('.fixed').attr('id');
        $('#' + modalId).removeClass('opacity-100').addClass('opacity-0');
        setTimeout(() => $('#' + modalId).addClass('hidden'), 300);
    });

    // Eliminar registro
    $(document).on('click', '.btn-eliminar', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        mostrarAlerta({
            titulo: 'Eliminar ' + entidad,
            texto: '¿Deseas eliminar este registro? Esta acción no se puede deshacer.',
            tipo: 'warning'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/${entidad}/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        tabla.ajax.reload();
                        mostrarNotificacion({ mensaje: 'Registro eliminado correctamente', tipo: 'success' });
                    },
                    error: manejarErrorAJAX
                });
            }
        });
    });

    // Envío de formularios
    $('form[data-mode]').on('submit', function (e) {
        e.preventDefault();
        const form = $(this);
        const mode = form.data('mode');
        const url = form.attr('action');
        const method = 'POST';
        const extraData = mode === 'editar' ? { _method: 'PUT' } : {};
        const data = form.serialize() + '&' + $.param(extraData);

        $.ajax({
            url: url,
            method: method,
            data: data,
            success: function (response) {
                const modalId = mode === 'editar' ? '#modal-editar' : '#modal-crear';
                $(modalId).removeClass('opacity-100').addClass('opacity-0');
                setTimeout(() => $(modalId).addClass('hidden'), 300);
                tabla.ajax.reload();
                mostrarNotificacion({ mensaje: response.mensaje, tipo: 'success' });
            },
            error: manejarErrorAJAX
        });
    });
});
</script>
@endsection

