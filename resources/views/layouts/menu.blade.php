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
    <a href="{{ route('clientes.index') }}" class="px-4 py-2 bg-gray-700 rounded hover:bg-gray-600 transition">
        Clientes
    </a>
</nav>


