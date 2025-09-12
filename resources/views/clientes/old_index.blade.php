@extends('layouts.app')
@section('content')

<!-- Usamos TailWind para mostrar datos en tabla -->

<div class="flex justify-between items-center mb-4">
    {{-- Título --}}
    <h1 class="text-3xl font-bold text-blue-600">Listado de Clientes</h1>

    {{-- Botón para crear un nuevo cliente --}}
    <button id="btn-crear" title="Nuevo registro" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transform transition duration-100 ease-in-out mr-2 cursor-pointer" data-mode="crear">
        <img src="/icons/CRUD/Agregar-Icono.png" alt="Editar" class="w-6 h-6 inline">
    </button>
</div>

{{-- --}}
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
            @csrf   {{-- Cuando usas @crfs dentro de un formulario Blade, Laravel genera automáticamente un campo oculto como este:
                        <input type="hidden" name="_token" value="TOKEN_GENERADO">
                        Ese token es único para cada sesión de usuario y Laravel lo verifica en cada petición POST, PUT, PACH o DELETE.
                        Si el token no está presente o es incorrecto, Laravel rechaza la petición por considerarla potencialmente maliciosa.
                        Sirve para proteger tus formularios contra ataques CSRF (Cross-Site Request Forgery) o “falsificación de petición
                        en sitios cruzados”
                    --}}

            {{-- @method('PUT') {{-- Ya no es necesario aquí, lo manejamos en JS (ver "//Envío de formularios de creación y edición") --}}

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
                {{--<button type="button" id="btn-cerrar-modal-editar" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>--}}
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

// Inicialización de DataTables con procesamiento del lado del servidor
console.log('Script embebido cargado');

$(document).ready(function () {
    console.log('Scripts dentro de Document.ready')

    const tabla = $('#clientes-table').DataTable({
        processing: true,
        serverSide: true,

        ajax: {
            url: "{{ route('clientes.data') }}",
            type: 'GET',
            dataSrc: function (json) {
                console.log('Respuesta JSON:', json);
                return json.data;
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'nombre', name: 'nombre' },
            { data: 'email', name: 'email' },
            { data: 'telefono', name: 'telefono' },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]
    }); // Cierre de clientes-table

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
    }); // Cierre de btn-editar


    // Abrir modal de creación
    $('#btn-crear').on('click', function () {
        console.log('Script btn-crear - Abrir modal de creación')
        $('#crear-nombre, #crear-email, #crear-telefono').val('');
        $('#modal-crear')
            .removeClass('hidden opacity-0')
            .addClass('opacity-100 transition-opacity duration-600');
    }); // Cierre btn-crear

    // Cerrar modal de edición
    $('#btn-cerrar-modal-editar').on('click', function () {
        console.log('script btn-cerrar-modal-editar de Cerrar Modales')
        $('#modal-editar').removeClass('opacity-100').addClass('opacity-0');
        setTimeout(() => $('#modal-editar').addClass('hidden'), 300);
    }); // Cierre btn-cerrar-modal-editar

    // Cerrar modal de creación
    $('#btn-cerrar-modal-crear').on('click', function () {
         console.log('Script btn-cerrar-modal-crear de Cerrar Modales')
        $('#modal-crear').removeClass('opacity-100').addClass('opacity-0');
        setTimeout(() => $('#modal-crear').addClass('hidden'), 300);
    }); // Cierre btn-cerrar-modal-crear



    // Manejo del botón de eliminación de un cliente
    $(document).on('click', '.btn-eliminar', function (e) {
         console.log('Script btn-eliminar - Manejo del botón de eliminación de un cliente')
        e.preventDefault();
        const id = $(this).data('id');

        // Confirmación antes de eliminar sin usar SweetAlert
        /*
        if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
            $.ajax({
                url: `/clientes/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function () {
                    tabla.ajax.reload();
                    alert('Cliente eliminado correctamente');
                },
                error: function () {
                    alert('Error al eliminar el cliente');
                }
            });
        }
        */
        // Confirmación antes de eliminar usando SweetAlert
        mostrarAlerta({
            nombreObjeto: 'cliente',
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
        }); // Cerramos mostrarAlerta
    }) // Cierre de $(document).on('click', '.btn-eliminar', ...

    // Envío de formularios de creación y edición
    $('form[data-mode]').on('submit', function (e) {
        e.preventDefault();
        console.log('Script form[data-mode] - Envío de formularios de creación y edición')
        //  Decidimos el metodo PUT o POST en base al valor de data-mode que se envía desde el
        //  formulario (editar o crear) activado por el botón. Esto nos permite reutilizar el mismo código
        //  para los dos modales
        const form = $(this);
        const mode = form.data('mode');
        const url = form.attr('action');
        //const method = mode === 'editar' ? 'PUT' : 'POST';
        const method = 'POST'; // Siempre usamos POST y manejamos PUT con _method
        const extraData = mode === 'editar' ? { _method: 'PUT' } : {};
        /*
        Esto usa el método POST en ambos casos, pero Laravel lo interpretará como PUT si le pasas _method=PUT, gracias a su sistema de spoofing de métodos.
        ¿Por qué esto funciona?
        Laravel permite enviar PUT, PATCH, o DELETE como un campo oculto (_method) en formularios . Así evitas problemas con navegadores o jQuery que no a
        manejan bien métodos HTTP distintos de GET/POST.
        Sustituye la línea comentada en el modal que usaba @method('PUT') en el formulario, lo cual no es necesario si manejas todo con JavaScript.
        */
        const data = form.serialize() + '&' + $.param(extraData);

        console.log('Modo:', mode);
        console.log('URL:', url);
        console.log('Método:', method);
        console.log('data:', data);


        $.ajax({
            url: url,
            method: method, //POST
            data: data,
            /*
            // Opcional: Método anterior. Notificación visual sin usar mostrarNotificacion() ni manejarErrorAJAX()
            success: function () {

                const modalId = mode === 'editar' ? '#modal-editar' : '#modal-crear';
                $(modalId).removeClass('opacity-100').addClass('opacity-0');
                setTimeout(() => $(modalId).addClass('hidden'), 300);

                tabla.ajax.reload();


                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: mode === 'editar' ? 'Cliente actualizado' : 'Cliente creado',
                        text: 'La operación se realizó correctamente',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    alert('Operación realizada correctamente');
                }
                    error: function (xhr) {
                const errors = xhr.responseJSON?.errors;
                let mensaje = 'Error al guardar el cliente';
                if (errors) {
                    mensaje = Object.entries(errors)
                        .map(([campo, msgs]) => `${campo}: ${msgs[0]}`)
                        .join('\n');
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: mensaje
                    });
                } else {
                    alert(mensaje);
                }
            }
            */
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
            /*  NOTA:¿manejarErrorAJAX no es una función? Por qué no la llamamos con parentesis?
                Porque la pasamos como referencia. jQuery la llamará automáticamente cuando ocurra un error en la petición AJAX,
                pasandole los argumentos esperados (jqXHR, textStatus y errorThrow).
                Si usamos paréntesis (manejarErrorAJAX()), la función se ejecutaría inmediatamente y no cuando ocurra el error.

                ¿Y si se necesita lógica adicional?
                Si quisieras hacer algo más antes o después de llamar a manejarErrorAJAX, entonces sí usarías una función anónima:
                error:  function (jqXHR, textStatus, errorThrown) {
                            console.log('Error detectado');
                            manejarErrorAJAX(jqXHR, textStatus, errorThrown);
                }
                Pero si manejarErrorAJAX ya hace todo lo que se necesita, podemos pasarla directamente como referencia
            */
        }); // Cierre de AJAX
    }); // Cierre de form[data-mode]
}); // Cierre del document.ready
</script>
@endsection



