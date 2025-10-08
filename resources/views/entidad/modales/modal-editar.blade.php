<div id="modal-editar" class="fixed inset-0 z-10 hidden flex items-center justify-center">
    <!-- Fondo gris semitransparente -->
    <div id="modal-editar-fondo" class="modal-fondo absolute inset-0 bg-gray-800 bg-opacity-80 backdrop-blur-sm z-40"></div>
    <!-- Contenido fondo blanco opaco -->
    <div id="modal-editar-content" class="modal-content relative bg-white rounded-lg shadow-lg w-full max-w-md p-6 mx-auto z-50 transition transform scale-95 opacity-0">

        <h2 class="text-xl font-bold mb-4">Editar {{ ucfirst($entidad) }}</h2>
        <form id="form-editar" data-mode="editar" method="POST">
            @csrf
            @foreach ($campos as $campo)
                @php
                    $type = Str::contains($campo, 'email') ? 'email' : 'text';
                @endphp

                <label for="editar-{{ $campo }}" class="block font-medium mb-1">{{ ucfirst($campo) }}</label>
                <input type="{{ $type }}"
                    id="editar-{{ $campo }}"
                    name="{{ $campo }}"
                    class="w-full mb-3 p-2 border rounded"
                    placeholder="{{ ucfirst($campo) }}">         
            @endforeach

            <!-- Contenedor para los campos ocultos -->
            @if (!empty($camposOcultos))
                <!--
                <div id="campos-ocultos" class="mt-4 space-y-2 bg-color-green"></div>
                @foreach ($camposOcultos as $campo)
                    <label for="editar-{{ $campo }}" class="block font-medium mb-1">{{ ucfirst($campo) }}</label>
                    <input type="{{ $type }}"
                        id="editar-{{ $campo }}"
                        name="{{ $campo }}"
                        class="w-full mb-3 p-2 border rounded"
                        placeholder="{{ ucfirst($campo) }}">         
                @endforeach
                -->
                <div id="campos-ocultos"
                    class="mt-4 bg-gray-100 p-3 rounded-md border border-gray-300 flex flex-wrap gap-4 text-sm">
                    @foreach ($camposOcultos as $campo)
                        <div class="flex-1 min-w-[45%]">
                            <label for="editar-{{ $campo }}" class="block font-medium mb-1 text-gray-700 text-xs">
                                {{ ucfirst($campo) }}
                            </label>
                            <input type="{{ $type }}"
                                id="editar-{{ $campo }}"
                                name="{{ $campo }}"
                                class="w-full p-2 border border-gray-300 rounded text-sm"
                                placeholder="{{ ucfirst($campo) }}">
                        </div>
                    @endforeach
                </div>

            @endif
            <!-- Contenedor para los botones -->
            <div class="flex justify-end gap-2">
                <button type="button" id="btn-cerrar-modal-editar" class="btn-close-modal px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                <button type="submit" id="btn-guardar-modal-editar" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-400">Guardar</button>
            </div>
        </form>
        <button id="btn-cerrar-modal-editar-icono" class="btn-close-modal absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
    </div>
</div>
