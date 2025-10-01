<!-- resources/views/components/modal-adjuntos.blade.php -->
<div id="modalAdjuntos" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <form id="formAdjunto" enctype="multipart/form-data">
      <input type="hidden" name="entidad" id="adjuntoEntidad">
      <input type="hidden" name="id" id="adjuntoId">
      <div class="modal-content">
        <div class="modal-header"><h5>Adjuntar archivo</h5></div>
        <div class="modal-body">
          <input type="file" name="archivo" class="form-control" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Subir</button>
        </div>
      </div>
    </form>
  </div>
</div>

