// modalEditor.js es un modal reutilizable que se genera en virtud de los valores aportados
/**
 *
 * @param {*} param0
 * @param campos // nombre de los inputs a mostrar
 * @param entidad // nombre de la entidad sobre la que actua (clientes, articulos, proveedores, ...)
 * @param valores // valores de los inputs a mostrar
 */
export function generarCamposModal({ campos, entidad = 'Entidad', valores = {} }) {
  const contenedor = document.getElementById('modal-campos');
  const titulo = document.getElementById('modal-titulo');
  contenedor.innerHTML = '';
  titulo.textContent = `Editar ${entidad}`;

  campos.forEach(campo => {
    const div = document.createElement('div');
    div.className = 'mb-4';

    const label = document.createElement('label');
    label.setAttribute('for', `editar-${campo.nombre}`);
    label.className = 'block text-sm font-medium text-gray-700';
    label.textContent = campo.label;

    const input = document.createElement('input');
    input.type = campo.tipo || 'text';
    input.id = `editar-${campo.nombre}`;
    input.name = campo.nombre;
    input.className = 'mt-1 block w-full border-gray-300 rounded-md shadow-sm';
    input.value = valores[campo.nombre] || '';

    div.appendChild(label);
    div.appendChild(input);
    contenedor.appendChild(div);
  });
}

export function abrirModal() {
  document.getElementById('modal-editar').classList.remove('hidden');
}

export function cerrarModal() {
  document.getElementById('modal-editar').classList.add('hidden');
}

export function inicializarModalListeners() {
  document.getElementById('btn-cerrar-modal-editar').onclick = cerrarModal;
  document.getElementById('btn-cerrar-modal-editar-icono').onclick = cerrarModal;
}
