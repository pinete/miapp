<h1>Formulario de edición de clientes</h1>

<form method="POST" action="{{ route('clientes.update', $cliente->id) }}">
    @csrf
    @method('PUT')

    Nombre: <input type="text" name="nombre" value="{{ $cliente->nombre }}" required><br>
    Email: <input type="email" name="email" value="{{ $cliente->email }}" required><br>
    Teléfono: <input type="text" name="telefono" value="{{ $cliente->telefono }}" required><br>

    <button type="submit">Actualizar Cliente</button>
</form>

