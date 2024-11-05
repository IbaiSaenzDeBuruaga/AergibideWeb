document.addEventListener('DOMContentLoaded', function() {
    const marcarTodasLeidasBtn = document.getElementById('marcarTodasLeidasBtn');
    const notificacionesContainer = document.getElementById('notificationsDropdown');

    if (marcarTodasLeidasBtn) {
        marcarTodasLeidasBtn.addEventListener('click', function() {
            const userId = this.getAttribute('data-usuario-id');

            if (!userId) {
                console.error('ID de usuario no disponible');
                return;
            }

            fetch(`index.php?controller=usuario&action=marcarTodasNotificacionesComoLeidas&id_usuario=${userId}`, {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Limpiar el contenedor de notificaciones
                        notificacionesContainer.innerHTML = '<p>No hay nuevas notificaciones</p>';
                        // Actualizar el icono de la campana
                        document.getElementById('bell').className = 'bi bi-bell';
                        document.querySelector('.notification-badge')?.remove();
                    } else {
                        console.error('Error al marcar las notificaciones como leídas');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    }
});