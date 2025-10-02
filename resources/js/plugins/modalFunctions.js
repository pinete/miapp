// resources/js/modalFunctions.js

import { data } from "jquery";
import { AdjuntoManager } from './AdjuntoManager.js';


/** Abre un modal según el botón disparador */
export function abrirModal(trigger) {
    //const btnId    = trigger.id;
    const btnId = trigger.id || trigger.dataset.mode;
    console.log('btnId:', btnId);
    let modalId;
    switch (btnId) {
        case 'btn-crear':
            modalId= 'modal-crear';
            //console.log('ModalId:', modalId);
            break;
        case 'editar':
            modalId= 'modal-editar';
            //console.log('ModalId:', modalId);
            break;
        case 'adjuntar':
            modalId= 'modal-adjuntar';
            //console.log('ModalId:', modalId);
            break;
        default:
            console.error('ID de botón no reconocido:', btnId);
            break;
    }

  //console.log('Abriendo modal:', modalId);
  const $modal   = $('#' + modalId);
  const $fondo   = $modal.find('.modal-fondo');
  const $box     = $modal.find('.modal-content');

  // Mostrar el modal con animaciones
  $modal.removeClass('hidden');
  setTimeout(() => {
    $fondo.removeClass('opacity-0')
          .addClass('opacity-90 transition-opacity duration-600');
    $box.removeClass('scale-95 opacity-0')
        .addClass('scale-100 opacity-100 transition duration-300 ease-out');
  }, 10);

  // Si es el modal de adjuntar, rellenar campos y cargar tabla
  //if (modalId === 'modal-adjuntar') {
  //      _inicializarTablaAdjuntos(trigger, modalId, $modal);
  //  }
  if (modalId === 'modal-adjuntar') {
        const entidad = trigger.dataset.entidad;
        const id = trigger.dataset.id;

        if (entidad && id) {
            const $form = $modal.find('form');
            AdjuntoManager.fillForm($form, entidad, id);
            AdjuntoManager.initTable(entidad, id);
        }
    }

}

/**
 * Inicializa el DataTable de adjuntos dentro del modal
 * @param {HTMLElement} trigger - Botón que disparó el modal
 * @param {string} modalId - ID del modal abierto
 * @param {jQuery} $modal - Elemento jQuery del modal
 */
/*
function _inicializarTablaAdjuntos(trigger, modalId, $modal) {
    const entidad = trigger.dataset.entidad;
    const id = trigger.dataset.id;

    if (!entidad || !id) {
        console.warn('Faltan datos para cargar adjuntos: entidad o id');
        return;
    }

    const $form = $modal.find('form');
    $form.find('[name="entidad"]').val(entidad);
    $form.find('[name="id"]').val(id);

    const $tabla = $('#tablaAdjuntosModal');
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
            { data: 'tipo', title: 'Tipo' },
            { data: 'created_at', title: 'Fecha' },
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

*/


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
