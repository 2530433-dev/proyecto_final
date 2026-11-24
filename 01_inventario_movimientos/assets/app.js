/*document.addEventListener('DOMContentLoaded', ()=> {
    // TODO 1: filtrar la tabla por texto.
    // TODO 2: validar cantidad > 0.
    // TODO 3: confirmar antes de una SALIDA.
}
);*/
document.addEventListener('DOMContentLoaded', () => {
    const disparadores = document.querySelectorAll('[data-vista]');
    const vistas = document.querySelectorAll('.vista');
    const navBotones = document.querySelectorAll('.nav-btn');

    function cambiarVista(nombreVista) {
        vistas.forEach((sec) => {
            sec.classList.toggle('active', sec.id === `vista-${nombreVista}`);
        });

        navBotones.forEach((btn) => {
            btn.classList.toggle('active', btn.dataset.vista === nombreVista);
        });
    }

    disparadores.forEach((el) => {
        el.addEventListener('click', () => {
            cambiarVista(el.dataset.vista);
        });
    });

    // Filtro rápido en tabla de productos (PF)
    const inputBuscar = document.getElementById('buscar');
    const tabla = document.getElementById('tabla-productos');

    if (inputBuscar && tabla) {
        inputBuscar.addEventListener('input', () => {
            const texto = inputBuscar.value.toLowerCase();
            tabla.querySelectorAll('tbody tr').forEach((fila) => {
                fila.style.display = fila.textContent.toLowerCase().includes(texto) ? '' : 'none';
            });
        });
    }
});