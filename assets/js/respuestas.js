/*Detectar si el archivo se ha subido */
document.addEventListener('change', function(e) {
    if (e.target && e.target.id.startsWith('cargadorDeImagenRespuesta')) {
        const labelConfirmacion = e.target.nextElementSibling; // Obtiene el label siguiente
        
        if (e.target.files && e.target.files[0]) {
            labelConfirmacion.removeAttribute('hidden');
        } else {
            labelConfirmacion.setAttribute('hidden', '');   
        }
    }
});


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/* Dar like o disLike a las preguntas */

document.getElementById('botonPreguntaLike').addEventListener('click', function(event) {
    likePregunta(event, 'botonPreguntaLike');
});
document.getElementById('botonPreguntaDislike').addEventListener('click', function(event) {
    likePregunta(event, 'botonPreguntaDislike');
});

async function likePregunta(event, idElemento) {
    event.preventDefault(); // Prevenir comportamiento por defecto
    const boton = document.getElementById(idElemento);
    const idPregunta = boton.value;
    const idUsuario = document.getElementById('userId').value;
    let meGusta = 0;

    // Obtener referencias a los botones
    const botonLike = document.getElementById('botonPreguntaLike');
    const botonDislike = document.getElementById('botonPreguntaDislike');

    // Determinar si es like o dislike
    if(idElemento == "botonPreguntaLike") {
        meGusta = 1;
        // Actualizar inmediatamente la UI
        if(botonLike.querySelector('i').classList.contains('bi-airplane-fill')) {
            // Si ya está liked, lo quitamos
            botonLike.innerHTML = '<i class="bi bi-airplane"></i>';
        } else {
            // Si no está liked, lo ponemos
            botonLike.innerHTML = '<i class="bi bi-airplane-fill"></i>';
            botonDislike.innerHTML = '<i class="bi bi-airplane airplane-down"></i>';
        }
    }
    else if(idElemento == "botonPreguntaDislike") {
        meGusta = 0;
        // Actualizar inmediatamente la UI
        if(botonDislike.querySelector('i').classList.contains('bi-airplane-fill')) {
            // Si ya está disliked, lo quitamos
            botonDislike.innerHTML = '<i class="bi bi-airplane airplane-down"></i>';
        } else {
            // Si no está disliked, lo ponemos
            botonDislike.innerHTML = '<i class="bi bi-airplane-fill airplane-down"></i>';
            botonLike.innerHTML = '<i class="bi bi-airplane"></i>';
        }
    }

    const params = new URLSearchParams();
    params.append("idPregunta", idPregunta);
    params.append("idUsuario", idUsuario);   
    params.append("meGusta", meGusta);

    try {
        const response = await fetch("index.php?controller=pregunta&action=like", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: params.toString()
        });

        if(!response.ok) {
            throw new Error('Error de la solicitud');
            // Si hay error, revertir los cambios visuales
            location.reload();
        }

        const data = await response.json();

        if(data.status !== "success") {
            // Si la respuesta no es exitosa, revertir los cambios
            console.log(data.message);
        }
    } catch (error) {
        console.log(error);
        // Si hay error, revertir los cambios visuales
        location.reload();
    }
}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* Dar like o disLike a las respuestas 

*/


/*El querySelectorAll es para seleccionar todos los elementos que coincidan con el selector*/
document.querySelectorAll('[id^="botonRespuestaLike-"]').forEach(boton => {
    boton.addEventListener('click', function() {
        const idRespuesta = this.value;
        const userId = document.getElementById('userId').value;
        
        const params = new URLSearchParams();
        params.append("idRespuesta", idRespuesta);
        params.append("idUsuario", userId);
        params.append("meGusta", 1);


        fetch(`index.php?controller=respuesta&action=like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: params.toString()
        })
        .then(response => response.json())
        .then(data => {
            if(data.message == "Voto eliminado correctamente la respuesta") {
                this.querySelector('i').classList.remove('bi-airplane-fill');
                this.querySelector('i').classList.add('bi-airplane');
            } else if(data.status == "success") {
                // Activar el like
                this.querySelector('i').classList.remove('bi-airplane');
                this.querySelector('i').classList.add('bi-airplane-fill');
                
                // Asegurarnos de que el dislike esté limpio
                const dislikeBtn = document.getElementById(`botonRespuestaDisLike-${idRespuesta}`);
                if(dislikeBtn) {
                    const dislikeIcon = dislikeBtn.querySelector('i');
                    dislikeIcon.classList.remove('bi-airplane-fill');
                    dislikeIcon.classList.add('bi-airplane');
                    dislikeIcon.classList.add('airplane-down');
                }
            }
        });
    });
});

document.querySelectorAll('[id^="botonRespuestaDisLike-"]').forEach(boton => {
    boton.addEventListener('click', function() {
        const idRespuesta = this.value;
        const userId = document.getElementById('userId').value;
        
        const params = new URLSearchParams();
        params.append("idRespuesta", idRespuesta);
        params.append("idUsuario", userId);
        params.append("meGusta", 0);

        fetch(`index.php?controller=respuesta&action=like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: params.toString()
        })
        .then(response => response.json())
        .then(data => {
            if(data.message == "Voto eliminado correctamente la respuesta") {
                this.querySelector('i').classList.remove('bi-airplane-fill');
                this.querySelector('i').classList.add('bi-airplane');
                this.querySelector('i').classList.add('airplane-down');
            } else if(data.status == "success") {
                // Activar el dislike
                this.querySelector('i').classList.remove('bi-airplane');
                this.querySelector('i').classList.add('bi-airplane-fill');
                this.querySelector('i').classList.add('airplane-down');
                
                // Asegurarnos de que el like esté limpio
                const likeBtn = document.getElementById(`botonRespuestaLike-${idRespuesta}`);
                if(likeBtn) {
                    likeBtn.querySelector('i').classList.remove('bi-airplane-fill');
                    likeBtn.querySelector('i').classList.add('bi-airplane');
                }
            }
        });
    });
});


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*Guardar o desguardar preguntas */

document.getElementById('botonGuardarPregunta').addEventListener('click', async function() {
    const idPregunta = this.value;
    const userId = document.getElementById('userId').value;

    const params = new URLSearchParams();
    params.append("idPregunta", idPregunta);
    params.append("idUsuario", userId);

   try
   {

       const response = await fetch(`index.php?controller=pregunta&action=guardados`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: params.toString()
        }); 
        


        const data = await response.json();
        console.log('Respuesta del servidor:', data);

        if (data.status == "success" && data.message == "add OK")
        {
            console.log("Guardando pregunta");
            console.log('Antes de cambiar clases:', this.querySelector('i').className);
            this.querySelector('i').classList.remove('bi-bookmark');
            this.querySelector('i').classList.add('bi-bookmark-fill');
        }
        else if(data.status == "success" && data.message == "delete OK")
        {
            console.log("Desguardando pregunta");
            console.log('Antes de cambiar clases:', this.querySelector('i').className);
            this.querySelector('i').classList.remove('bi-bookmark-fill');
            this.querySelector('i').classList.add('bi-bookmark');
        }
        else
        {
            throw new Error(data.message);
            
        }

    
   }
   catch (error)
   {
        console.log(error);
   }
    
}); 

/*Guardar o desguardar respuestas */
document.querySelectorAll('[id^="botonGuardarRespuesta-"]').forEach(boton => {
    boton.addEventListener('click', async function(){
        const idRespuesta = this.value;
        const userId = document.getElementById('userId').value;
        
        const params = new URLSearchParams();
        params.append("idRespuesta", idRespuesta);
        params.append("idUsuario", userId);


        try
        {
     
            const response = await fetch(`index.php?controller=respuesta&action=guardados`, {
                 method: 'POST',
                 headers: {
                     'Content-Type': 'application/x-www-form-urlencoded',
                     'X-Requested-With': 'XMLHttpRequest'
                 },
                 body: params.toString()
             }); 
             
     
     
             const data = await response.json();
             console.log('Respuesta del servidor:', data);
     
             if (data.status == "success" && data.message == "add OK")
             {
                 console.log("Guardando respuesta");
                 console.log('Antes de cambiar clases:', this.querySelector('i').className);
                 this.querySelector('i').classList.remove('bi-bookmark');
                 this.querySelector('i').classList.add('bi-bookmark-fill');
             }
             else if(data.status == "success" && data.message == "delete OK")
             {
                 console.log("Desguardando respuesta");
                 console.log('Antes de cambiar clases:', this.querySelector('i').className);
                 this.querySelector('i').classList.remove('bi-bookmark-fill');
                 this.querySelector('i').classList.add('bi-bookmark');
             }
             else
             {
                 throw new Error(data.message);
                 
             }
     
         
        }
        catch (error)
        {
             console.log(error);
        }

    })
})



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/*Editar respuestas*/

//EventListener para poner en modo editar la respuesa

document.querySelectorAll('[id^="editarRespuesta-"]').forEach(boton => {
    boton.addEventListener('click', function() {
        const idRespuesta = this.getAttribute('value');
        const idPregunta = this.getAttribute('data-id-pregunta');
        const contenedorRespuesta = this.closest('.contenedorRespuestaDivididor');
        const contenidoOriginal = contenedorRespuesta.querySelector('.contenidoRespuesta');
        const textoOriginal = contenidoOriginal.textContent.trim();
        const imagenOriginal = contenedorRespuesta.querySelector('.imagenRespuesta');
        let imagenOriginalUrl = null;
        if (imagenOriginal) {
            imagenOriginalUrl = imagenOriginal.src;
        }
        

        const formularioEdicion = `
            <form class="editarRespuestaForm" action="index.php?controller=respuesta&action=edit" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="idRespuesta" value="${idRespuesta}">
                <input type="hidden" name="id_pregunta" value="${idPregunta}">
                <textarea name="texto" class="textAreaRespuesta">${textoOriginal}</textarea>
                <div class="contenedorImagenEdicion">
                    <img src="${imagenOriginalUrl ? imagenOriginalUrl : ''}" alt="Imagen de respuesta">
                </div>
                <div class="botonesEdicion" style="display: flex; justify-content: space-between; gap: 1em;">
                    <label class="botonSubirArchivo">
                        Cambiar Archivo
                        <input type="file" name="imagen" id="cargadorDeImagenRespuesta-${idRespuesta}" accept="image/*" hidden>
                        <label id="archivoSubidoRespuesta-${idRespuesta}" hidden>
                            <i class="bi bi-check-circle-fill"></i>
                        </label>
                    </label>
                    <button class="botonGuardarRespuesta" type="submit" data-id="${idRespuesta}">
                        <i class="bi bi-check-circle"></i> Guardar
                    </button>
                    <button class="botonCancelarRespuesta">
                        <i class="bi bi-x-circle "></i> Cancelar
                    </button>
                </div>
            </form>
        `;

        // Reemplazar el contenido original con el formulario
        contenidoOriginal.innerHTML = formularioEdicion;

        // Manejador para cancelar la edición
        contenedorRespuesta.querySelector('.botonCancelarRespuesta').addEventListener('click', () => {
            contenidoOriginal.innerHTML = textoOriginal;
            if (imagenOriginal) {
                contenidoOriginal.appendChild(imagenOriginal);
            }
        });
    });
});