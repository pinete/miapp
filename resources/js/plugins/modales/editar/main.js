// main.js
import { generarCamposModal, abrirModal, inicializarModalListeners } from './modalEditor.js';

const camposCliente = [
  { nombre: 'nombre', label: 'Nombre', tipo: 'text' },
  { nombre: 'email', label: 'Email', tipo: 'email' },
  { nombre: 'telefono', label: 'Teléfono', tipo: 'text' }
];

const valoresCliente = {
  nombre: 'Luis',
  email: 'luis@example.com',
  telefono: '600123456'
};

inicializarModalListeners();
generarCamposModal({ campos: camposCliente, entidad: 'Cliente', valores: valoresCliente });
abrirModal();
