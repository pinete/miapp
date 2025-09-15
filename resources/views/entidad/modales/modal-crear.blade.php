<div id="modal-crear" class="fixed inset-0 bg-gray-800 bg-opacity-30 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-opacity duration-300">
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
            <button type="button" id="btn-cerrar-modal-crear" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-400">Guardar</button>
        </div>
    </form>
</div>
