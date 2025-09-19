<div id="modal-crear" class="fixed inset-0 z-10 hidden flex items-center justify-center">
    <!-- Fondo gris semitransparente -->
    <div id="modal-crear-fondo" class="modal-fondo absolute inset-0 bg-gray-800 bg-opacity-80 backdrop-blur-sm z-40"></div>
    <!-- Contenido fondo blanco opaco -->
    <div id="modal-crear-content" class="modal-content relative bg-white rounded-lg shadow-lg w-full max-w-md p-6 mx-auto z-50 transition transform scale-95 opacity-0">

    <form id="form-crear" method="POST" data-mode="crear" action="{{ $storeRoute }}" class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
        @csrf
        <h2 class="text-xl font-bold mb-4">Nuevo {{ ucfirst(Str::singular($entidad)) }}</h2>
            @foreach ($campos as $campo)
                @php
                    $type = Str::contains($campo, 'email') ? 'email' : 'text';
                @endphp

                <input type="{{ $type }}"
                    name="{{ $campo }}"
                    id="crear-{{ $campo }}"
                    placeholder="{{ ucfirst($campo) }}"
                    class="w-full mb-3 p-2 border rounded">
            @endforeach

        <div class="flex justify-end gap-2 mt-4">
            <button type="button" id="btn-cerrar-modal-crear" class="btn-close-modal px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
            <button type="submit" id="btn-guardar-modal-crear" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-400">Guardar</button>
        </div>
    </form>
    <button id="btn-cerrar-modal-crear-icono" class="btn-close-modal absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
    </div>
</div>
