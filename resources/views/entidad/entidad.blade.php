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
    <button id="btn-crear" title="Nuevo registro" class="btn-crear inline-block px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded hover:bg-green-700 active:scale-95 transform transition duration-100 ease-in-out mr-2 cursor-pointer" data-mode="crear">
        <img src="/icons/CRUD/Agregar-Icono.png" alt="Agregar" class="w-6 h-6 inline">
    </button>
</div>

{!! $dataTable->table(['id' => $tableId, 'class' => 'table table-bordered table-striped'], true) !!}
{!! $dataTable->scripts() !!}

<!-- Modal de edición -->
@include('entidad.modales.modal-editar', ['entidad' => $entidad, 'campos' => $campos])

<!-- Modal de creación -->
@include('entidad.modales.modal-crear', ['entidad' => $entidad, 'campos'=>$campos, 'storeRoute' => $storeRoute])
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    const entidad = "{{ $entidad }}";
    const tablaId = "#{{ $tableId }}";
    const tabla = $(tablaId).DataTable();
    const campos = @json($campos);
    tabla.buttons().container().appendTo(tablaId + '_wrapper .col-md-6:eq(0)');

    // Evento para abrir modal desde cualquier botón con clase .btn-editar o .btn-crear
    $(document).on('click', '.btn-editar, .btn-crear', function (e) {
        e.preventDefault();
        const btnId = this.id;
        btnId === 'btn-crear' ? vaciarModal() :  rellenarModal($(this));
        abrirModal(this); // Con 'this' pasamos el botón clickeado
    });

    // Función para abrir el modal dinámicamente
    function abrirModal(trigger) {
        const btnId = trigger.id; // 'btn-crear' o 'btn-editar'
        const modalId = btnId === 'btn-crear' ? 'modal-crear' : 'modal-editar';
        const $modal = $('#' + modalId);
        const fondoId = $modal.find('.modal-fondo').attr('id');
        const contentId = $modal.find('.modal-content').attr('id');
        console.log('ID del modal Padre:', modalId);
        console.log('ID del fondo:', fondoId);
        console.log('ID del contenido:', contentId);

        $('#' + modalId).removeClass('hidden');
        setTimeout(() => {
            $('#' + fondoId)
                .removeClass('opacity-0')
                .addClass('opacity-90 transition-opacity duration-600');
            $('#' + contentId)
                .removeClass('transform scale-95 opacity-0')
                .addClass('transform scale-100 opacity-100 transition duration-300 ease-out');
        }, 10);
    }

    // Función para cerrar el modal
    function cerrarModal(trigger) {

        const $modal    = $(trigger).closest('.fixed');          // Encuentra el modal padre
        const $fondo    = $modal.find('.modal-fondo');           // Su fondo
        const $contenido= $modal.find('.modal-content');         // Su caja blanca

        // Animación de cierre
        $fondo
            .removeClass('opacity-90 transition-opacity duration-600')
            .addClass('opacity-0');

        $contenido
            .removeClass('scale-100 opacity-100 transition duration-300 ease-out')
            .addClass('scale-95 opacity-0');

        // Al ocultarse el fondo y el contenido...
        setTimeout(() => $modal.addClass('hidden'), 300);
    }

    // Función para rellenar el modal de edición
    function rellenarModal(elem) {
         const id = elem.data('id');
         const data = elem.data();
        // Cargar datos del registro a editar
        $.get(`/${entidad}/${id}/json`, function (data) {
            $('#modal-id').val(data.id);
            $('#form-editar').attr('action', `/${entidad}/${data.id}`);

            // Recorre todos los campos visibles definidos en Laravel y rellena los inputs
            if (typeof campos !== 'undefined' && Array.isArray(campos)) {
                console.log('Campos definidos:', campos);
                campos.forEach(function (campo) {
                    const valor = data[campo] ?? '';
                    $(`#editar-${campo}`).val(valor);
                });
            }
        });
    }

    // Función para vaciar el modal de creación
    function vaciarModal() {
        // Vaciar los inputs del modal
        if (typeof campos !== 'undefined' && Array.isArray(campos)) {
            console.log('Campos definidos:', campos);
            campos.forEach(function (campo) {
                $(`#crear-${campo}`).val('');
            });
        }
    }


    /*
    // Abrir modal de edición
    $(document).on('click', '.btn-editar', function (e) {
        e.preventDefault();
        const id = $(this).data('id');

        // Cargar datos del registro a editar
        $.get(`/${entidad}/${id}/json`, function (data) {
            $('#modal-id').val(data.id);
            $('#form-editar').attr('action', `/${entidad}/${data.id}`);

            // Recorre todos los campos visibles definidos en Laravel y rellena los inputs
            if (typeof campos !== 'undefined' && Array.isArray(campos)) {
                console.log('Campos definidos:', campos);
                campos.forEach(function (campo) {
                    const valor = data[campo] ?? '';
                    $(`#editar-${campo}`).val(valor);
                });
            };

            $('#modal-editar').removeClass('hidden');
            setTimeout(() => {
                $('#modal-editar-fondo')
                    .removeClass('opacity-0')
                    .addClass('opacity-90 transition-opacity duration-600');
                $('#modal-editar-content')
                    .removeClass('transform scale-95 opacity-0')
                    .addClass('transform scale-100 opacity-100 transition duration-300 ease-out');
            }, 10);
        });
    });
    */

    /*
    // Abrir modal de creación
    $('#btn-crear').on('click', function () {
        //$('#crear-nombre, #crear-email, #crear-telefono').val('');
        //$('#modal-crear').removeClass('hidden opacity-0').addClass('opacity-100 transition-opacity duration-600');
    });
    */


    // Cerrar modales
    //$('#btn-cerrar-modal-editar, #btn-cerrar-modal-crear').on('click', cerrarModal);
    //$('#btn-cerrar-modal-editar-icono, #btn-cerrar-modal-crear-icono').on('click', cerrarModal);
    $(document).on('click', '.btn-close-modal', function () {
        cerrarModal(this);
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
                tabla.ajax.reload(); // Recargar la tabla
                mostrarNotificacion({ mensaje: response.mensaje, tipo: 'success' });
                cerrarModal(form.find('button[type="submit"]')[0]);
            },
            error: manejarErrorAJAX
        });
    });
});
</script>
@endsection

