// resources/js/modalFunctions.js

/** Abre un modal según el botón disparador */
export function abrirModal(trigger) {
  const btnId    = trigger.id;
  const modalId  = btnId === 'btn-crear' ? 'modal-crear' : 'modal-editar';
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

/** Cierra el modal en cuyo interior está el botón disparador */
export function cerrarModal(trigger) {
  const $modal   = $(trigger).closest('.fixed');
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
