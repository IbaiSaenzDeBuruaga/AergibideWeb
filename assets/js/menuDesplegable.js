// Seleccionar el icono de persona y el menú desplegable
const personIcon = document.getElementById('person');
const dropdownMenu = document.getElementById('dropdown');

// Función para mostrar u ocultar el menú desplegable
personIcon.addEventListener('click', (event) => {
    event.stopPropagation(); // Evita que el evento se propague al documento
    const isVisible = dropdownMenu.style.display === 'block';

    // Alternar visibilidad del menú desplegable
    dropdownMenu.style.display = isVisible ? 'none' : 'block';
});

// Ocultar el menú si se hace clic en cualquier parte de la página
document.addEventListener('click', (event) => {
    if (!personIcon.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.style.display = 'none'; // Ocultar el menú desplegable
    }
});
