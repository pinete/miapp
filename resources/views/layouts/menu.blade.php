{{-- Versión anterior del menú
<nav>
    <a href="{{ url('/') }}">Inicio</a> |
    <a href="{{ route('clientes.index') }}">Clientes</a>
</nav>
--}}
{{-- Nuevo menú con TailWind CSS --}}
<nav class="bg-gray-900 text-white px-6 py-4 flex gap-4 shadow-md">
    <a href="{{ url('/') }}" class="px-4 py-2 bg-gray-700 rounded hover:bg-gray-600 transition">
        Inicio
    </a>
    <a href="{{ route('entidad.index', ['entidad' => 'clientes']) }}" class="px-4 py-2 bg-gray-700 rounded hover:bg-gray-600 transition">
        Clientes
    </a>
    <a href="{{ route('entidad.index', ['entidad' => 'proveedores']) }}" class="px-4 py-2 bg-gray-700 rounded hover:bg-gray-600 transition">
        Proveedores
    </a>
    <a href="{{ route('entidad.index', ['entidad' => 'articulos']) }}" class="px-4 py-2 bg-gray-700 rounded hover:bg-gray-600 transition">
        Artículos
    </a>
    <button id="btn-configuracion" class="absolute top-4 right-4 text-gray-600 hover:text-blue-600">
        <i class="fas fa-cog text-xl"></i>
    </button>
</nav>

@include('entidad.modales.modal-configuracion') 

<script>
    // Abrir modal
    document.getElementById('btn-configuracion').onclick = () => {
        document.getElementById('modal-configuracion').classList.remove('hidden');
    };

    // Cierre por botón "Cerrar"
    document.getElementById('btn-cerrar-configuracion').onclick = () => {
        document.getElementById('modal-configuracion').classList.add('hidden');
    };

    // Cierre por icono (X)
    document.getElementById('btn-cerrar-configuracion-icono').onclick = () => {
        document.getElementById('modal-configuracion').classList.add('hidden');
    };
    
    // Cierre al pulsar ESC
    document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.getElementById('modal-configuracion').classList.add('hidden');
    }
});


</script>


