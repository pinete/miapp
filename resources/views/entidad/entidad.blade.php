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
    $(document).ready(function () {
        const entidad = "{{ $entidad }}";
        const tablaId = "#{{ $tableId }}";
        const tabla = $(tablaId).DataTable();
        const camposVisibles = @json($campos);
        //console.log('campos: ', camposVisibles);
        const camposOcultos = @json($camposOcultos); 
        //console.log('camposOcultos: ', camposOcultos);

        /***********  Manejo de modales para crear, editar y adjuntar  ***********/

        // Abrir modal al hacer clic en los botones de crear o editar
        $(document).on('click', '.btn-editar, .btn-crear', function (e) {
            e.preventDefault();
            const btnId = this.id;
            btnId === 'btn-crear'
                ? vaciarModal(entidad, camposVisibles, camposOcultos)
                : rellenarModal($(this), entidad, camposVisibles, camposOcultos);
            abrirModal(this); // Con 'this' pasamos el botón clickeado
        });


        // Cerrar modal al hacer clic en la 'x' o en el botón de cancelar (los que tienen clase .btn-close-modal)
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

        // Envío de formularios (crear y editar)
        $('form[data-mode]').not('#formAdjunto').on('submit', function (e) {
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
                    cerrarModal(form.find('button[type="submit"]')[0]); // Usa el botón submit como trigger

                },
                error: manejarErrorAJAX
            });
        });

        // Abrir modal Adjuntar archivos
        $(document).on('click', '.btn-adjuntar', function (e) {
            e.preventDefault();
            $('#adjuntoId').val($(this).data('id'));
            $('#adjuntoEntidad').val($(this).data('entidad'));
            console.log('Entidad:', $('#adjuntoEntidad').val());
            console.log('ID:', $('#adjuntoId').val());
            abrirModal(this);
        });

        // Envío del formulario de adjuntos
        $('#formAdjunto').on('submit', function (e) {
            e.preventDefault();
            const form = $(this);
            const mode = $('#formAdjunto').data('mode');
            const formData = new FormData(this);

            $.ajax({
                url: '/adjuntos',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    mostrarNotificacion({ mensaje: 'Adjunto subido correctamente', tipo: 'success' });
                    cerrarModal(form.find('button[type="submit"]')[0]); // Usa el botón submit como trigger

                },
                error: manejarErrorAJAX
            });
        });

        // Listener de botón expandir
        $('#{{ $entidad }}-table').on('click', '.btn-expand-row', function () {
            const $btn = $(this);
            const $tr = $btn.closest('tr'); // busca el elemento <tr> más cercano hacia arriba en el árbol DOM, partiendo desde el elemento actual.
            const table = $('#{{ $entidad }}-table').DataTable(); // Inicializa o recupera la instancia de DataTables asociada al elemento HTML con ID {{ $entidad }}-table.
            const row = table.row($tr);
            const data = row.data();
            const ocultos = table.ajax.json().camposOcultos;
           
            // Controlamos la acción al pulsar el boton expandir
            if (row.child.isShown()) { // Si esta mostrando datos ocultos -> Ocultar los datos
                row.child.hide();
                $tr.removeClass('shown');
            } else { // Si no muestra los datos ocultos -> Mostrar los datos
                
                /* 
                // Versión anterior mas simple
                let html = '<table class="w-full text-sm text-left">';
                ocultos.forEach(campo => {
                    html += `<tr><td class="font-semibold pr-4">${campo}</td><td>${data[campo] ?? '<i class="text-gray-400">Sin valor</i>'}</td></tr>`;
                });
                html += '</table>';
                */

                // Versión con presentación de datos mejorada
                let html = `<div class="bg-gray-100 p-4 rounded-md border border-gray-300 grid grid-cols-2 gap-x-6 gap-y-2 text-sm">`;

                ocultos.forEach(campo => {
                const valor = data[campo] ?? '<i class="text-gray-400">Sin valor</i>';
                html += `
                    <div class="font-semibold text-gray-700">${campo}</div>
                    <div class="text-gray-900">${valor}</div>
                `;
                });

                html += '</div>';
                
                // Incorporamos y mostramos el html al DOM
                row.child(html).show();
                $tr.addClass('shown');
            }
        });
    });
    </script>
@endsection

