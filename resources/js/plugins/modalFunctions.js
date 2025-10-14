// resources/js/modalFunctions.js

import { AdjuntoManager } from './AdjuntoManager.js';
import { aplicarAnchoModal } from './personalConfig.js';


/** Abre un modal según el botón disparador */
export function abrirModal(trigger) {
    const id = trigger.id || trigger.dataset.mode;
    //console.log('btnId:', btnId);
    let modalId;
    switch (id) {
        case 'btn-crear':
            modalId= 'modal-crear';
            break;
        case 'editar':
            modalId= 'modal-editar';
            break;
        case 'adjuntar':
            modalId= 'modal-adjuntar';
            break;
        default:
            console.error('ID de botón no reconocido:', btnId);
            break;
    }

  //console.log('Abriendo modal:', modalId);
  const $modal   = $('#' + modalId);
  const $fondo   = $modal.find('.modal-fondo');
  const $box     = $modal.find('.modal-content');

  // Detectar entidad y aplicar ancho
  const entidad = trigger.dataset.entidad;
  const modo = modalId.replace('modal-', ''); // 'crear', 'editar', 'adjuntar'
  if (entidad) {
    aplicarAnchoModal(entidad, modo);
  }

  // Mostrar el modal con animaciones
  $modal.removeClass('hidden');
  setTimeout(() => {
    $fondo.removeClass('opacity-0')
          .addClass('opacity-90 transition-opacity duration-600');
    $box.removeClass('scale-95 opacity-0')
        .addClass('scale-100 opacity-100 transition duration-300 ease-out');
  }, 10);

  // Si es el modal de adjuntar, rellenar campos y cargar tabla
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
 * Cierra el modal enviado o el modal en cuyo interior está el botón disparador
 * @param {*} trigger - El botón que disparó el cierre o el elemento formulario
 */
export function cerrarModal(trigger) {
    const $trigger = $(trigger);
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
export function rellenarModal(elem, entidad, camposVisibles, camposOcultos) {
  const id  = elem.data('id');
  //Capturamos los datos del registro de la entidad que coinciden con id
  $.get(`/${entidad}/${id}/json`, data => {
    $('#modal-id').val(data.id.value);
    $('#form-editar').attr('action', `/${entidad}/${data.id.value}`);

    crearHtmlCamposModal(data, camposVisibles, camposOcultos,'editar');
  });
}


/** Vacía los inputs del modal de creación */
export function vaciarModal(entidad, camposVisibles,camposOcultos) {

  $('#modal-id').val('');

  // Creamos la estructura json de un registro de la entidad con los campos vacios y con el type correspondiente a cada campo
  $.get(`/${entidad}/estructura`, data => {
      console.log('data: ', data);
      console.log('camposVisibles: ', camposVisibles);
      console.log('camposOcultos: ', camposOcultos);
      crearHtmlCamposModal(data, camposVisibles, camposOcultos, 'crear');
  });
}


/**
 * Genera el HTML de los campos del formulario modal (campos visibles y ocultos)
 * @param {Object} camposData - objeto con estructura { campo: { value, type } }
 * @param {Array} camposVisibles - lista de campos visibles
 * @param {Array} camposOcultos - lista de campos ocultos
 */
export function crearHtmlCamposModal(camposData, camposVisibles = [], camposOcultos = [], modo='editar') {
  const $visibles = $(`#campos-visibles-${modo}`);
  const $ocultos = $(`#campos-ocultos-${modo}`);

  $visibles.empty();
  $ocultos.empty(); // limpiar ocultos

  const renderCampo = (campo, destino) => {
    const { value, type } = camposData[campo] || { value: '', type: 'text' };
    let html;

    if (type === 'checkbox') {
      /* TRUCO: El comportamiento clásico de un checkbox en el formulario es no ser enviado al payload si no esta marcado.
                Al guardar, en la creación de nuevo registro, da error en la validación si no envia los checkbox no marcados.
                Por eso añado un input hidden (oculto) que se comportará de la sifuiente forma:
                  - Si el checkbox está desmarcado, el navegador no envía el checkbox, pero sí envía el hidden → campo=0.
                  - Si el checkbox está marcado, el navegador envía solo el checkbox → campo=1, y ignora el hidden.
                  - Laravel lo recibe como 1 o 0, y con 'nullable|boolean' en la validación, todo funciona.
      */
      html = `
        <div class="campo-generado mb-3">
          <!-- Este hidden garantiza que el campo se envíe como 0 si el checkbox está desmarcado -->
          <input type="hidden" name="${campo}" value="0">
          <label class="flex items-center space-x-2">
            <input type="checkbox" id="${modo}-${campo}" name="${campo}" value="1" ${value ? 'checked' : ''}>
            <span>${campo}</span>
          </label>
        </div>
      `;
    } else {
      //Nota: step="any" me permitirá introducir valores decimales en los campos con type number. Si no, solo va a permitir enteros
      html = `
        <div class="campo-generado mb-3">
          <label for="${modo}-${campo}" class="block font-medium mb-1">${campo}</label>
          <input type="${type}" step="any" id="${modo}-${campo}" name="${campo}" value="${value ?? ''}"
                 class="w-full p-2 border rounded" placeholder="${campo}">
        </div>
      `;
    }

    destino.append(html);
  };
  
  // Renderizamos campos visibles
  camposVisibles.forEach(campo => renderCampo(campo, $visibles));

  // Ocultar o no el contenedor si no hay campos ocultos
  if (camposOcultos.length === 0) {
    $ocultos.addClass('hidden'); // o .hide() si se prefiere inline
  } else {
    $ocultos.removeClass('hidden'); // mostrar si hay campos
    camposOcultos.forEach(campo => renderCampo(campo, $ocultos)); //Renderizamos campos ocultos
  }
}



