// resources/js/modalFunctions.js

import { data } from "jquery";

/** Abre un modal según el botón disparador */
export function abrirModal(trigger) {
    //const btnId    = trigger.id;
    const btnId = trigger.id || trigger.dataset.mode;
    console.log('btnId:', btnId);
    let modalId;
    switch (btnId) {
        case 'btn-crear':
            modalId= 'modal-crear';
            console.log('ModalId:', modalId);
            break;
        case 'editar':
            modalId= 'modal-editar';
            console.log('ModalId:', modalId);
            break;
        case 'adjuntar':
            modalId= 'modal-adjuntar';
            console.log('ModalId:', modalId);
            break;
        default:
            console.error('ID de botón no reconocido:', btnId);
            break;
    }
  //const modalId  = btnId === 'btn-crear' ? 'modal-crear' : 'modal-editar';
  console.log('Abriendo modal:', modalId);
  const $modal   = $('#' + modalId);
  const $fondo   = $modal.find('.modal-fondo');
  const $box     = $modal.find('.modal-content');

  $modal.removeClass('hidden');
  setTimeout(() => {
    $fondo.removeClass('opacity-0')
          .addClass('opacity-90 transition-opacity duration-600');
    $box.removeClass('scale-95 opacity-0')
        .addClass('scale-100 opacity-100 transition duration-300 ease-out');
  }, 10);
}

/** Cierra el modal enviado o el modal en cuyo interior está el botón disparador
 * @param {*} trigger - El botón que disparó el cierre o el formulario mismo
*/
export function cerrarModal(trigger) {
    const $trigger = $(trigger);
    //const $modal   = $(trigger).closest('.fixed');
    // Para que sirva tanto si el trigger es un botón dentro del modal como si es el formulario
    const $modal = $trigger.closest('.fixed').length
        ? $trigger.closest('.fixed') // Si el trigger es un botón dentro del modal
        : $trigger.find('.fixed'); // Si el trigger es el formulario

    const $fondo   = $modal.find('.modal-fondo');
    const $box     = $modal.find('.modal-content');

    $fondo.removeClass('opacity-90 transition-opacity duration-600')
            .addClass('opacity-0');
    $box.removeClass('scale-100 opacity-100 transition duration-300 ease-out')
        .addClass('scale-95 opacity-0');

    setTimeout(() => $modal.addClass('hidden'), 300);
}

/** Rellena el formulario de edición con datos AJAX */
export function rellenarModal(elem, entidad, campos) {
  const id  = elem.data('id');
  $.get(`/${entidad}/${id}/json`, data => {
    $('#modal-id').val(data.id);
    $('#form-editar').attr('action', `/${entidad}/${data.id}`);
    campos.forEach(campo => {
      $(`#editar-${campo}`).val(data[campo] || '');
    });
  });
}

/** Vacía los inputs del modal de creación */
export function vaciarModal(campos) {
  campos.forEach(campo => {
    $(`#crear-${campo}`).val('');
  });
}
