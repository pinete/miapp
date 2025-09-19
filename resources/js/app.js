import './bootstrap';

// Módulos JS para los botones DataTable
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.html5';
import 'datatables.net-buttons/js/buttons.print';
import 'datatables.net-colreorder'; // Permite reordenar las columnas
import 'datatables.net-colreorder-bs5'; // Estilos Bootstrap 5 para ColReorder



// Funciones para servir alertas de SweetAlert2
import {
    mostrarAlerta,
    mostrarNotificacion,
    manejarErrorAJAX,
    validarFormulario
} from './plugins/alertas.js';

// Para que las alertas tengan ámbito global
window.mostrarAlerta = mostrarAlerta;
window.mostrarNotificacion = mostrarNotificacion;
window.manejarErrorAJAX = manejarErrorAJAX;
window.validarFormulario = validarFormulario;

/*
// Funciones para manejar modales (abrir, cerrar, rellenar, vaciar)
import { abrirModal, cerrarModal, rellenarModal, vaciarModal } from './plugins/modalFunctions.js';

// Para que las funciones de modal tengan ámbito global
window.abrirModal = abrirModal;
window.cerrarModal = cerrarModal;
window.rellenarModal = rellenarModal;
window.vaciarModal = vaciarModal;
*/
