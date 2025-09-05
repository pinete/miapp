@include('layouts.header')

<h1>Listado de Clientes</h1>
<a href="{{ route('clientes.create') }}">Nuevo Cliente</a>
<table>
    <tr><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Acciones</th></tr>
    @foreach($clientes as $cliente)
    <tr>
        <td>{{ $cliente->nombre }}</td>
        <td>{{ $cliente->email }}</td>
        <td>{{ $cliente->telefono }}</td>
        <td>
            <a href="{{ route('clientes.edit', $cliente->id) }}">Editar</a>
            <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Eliminar</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

@include('layouts.footer')
