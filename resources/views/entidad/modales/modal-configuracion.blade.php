<div id="modal-configuracion" class="fixed inset-0 z-50 hidden flex items-center justify-center">
  <!-- Fondo -->
  <div class="absolute inset-0 bg-gray-800 bg-opacity-60 backdrop-blur-sm"></div>

  <!-- Contenido -->
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative z-10">
    <h2 class="text-xl font-bold mb-4">Configuración de Modales</h2>

    <form id="form-configuracion" class="space-y-4">
      <!-- Selector de entidad -->
      <div>
        <label for="select-entidad" class="block font-medium mb-1">Entidad</label>
        <select id="select-entidad" class="w-full border rounded p-2 bg-white">
          <option value="">Selecciona una entidad</option>
          <!-- JS poblará las opciones -->
        </select>
      </div>

      <!-- Selector de ancho -->
      <div>
        <label for="select-ancho" class="block font-medium mb-1">Ancho del modal</label>
        <select id="select-ancho" class="w-full border rounded p-2 bg-white">
          <option value="sm">Pequeño (sm)</option>
          <option value="md">Mediano (md)</option>
          <option value="lg">Grande (lg)</option>
          <option value="xl">Extra grande (xl)</option>
          <option value="2xl">Máximo (2xl)</option>
        </select>
      </div>

      <!-- Botones -->
      <div class="flex justify-end gap-2 pt-2">
        <button type="button" id="btn-cerrar-configuracion" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cerrar</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">Guardar</button>
      </div>
    </form>

    <!-- Icono cerrar -->
    <button id="btn-cerrar-configuracion-icono" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
  </div>
</div>

    <div id="modal-configuracion" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <!-- Fondo -->
    <div class="absolute inset-0 bg-gray-800 bg-opacity-60 backdrop-blur-sm"></div>

    <!-- Contenido -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative z-10">
        <h2 class="text-xl font-bold mb-4">Configuración de Modales</h2>

        <form id="form-configuracion" class="space-y-4">
        <!-- Selector de entidad -->
        <div>
            <label for="select-entidad" class="block font-medium mb-1">Entidad</label>
            <select id="select-entidad" class="w-full border rounded p-2 bg-white">
            <option value="">Selecciona una entidad</option>
            <!-- JS poblará las opciones -->
            </select>
        </div>

        <!-- Selector de ancho -->
        <div>
            <label for="select-ancho" class="block font-medium mb-1">Ancho del modal</label>
            <select id="select-ancho" class="w-full border rounded p-2 bg-white">
            <option value="sm">Pequeño (sm)</option>
            <option value="md">Mediano (md)</option>
            <option value="lg">Grande (lg)</option>
            <option value="xl">Extra grande (xl)</option>
            <option value="2xl">Máximo (2xl)</option>
            </select>
        </div>

        <!-- Botones -->
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" id="btn-cerrar-configuracion" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cerrar</button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">Guardar</button>
        </div>
        </form>

        <!-- Icono cerrar -->
        <button id="btn-cerrar-configuracion-icono" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
    </div>
</div>
