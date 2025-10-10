import {
  anchosModales,
  guardarConfModal,
  obtenerConfModal,
  obtenerEntidadesModelos,
  obtenerEntidades,
} from './plugins/personalConfig.js';

document.addEventListener('DOMContentLoaded', async () => {
    const selectAncho = document.getElementById('select-ancho');
    const selectEntidad = document.getElementById('select-entidad');

    // Poblar selector de ancho desde anchosModales
    selectAncho.innerHTML = '';
    Object.entries(anchosModales).forEach(([key, clase]) => {
        const option = document.createElement('option');
        option.value = key;
        option.textContent = `${key} (${clase})`;
        selectAncho.appendChild(option);
    });

    // Poblar selector de entidad desde backend
    const entidades = await obtenerEntidades();
    
    if (!entidades || typeof entidades !== 'object') {
        console.error('Entidades no válidas:', entidades);
        return; // o mostrar mensaje al usuario
    }

    
    entidades.forEach(entidad => {
        const option = document.createElement('option');
        option.value = entidad;
        option.textContent = entidad; // o capitalizar si quieres
        selectEntidad.appendChild(option);
    });



    // Cargar ancho actual al cambiar entidad
    selectEntidad.addEventListener('change', () => {
        const entidad = selectEntidad.value;
        const anchoActual = obtenerConfModal(entidad);
        selectAncho.value = anchoActual;
    });

    // Guardar configuración
    document.getElementById('form-configuracion').addEventListener('submit', e => {
        e.preventDefault();
        const entidad = selectEntidad.value;
        const ancho = selectAncho.value;
        if (entidad && ancho) {
            guardarConfModal(entidad, ancho);
            alert(`Configuración guardada para ${entidad}: ${ancho}`);
        }
    });
});