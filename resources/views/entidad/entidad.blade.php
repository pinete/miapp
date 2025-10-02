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
    @include('entidad.modales.modal-editar', ['entidad' => $entidad, 'campos' => $campos])

    <!-- Modal de creación -->
    @include('entidad.modales.modal-crear', ['entidad' => $entidad, 'campos'=>$campos, 'storeRoute' => $storeRoute])

    <!-- Modal adjuntar -->
    @include('entidad.modales.modal-adjuntos', ['entidad' => 'adjuntos'])
@endsection

@section('scripts')
    <script>
    $(document).ready(function () {
        const entidad = "{{ $entidad }}";
        const tablaId = "#{{ $tableId }}";
        const tabla = $(tablaId).DataTable();
        const campos = @json($campos);

        /***********  Manejo de modales para crear, editar y adjuntar  ***********/

        // Abrir modal al hacer clic en los botones de crear o editar
        $(document).on('click', '.btn-editar, .btn-crear', function (e) {
            e.preventDefault();
            const btnId = this.id;
            btnId === 'btn-crear'
                ? vaciarModal(campos)
                : rellenarModal($(this), entidad, campos);
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
                    cerrarModal(form.find('button[type="submit"]')[0]);

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

            console.log('FormData-token:', formData.get('_token'));

            // Comprobamos los datos del FormData en la consola
            // for (let pair of formData.entries()) {
            //     console.log('DATOS: ',pair[0] + ':', pair[1]);
            // }

            $.ajax({
                url: '/adjuntos',
                method: 'POST',
                data: formData, // + '&' + $.param(extraData),
                processData: false,
                contentType: false,
                success: function (response) {
                    //$('#modalAdjuntos').modal('hide');
                    //Swal.fire('¡Adjunto subido!', '', 'success');
                    mostrarNotificacion({ mensaje: 'Adjunto subido correctamente', tipo: 'success' });
                    cerrarModal(form.find('button[type="submit"]')[0]); // Usa el botón submit como trigger

                },
                error: manejarErrorAJAX
                //error: ()=>{
                //    console.error('Error en la subida:', xhr.responseText);
                //    Swal.fire('Error al subir el archivo', '', 'error');
                //}
            });
        });
    });
    </script>
@endsection

