<!-- resources/views/components/modal-adjuntos.blade.php -->
<div id="modal-adjuntar" class="fixed inset-0 z-10 hidden flex items-center justify-center">
    <!-- Fondo gris semitransparente -->
    <div id="modal-crear-fondo" class="modal-fondo absolute inset-0 bg-gray-800 bg-opacity-80 backdrop-blur-sm z-40"></div>
    <!-- Contenido fondo blanco opaco -->
    <div id="modal-crear-content" class="modal-content relative bg-white rounded-lg shadow-lg w-full max-w-xl p-6 mx-auto z-50 transition transform scale-95 opacity-0">
        <!-- Tabla de adjuntos del registro actual con scroll -->
        <div class="mb-6 max-h-64 overflow-y-auto">
            <h3 class="text-lg font-semibold mb-2">Adjuntos existentes</h3>
            <table id="tablaAdjuntosModal" class="display w-full text-sm rounded overflow-hidden border border-gray-300 shadow-sm"></table>
        </div>

        <form
            id="formAdjunto"
            method ="POST"
            data-mode="adjuntar"
            action="{{ route('entidad.adjuntar') }}"
            enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="entidad" id="adjuntoEntidad">
            <input type="hidden" name="id" id="adjuntoId">
            <div class="modal-content">
                <div class="modal-header"><h3 class="text-lg text-center font-semibold mb-2">Adjuntar documento</h3></div>
                <div class="modal-body">

                    <input type="file"
                        id="docAdjunto"
                        name="archivo"
                        class="form-control bg-gray-200 w-full mb-3 p-2 border rounded">
                    <small class="text-gray-500">Tipos permitidos: pdf, doc, docx, txt, jpg, png. Máx 5MB.</small>
                </div>
                <div class="modal-footer flex justify-end gap-x-2">
                    <button type="button" id="btn-cerrar-adjuntar" class="btn-close-modal px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 bg-green-300 rounded hover:bg-green-400">Subir</button>
                </div>
            </div>
        </form>
    </div>
</div>

