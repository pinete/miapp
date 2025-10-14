/*  Listeners de gestión de los botones de acciones CRUD de entidad.Blade
    Usa funciones de:
        'resources/js/listeners/listenerConfModal.js' 
        'resources/js/plugins/alertas.js'
    importadas en app.js
*/
export function initEntidadListeners({ entidad, tableId, camposVisibles, camposOcultos }) {
  const tabla = $(`#${tableId}`).DataTable();

  // Abrir modal Crear / Editar
  $(document).on('click', '.btn-editar, .btn-crear', function (e) {
    e.preventDefault();
    this.id === 'btn-crear'
      ? vaciarModal(entidad, camposVisibles, camposOcultos)
      : rellenarModal($(this), entidad, camposVisibles, camposOcultos);
    abrirModal(this);
  });

  // Cerrar modal
  $(document).on('click', '.btn-close-modal', function () {
    cerrarModal(this);
  });

  // Eliminar registro
  $(document).on('click', '.btn-eliminar', function (e) {
    e.preventDefault();
    const id = $(this).data('id');
    mostrarAlerta({
      titulo: `Eliminar ${entidad}`,
      texto: '¿Deseas eliminar este registro? Esta acción no se puede deshacer.',
      tipo: 'warning'
    }).then(result => {
      if (result.isConfirmed) {
        $.ajax({
          url: `/${entidad}/${id}`,
          type: 'DELETE',
          data: { _token: $('meta[name="csrf-token"]').attr('content') },
          success: () => {
            tabla.ajax.reload();
            mostrarNotificacion({ mensaje: 'Registro eliminado correctamente', tipo: 'success' });
          },
          error: manejarErrorAJAX
        });
      }
    });
  });

  // Envío de formularios
  $('form[data-mode]').not('#formAdjunto').on('submit', function (e) {
    e.preventDefault();
    const form = $(this);
    const mode = form.data('mode');
    const url = form.attr('action');
    const extraData = mode === 'editar' ? { _method: 'PUT' } : {};
    const data = form.serialize() + '&' + $.param(extraData);

    $.ajax({
      url,
      method: 'POST',
      data,
      success: response => {
        tabla.ajax.reload();
        mostrarNotificacion({ mensaje: response.mensaje, tipo: 'success' });
        cerrarModal(form.find('button[type="submit"]')[0]);
      },
      error: manejarErrorAJAX
    });
  });

  // Adjuntar archivos
  $(document).on('click', '.btn-adjuntar', function (e) {
    e.preventDefault();
    $('#adjuntoId').val($(this).data('id'));
    $('#adjuntoEntidad').val($(this).data('entidad'));
    abrirModal(this);
  });

  $('#formAdjunto').on('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    $.ajax({
      url: '/adjuntos',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: () => {
        mostrarNotificacion({ mensaje: 'Adjunto subido correctamente', tipo: 'success' });
        cerrarModal($('#formAdjunto').find('button[type="submit"]')[0]);
      },
      error: manejarErrorAJAX
    });
  });

  // Expandir fila
  $(`#${tableId}`).on('click', '.btn-expand-row', function () {
    const $tr = $(this).closest('tr');
    const row = tabla.row($tr);
    const data = row.data();

    if (row.child.isShown()) {
      row.child.hide();
      $tr.removeClass('shown');
    } else {
      let html = `<div class="bg-gray-100 p-4 rounded-md border border-gray-300 grid grid-cols-2 gap-x-6 gap-y-2 text-sm">`;
      camposOcultos.forEach(campo => {
        const valor = data[campo] ?? '<i class="text-gray-400">Sin valor</i>';
        html += `<div class="font-semibold text-gray-700">${campo}</div><div class="text-gray-900">${valor}</div>`;
      });
      html += '</div>';
      row.child(html).show();
      $tr.addClass('shown');
    }
  });
}