document.getElementById("botonDeLogin").addEventListener("click", validarCredenciales);

async function validarCredenciales(event)
 {
    event.preventDefault();  // Prevenir la recarga de la página

    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;

    if (email === "" || password === "") {
        alert("Por favor, introduce el email y la contraseña.");
        return;
    }

    // Crear el cuerpo de la solicitud con URLSearchParams
    const params = new URLSearchParams();
    params.append('email', email);
    params.append('password', password);

    try {
        const response = await fetch('index.php?controller=usuario&action=logear', {
            method: 'POST',  // Método POST
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: params.toString()  // Convertir a string los parámetros
        });

        if (!response.ok) {
            throw new Error('Error en la solicitud');
        }

        // Procesar la respuesta como JSON
        const data = await response.json();
        console.log('Respuesta del servidor:', data);

        // Evaluar el estado de la respuesta
        if (data.status === "success") 
        {
            console.log(data.message);
            window.location.href = data.redirect;  
        }
        else if (data.status === "error") 
        {
            console.log(data.message);
            alert('Login fallido: ' + data.message +"\n"+data.datosDeSesion);
        }
    } 
    catch (error)
    {
        console.error('Error:', error);
    }
}
