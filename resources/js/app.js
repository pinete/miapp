import './bootstrap';

// Utilidades personalizadas
//import './utilies.js';

// Módulos JS para los botones DataTable
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.html5';
import 'datatables.net-buttons/js/buttons.print';

import {
    mostrarAlerta,
    mostrarNotificacion,
    manejarErrorAJAX,
    validarFormulario
} from './plugins/alertas.js';

// Para que tengan ámbito global
window.mostrarAlerta = mostrarAlerta;
window.mostrarNotificacion = mostrarNotificacion;
window.manejarErrorAJAX = manejarErrorAJAX;
window.validarFormulario = validarFormulario;


