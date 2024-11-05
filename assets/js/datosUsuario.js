document.addEventListener("DOMContentLoaded", function() {
    // Hacemos la solicitud al servidor para obtener los datos del usuario
    fetch('index.php?controller=usuario&action=datosUsuario', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(
        
        data => {
        // Verifica que el usuario tiene datos correctos
        if (data) {
            // Actualiza los campos del HTML con los datos recibidos
            document.querySelector('.containerPerfil h1').textContent = data.username;
            document.querySelector('.divFoto img').src = data.foto_perfil || 'ruta_default_de_foto.png';
            document.getElementById("nombre").value = data.nombre || '';
            document.getElementById("apellido").value = data.apellido || '';
            document.getElementById("username").value = data.username || '';
            document.getElementById("email").value = data.email || '';
            document.getElementById("password").value = data.password || '';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
    
});