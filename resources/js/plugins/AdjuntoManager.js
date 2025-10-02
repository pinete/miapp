// plugins/AdjuntoManager.js

/**
 * Inicializa la tabla de adjuntos para una entidad y registro
 * @param {string} entidad - Nombre de la entidad (clientes, proyectos, etc.)
 * @param {string|number} id - ID del registro
 * @param {string} selectorTabla - Selector del contenedor de la tabla
 */
function initTable(entidad, id, selectorTabla = '#tablaAdjuntosModal') {
    const $tabla = $(selectorTabla);

    if ($.fn.DataTable.isDataTable($tabla)) {
        $tabla.DataTable().destroy();
        $tabla.empty();
    }

    $tabla.DataTable({
        ajax: {
            url: '/adjuntos',
            data: { entidad, id }
        },
        columns: [
            { data: 'nombre', title: 'Nombre' },
            { data: 'mime', title: 'Tipo' },
            {
                data: 'created_at',
                title: 'Fecha',
                render: function (data) {
                    const fecha = new Date(data);
                    const dia = String(fecha.getDate()).padStart(2, '0');
                    const mes = String(fecha.getMonth() + 1).padStart(2, '0');
                    const año = fecha.getFullYear();
                    return `${dia}/${mes}/${año}`;
                }
            }
,
            {
                data: 'id',
                title: 'Acciones',
                render: id => `<button class="btn-borrar-adjunto" data-id="${id}">🗑️</button>`
            }
        ],
        dom: 't',
        paging: false,
        ordering: false,
        searching: false
    });
}

/**
 * Elimina un adjunto por ID
 * @param {string|number} id - ID del adjunto
 * @param {Function} onSuccess - Callback tras éxito
 */
async function deleteFile(id, onSuccess) {
    try {   /* ¿Por que hacerlo así.... EXPLICACIÓN:
            • 	En muchas aplicaciones web con Laravel, las vistas generadas por Laravel ya tiene el token CSRF vinculado
            a la sesión en esas vistas, y las peticiones se hacen desde formularios Blade o AJAX que heredan ese contexto.
            En cambio, los adjuntos se gestionan desde un modal que puede estar fuera del flujo principal, y el token no
            se sincroniza correctamente. Por eso, es necesario obtener el token CSRF vinculado a la sesión antes de hacer
            la petición DELETE.
                • 	El endpoint /sanctum/csrf-cookie  de Laravel Sanctum genera el token y lo vincula a la sesión.
                • 	Asegura que se envíen las cookies necesarias.
                • 	Luego, el token se extrae de la cookie y se envía en el encabezado.
            */
        // 1. Solicita el token CSRF vinculado a la sesión
        await fetch('/sanctum/csrf-cookie', { credentials: 'include' });

        // 2. Ejecuta la petición DELETE con el token activo
        $.ajax({
            url: `/adjuntos/${id}`,
            method: 'DELETE',
            //data: { _token: $('meta[name="csrf-token"]').attr('content') },
            headers: {
                'X-XSRF-TOKEN': getCookie('XSRF-TOKEN')
            },

            xhrFields: {
                withCredentials: true
            },

            success: () => {
                mostrarNotificacion({ mensaje: 'Adjunto eliminado', tipo: 'success' });
                if (typeof onSuccess === 'function') onSuccess();
            },
            error: manejarErrorAJAX
        });
    } catch (error) {
        mostrarNotificacion({ mensaje: 'Error al obtener el token CSRF', tipo: 'error' });
    }
}

/**
 * Obtiene el valor de una cookie por nombre
 * @param {} name
 * @returns
 */
function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? decodeURIComponent(match[2]) : null;
}


/**
 * Rellena los campos ocultos del formulario de adjuntos
 * @param {jQuery} $form - Formulario dentro del modal
 * @param {string} entidad
 * @param {string|number} id
 */
function fillForm($form, entidad, id) {
    $form.find('[name="entidad"]').val(entidad);
    $form.find('[name="id"]').val(id);
}

export const AdjuntoManager = {
    initTable,
    deleteFile,
    fillForm
};

// Listener para el botón de borrar adjunto
$(document).on('click', '.btn-borrar-adjunto', function () {
    const id = $(this).data('id');
    mostrarAlerta({
        titulo: 'Eliminar adjunto',
        texto: '¿Estás seguro de que deseas eliminar este archivo?',
        tipo: 'warning',
        txtConfirmar: 'Sí, eliminar',
        txtCancelar: 'Cancelar',
        mostrarBtnCancelar: true

    }).then(result => {
        if (result.isConfirmed) {
            AdjuntoManager.deleteFile(id, () => {
                $('#tablaAdjuntosModal').DataTable().ajax.reload();
            });
        }
    });
});
