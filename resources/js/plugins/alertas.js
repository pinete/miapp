/*
    Utilidades JavaScript para manejo de alertas, notificaciones y errores AJAX
    Requiere SweetAlert2 (https://sweetalert2.github.io/)
*/
import Swal from 'sweetalert2';


/** Mostrar alertas de confirmación
 *
 *@param {Object} options
 *@param {string} options.titulo - Título Titulo que encabeza el aviso. Por defecto es 'Confirmar acción'
 *@param {string} options.texto - Texto personalizado para la alerta. Por defecto vacío
 *@param {string} options.tipo - Tipo de alerta ('warning', 'error', 'success', 'info', 'question'). Por defecto es 'warning'
 *@param {string} options.txtConfirmar - Texto del botón de confirmación. Por defecto es 'Aceptar'
 *@param {string} options.txtCancelar - Texto del botón de cancelación. Por defecto es 'Cancelar'
 *@param {boolean} options.mostrarBtnCancelar - Indica si se debe mostrar el botón de cancelar (verdadero o falso). Por defecto es true
 *@returns {Promise} - Promise que se resuelve con el resultado de la alerta.
 */
export function mostrarAlerta({
    titulo = 'Confirmar acción',
    texto = '',
    tipo = 'warning',
    txtConfirmar = 'Aceptar',
    txtCancelar = 'Cancelar',
    mostrarBtnCancelar = true
}) {
    const mensaje = texto || `¿Estás seguro de que deseas continuar con este ${titulo.toLowerCase()}?`;

    return Swal.fire({
        title: titulo,
        text: mensaje,
        icon: tipo,
        showCancelButton: mostrarBtnCancelar,
        confirmButtonText: txtConfirmar,
        cancelButtonText: txtCancelar,
        reverseButtons: true
    });
}
// Ejemplo de uso:
// mostrarAlerta({ titulo: 'cliente', texto: '¿Deseas eliminar este cliente?', tipo: 'error' })
//     .then((result) => {
//         if (result.isConfirmed) {
//             // El usuario confirmó la acción
//             console.log('Acción confirmada');
//         } else if (result.dismiss === Swal.DismissReason.cancel) {
//             // El usuario canceló la acción
//             console.log('Acción cancelada');
//         }
//     });
//
// *********************************************************************************************************

/** Función para mostrar notificaciones tipo toast
 *  Muestra una notificación tipo toast en la esquina superior derecha  de la pantalla.
 *
 * @param {Object} options
 * @param {string} options.mensaje - Mensaje a mostrar en la notificación
 * @param {string} options.tipo - Tipo de notificación ('success', 'error', 'warning', 'info', 'question')
 * @param {number} options.duracion - Duración en milisegundos que la notificación estará visible
 * @return {void}
 */
export function mostrarNotificacion({
    mensaje = '',
    tipo = 'success',
    duracion = 3000
}) {
    console.log('mostrarNotificacion ejecutado con:', mensaje, tipo);
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: duracion,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    Toast.fire({
        icon: tipo,
        title: mensaje
    });
}

// Ejemplo de uso:
// mostrarNotificacion({ mensaje: 'Cliente creado con éxito', tipo: 'success' });
// mostrarNotificacion({ mensaje: 'Error al crear el cliente', tipo: 'error' });
// *********************************************************************************************************

/** Manejo errores de AJAX
 *
 * @param {*} jqXHR // Objeto jqXHR de la llamada AJAX. Se usa para obtener el código de estado HTTP
 * @param {*} textStatus // Estado textual de la llamada AJAX (timeout, error, abort, parsererror)
 * @param {*} errorThrown // Texto del error lanzado (opcional)
 * @returns {void}
 */
export function manejarErrorAJAX(jqXHR, textStatus, errorThrown) {
    let mensajeError = 'Ocurrió un error inesperado.';
        if (jqXHR.status === 422 && jqXHR.responseJSON?.errors) {
        mensajeError = Object.entries(jqXHR.responseJSON.errors)
            .map(([campo, msgs]) => `${campo}: ${msgs[0]}`)
            .join('\n');
    } else {
        switch (true) {
            case (jqXHR.status === 0):
                mensajeError = 'No hay conexión. Verifica tu red.';
                break;
            case (jqXHR.status >= 400 && jqXHR.status < 500):
                mensajeError = 'Error en la solicitud. Por favor, verifica los datos ingresados.';
                break;
            case (jqXHR.status >= 500):
                mensajeError = 'Error del servidor. Intenta nuevamente más tarde.';
                break;
            default:
                switch (textStatus) {
                    case 'parsererror':
                        mensajeError = 'Error al procesar la respuesta del servidor.';
                        break;
                    case 'timeout':
                        mensajeError = 'La solicitud ha excedido el tiempo de espera.';
                        break;
                    case 'abort':
                        mensajeError = 'La solicitud fue abortada.';
                        break;
                    default:
                        mensajeError = 'Ocurrió un error inesperado.';
                }
        }
    }
    mostrarNotificacion({ mensaje: mensajeError, tipo: 'error' });
}

// Ejemplo de uso en una llamada AJAX:
// $.ajax({
//     url: '/ruta/api',
//     method: 'GET',
//     success: function(data) {
//         console.log('Datos recibidos:', data);
//     },
//     error: manejarErrorAJAX
// });
// *********************************************************************************************************

/**  Validación básica de formularios (puedes expandirla según tus necesidades)
 *
 * @param {*} form - jQuery object del formulario a validar
 * @returns {boolean} - true si el formulario es válido, false si hay errores
 */
export function validarFormulario(form) {
    let esValido = true;
    form.find('input[required], select[required], textarea[required]').each(function() {
        if (!$(this).val()) {
            esValido = false;
            $(this).addClass('border-red-500');
        } else {
            $(this).removeClass('border-red-500');
        }
    });
    return esValido;
}

// Ejemplo de uso:
// const formulario = $('#miFormulario');
// if (validarFormulario(formulario)) {
//     console.log('Formulario válido, proceder con el envío.');
// } else {
//     mostrarNotificacion({ mensaje: 'Por favor, completa todos los campos requeridos.', tipo: 'warning' });
// }
// *********************************************************************************************************

// Debemos añadir estas funciones a resources/js/app.js para que Vite las reconozca
