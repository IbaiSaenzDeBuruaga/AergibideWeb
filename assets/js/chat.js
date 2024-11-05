var id_emisor = window.id_emisor; // ID del emisor desde la sesión
var user_emisor = window.user_emisor;
var msgBox = document.getElementById('msgBox'); // Contenedor de mensajes
console.log("Initial user_emisor:", user_emisor);

document.addEventListener("DOMContentLoaded", function() {
    const msgBox = document.getElementById('msgBox');
    const messageInput = document.getElementById('message');
    const sendButton = document.getElementById('send-message');
    const userComboBox = document.getElementById('user-combobox');
    const selectedUserHeader = document.getElementById('selected-user');
    const userItems = document.querySelectorAll('.user-item');

    // Update active user in sidebar
    function updateActiveUser(userId) {
        const userItems = document.querySelectorAll('.user-item');
        const selectedUserHeader = document.getElementById('selected-user');
        const selectedUserAvatar = document.getElementById('selected-user-avatar');
        const defaultImagePath = 'assets/img/fotoPorDefecto.png';

        userItems.forEach(item => {
            if (item.dataset.userId === userId) {
                item.classList.add('active');
                selectedUserHeader.textContent = item.querySelector('span').textContent;

                // Update the avatar image
                const userImageElement = item.querySelector('.user-avatar img');
                if (userImageElement) {
                    const userImage = userImageElement.src;
                    selectedUserAvatar.src = userImage;
                } else {
                    console.error('No image element found for user:', userId);
                    selectedUserAvatar.src = defaultImagePath;
                }
            } else {
                item.classList.remove('active');
            }
        });
    }

    // Click handlers for user items in sidebar
    userItems.forEach(item => {
        item.addEventListener('click', () => {
            const userId = item.dataset.userId;
            userComboBox.value = userId;
            updateActiveUser(userId);
            loadMessages();
        });
    });

    let firstLoad = true; // Variable para rastrear si es la primera carga

    async function loadMessages() {
        const id_receptor = userComboBox.value;
        if (!id_receptor) return;

        updateActiveUser(id_receptor);

        try {
            const response = await fetch(
                `index.php?controller=chat&action=get_messages&id_emisor=${id_emisor}&id_receptor=${id_receptor}`,
                {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );

            if (!response.ok) throw new Error('Error en la solicitud');

            const data = await response.json();

            // Guardamos la posición actual del scroll y la altura del contenido
            const scrollPosition = msgBox.scrollTop;
            const oldScrollHeight = msgBox.scrollHeight;

            msgBox.innerHTML = "";

            let lastDate = "";

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(function(mensaje) {
                    const mensajeFecha = new Date(mensaje.fecha);
                    const mensajeFechaLocal = new Date(mensajeFecha.getTime() - mensajeFecha.getTimezoneOffset() * 60000);
                    const mensajeDia = mensajeFechaLocal.toLocaleDateString();
                    const mensajeHora = mensajeFechaLocal.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                    if (mensajeDia !== lastDate) {
                        msgBox.innerHTML += `<div class="message-day">${mensajeDia}</div>`;
                        lastDate = mensajeDia;
                    }

                    const messageClass = (mensaje.emisor === user_emisor) ? 'sent' : 'received';
                    msgBox.innerHTML += `
            <div class="message ${messageClass}">
                ${mensaje.mensaje}
                <em class="message-time">${mensajeHora}</em>
            </div>
            `;
                });

                // Lógica para el desplazamiento
                if (firstLoad) {
                    msgBox.scrollTop = msgBox.scrollHeight; // Desplazamos al último mensaje en la primera carga
                    firstLoad = false;
                } else {
                    // Calculamos si el usuario estaba cerca del fondo antes de la actualización
                    const wasNearBottom = oldScrollHeight - scrollPosition <= msgBox.clientHeight + 100;

                    if (wasNearBottom) {
                        msgBox.scrollTop = msgBox.scrollHeight; // Si estaba cerca del fondo, desplazamos al final
                    } else {
                        // Si no estaba cerca del fondo, mantenemos la posición relativa
                        msgBox.scrollTop = scrollPosition + (msgBox.scrollHeight - oldScrollHeight);
                    }
                }
            } else {
                msgBox.innerHTML = "<div class='message'>No hay mensajes aún.</div>";
            }
        } catch (error) {
            console.error("Error en la carga de mensajes:", error);
            msgBox.innerHTML = "<div class='message'>Error al cargar mensajes.</div>";
        }
    }

    async function sendMessage() {
        const id_receptor = userComboBox.value;
        const message = messageInput.value.trim();

        if (!id_receptor) {
            alert("Selecciona un usuario para enviar el mensaje");
            return;
        }
        if (!message) {
            alert("Escribe algún mensaje");
            return;
        }

        const params = new URLSearchParams();
        params.append('id_emisor', id_emisor);
        params.append('id_receptor', id_receptor);
        params.append('mensaje', message);

        try {
            const response = await fetch('controller/insert_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: params.toString()
            });

            const responseText = await response.text();

            if (responseText.trim() === "Mensaje insertado correctamente") {
                messageInput.value = '';
                loadMessages();
            } else {
                console.error("Error en la inserción:", responseText);
            }
        } catch (error) {
            console.error("Error en la solicitud de envío:", error);
        }
    }

    // Event Listeners
    userComboBox.addEventListener('change', loadMessages);
    sendButton.addEventListener('click', sendMessage);
    messageInput.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    });

    // Initial load
    loadMessages();

    // Auto refresh
    setInterval(loadMessages, 1000);
});