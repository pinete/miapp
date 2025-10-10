@php
  $modo = $modo ?? 'editar'; // por defecto
  $formId = 'form-' . $modo;
  $camposVisiblesId = 'campos-visibles-' . $modo;
  $camposOcultosId = 'campos-ocultos-' . $modo;
@endphp

<div id="modal-{{ $modo }}" class="fixed inset-0 z-10 hidden flex items-center justify-center">
  <!-- Fondo gris semitransparente -->
  <div id="modal-{{ $modo }}-fondo" class="modal-fondo absolute inset-0 bg-gray-800 bg-opacity-80 backdrop-blur-sm z-40"></div>

  <!-- Contenido fondo blanco opaco -->
  <div id="modal-{{ $modo }}-content" class="modal-content relative bg-white rounded-lg shadow-lg w-full max-w-md p-6 mx-auto z-50 transition transform scale-95 opacity-0">
    <h2 class="text-xl font-bold mb-4">
      {{ ucfirst($modo) }} {{ ucfirst($entidad) }}
    </h2>

    <form 
      id="{{ $formId }}" 
      method="POST" 
      data-mode="{{ $modo }}" 
      data-entidad={{$entidad}}
      action="{{ $modo === 'crear' ? $storeRoute : '' }}">

      @csrf

      <!-- Contenedor para campos visibles -->
      <div id="{{ $camposVisiblesId }}" class="space-y-2"></div>

      <!-- Contenedor para campos ocultos -->
      <div id="{{ $camposOcultosId }}" class="mt-4 bg-gray-100 p-3 rounded-md border border-gray-300 flex flex-wrap gap-4 text-sm"></div>

      <!-- Botones -->
      <div class="flex justify-end gap-2 mt-4">
        <button type="button" id="btn-cerrar-modal-{{ $modo }}" class="btn-close-modal px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
        <button type="submit" id="btn-guardar-modal-{{ $modo }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-400">Guardar</button>
      </div>
    </form>

    <button id="btn-cerrar-modal-{{ $modo }}-icono" class="btn-close-modal absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
  </div>
</div>