<div id="modal-editar" class="fixed inset-0 bg-gray-800 bg-opacity- backdrop-blur-sm flex items-center justify-center z-50 hidden transition-opacity duration-600">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <h2 class="text-xl font-bold mb-4">Editar {{ ucfirst($entidad) }}</h2>
        <form id="form-editar" data-mode="editar" method="POST">
            @csrf
            @foreach ($campos as $campo)
                @php
                    $type = Str::contains($campo, 'email') ? 'email' : 'text';
                @endphp
                <input type="{{ $type }}"
                    id="editar-{{ $campo }}"
                    name="{{ $campo }}"
                    class="w-full mb-3 p-2 border rounded"
                    placeholder="{{ ucfirst($campo) }}">
            @endforeach
            <div class="flex justify-end gap-2">
                <button type="button" id="btn-cerrar-modal-editar" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-400">Guardar</button>
            </div>
        </form>
        <button id="btn-cerrar-modal-editar-icono" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
    </div>
</div>
