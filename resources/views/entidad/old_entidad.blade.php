@extends('layouts.app')

@section('content')
    @php
        // Variables dinámicas
        $titulo = 'Listado de ' . ucfirst($entidad); // Título de la página
        $storeRoute = route('entidad.store', ['entidad' => $entidad]); // Ruta para crear nuevo registro
        $jsonRoute = url("/{$entidad}/:id/json"); // Ruta para obtener datos en JSON
        $updateRoute = url("/{$entidad}/:id"); // Ruta para actualizar registro
        $deleteRoute = url("/{$entidad}/:id"); // Ruta para eliminar registro
        $tableId = $entidad . '-table'; // ID único para la tabla
    @endphp

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold text-blue-600">{{ $titulo }}</h1>
        <button id="btn-crear" title="Nuevo registro" class="btn-crear inline-block px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded hover:bg-green-700 active:scale-95 transform transition duration-100 ease-in-out mr-2 cursor-pointer" data-mode="crear">
            <img src="/icons/CRUD/Agregar-Icono.png" alt="Agregar" class="w-6 h-6 inline">
        </button>
    </div>

    {!! $dataTable->table(['id' => $tableId, 'class' => 'table table-auto table-bordered table-striped'], true) !!}
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

        /* Manejo de modales para crear y editar */

        // Abrir modal al hacer clic en los botones de crear o editar
        $(document).on('click', '.btn-editar, .btn-crear', function (e) {
            e.preventDefault();
            const btnId = this.id;
            btnId === 'btn-crear' ? vaciarModal() :  rellenarModal($(this));
            abrirModal(this); // Con 'this' pasamos el botón clickeado
        });

        /** Función para abrir el modal dinámicamente
         * @param {HTMLElement} trigger - El botón que disparó la apertura del modal (crear o editar).
         * Determina qué modal abrir según el ID del botón.
         */
        function abrirModal(trigger) {
            const btnId = trigger.id; // 'btn-crear' o 'btn-editar'
            const modalId = btnId === 'btn-crear' ? 'modal-crear' : 'modal-editar';
            const $modal = $('#' + modalId);
            const fondoId = $modal.find('.modal-fondo').attr('id');
            const contentId = $modal.find('.modal-content').attr('id');
            //console.log('ID del modal Padre:', modalId);
            //console.log('ID del fondo:', fondoId);
            //console.log('ID del contenido:', contentId);

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

        /** Función para cerrar el modal
         * @param {HTMLElement} trigger - El botón que disparó el cierre del modal (la 'x' o cancelar).
         * Encuentra el modal padre y aplica las animaciones de cierre.
         */
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

        /** Función para rellenar el modal de edición
         * @param {jQuery} elem - El botón de editar que disparó la apertura del modal.
         * Obtiene los datos del registro y los coloca en los inputs del modal.
         */
        function rellenarModal(elem) {
            const id = elem.data('id');
            const data = elem.data();
            // Cargar datos del registro a editar
            $.get(`/${entidad}/${id}/json`, function (data) {
                $('#modal-id').val(data.id);
                $('#form-editar').attr('action', `/${entidad}/${data.id}`);

                // Recorre todos los campos visibles definidos en Laravel y rellena los inputs
                if (typeof campos !== 'undefined' && Array.isArray(campos)) {
                    //console.log('Campos definidos:', campos);
                    campos.forEach(function (campo) {
                        const valor = data[campo] ?? '';
                        $(`#editar-${campo}`).val(valor);
                    });
                }
            });
        }

        /** Función para vaciar el modal de creación
         * Limpia todos los inputs del modal para un nuevo registro.
         */
        function vaciarModal() {
            // Vaciar los inputs del modal
            if (typeof campos !== 'undefined' && Array.isArray(campos)) {
                //console.log('Campos definidos:', campos);
                campos.forEach(function (campo) {
                    $(`#crear-${campo}`).val('');
                });
            }
        }

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

