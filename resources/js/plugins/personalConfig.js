// Relación de anchos de modales en TailWind
export const anchosModales = {
    sm: 'max-w-sm',
    md: 'max-w-md',
    lg: 'max-w-lg',
    xl: 'max-w-xl',
    '2xl': 'max-w-2xl'
};

/** 
 * Capturamos el mapa de entidades => Modelos 
 */ 
export async function obtenerEntidadesModelos() {
    try {
        const res = await fetch('/configuracion/entidades');
        const data = await res.json();
        return data?.entidades || {}; // { clientes: 'Cliente', ... } o {}
    } catch (error) {
        console.error('Error al obtener entidades:', error);
        return {};
    }
}

/**
 * Obtenemos las entidades existentes
 * @returns array de entidades
 */
export async function obtenerEntidades() {
    const res = await fetch('/configuracion/entidades');
    const entidades = await res.json(); // { clientes: 'Cliente', ... }
    return Object.keys(entidades); // ['clientes', 'proveedores', ...]
}

/**
 * Guardamos la configuración guardada para los modales de cada entidad 
 * en el objeto 'configModales' almacenado en LocalStorage
 * @param {*} entidad 
 * @param {*} ancho 
 */
export function guardarConfModal(entidad, ancho) {

    const config = JSON.parse(localStorage.getItem('configModales')) || {};
    config[entidad] = ancho;
    localStorage.setItem('configModales', JSON.stringify(config));
}

/**
 * Obtenemos la configuración guardada para los modales de cada entidad 
 * en el objeto 'configModales' almacenado en LocalStorage
 * @param {*} entidad 
 * @returns 
 */
export function obtenerConfModal(entidad) {
    const config = JSON.parse(localStorage.getItem('configModales')) || {};
    return config[entidad] || 'md'; // valor por defecto
}

/**
 * Aplica al abrir el modal de una entidad determinada el valor guardado 
 * en el objeto 'configModales de LocalStorage (ancho del modal) 
 * @param {*} entidad 
 * @param {*} modo 
 * @returns 
 */
export function aplicarAnchoModal(entidad, modo = 'editar') {
    const ancho = obtenerConfModal(entidad);
    console.log('resultado de obtenerConfModal(entidad):',ancho)
    const claseAncho = anchosModales[ancho] || anchosModales['md'];

    const modalContent = document.getElementById(`modal-${modo}-content`);
    if (!modalContent) return;

    Object.values(anchosModales).forEach(clase => modalContent.classList.remove(clase));
    modalContent.classList.add(claseAncho);
}
