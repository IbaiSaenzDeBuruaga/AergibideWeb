<div class="containerPerfil">

    <?php
        require_once "panelLateral.html.php";
    ?>

    <div class="containerPanel">
        <div class="botonesArriba">
            <a href="index.php?controller=usuario&action=mostrarGestionUsuario" class="lateralBoton <?php echo ($currentController === 'usuario' && $currentAction === 'gestionUsuario') ? 'active' : ''; ?>">
                Gestión Usuarios
            </a>
            <a href="index.php?controller=usuario&action=nuevoUsuario" class="lateralBoton <?php echo ($currentController === 'usuario' && $currentAction === 'nuevoUsuario') ? 'active' : ''; ?>">
                Nuevo Usuario
            </a>
        </div>

        <div class="containerCreate">
            <div class="perfil">
                <h1>Username</h1>
                <form action="index.php?controller=usuario&action=updateFoto" method="post" id="datosUsuarioForm" enctype="multipart/form-data" class="divFoto">
                    <!-- Aqui se mostrara la foto de usuario -->
                    <img class="sinFoto" id="fotoPerfil" src="<?php echo isset($usuario->foto_perfil) ? $usuario->foto_perfil : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'; ?>" alt="Foto de perfil default">
                    <label id="labelActualizarFoto">
                        <button id="editarFoto" class="botonEditar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16" color="#4DBAD9">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/></svg><path d="..."></path></svg>
                        </button>
                        <input type="file" name="nuevaFoto" id="nuevaFoto" accept="image/*" hidden>
                    </label>
                    <div class="botonesPerfil">
                        <button type="submit" id="guardarFoto" class="diseñoBoton">Añadir Foto</button>
                    </div>
                </form>
            </div>

            <div class="listaDatos">
                <form action="index.php?controller=usuario&action=create" method="post" id="nuevoUsuarioForm" enctype="multipart/form-data">
                    <table class="estiloTabla">
                        <tr>
                            <th>Nombre:</th>
                            <td><input type="text" name="nombre" id="nombre" value=""></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Apellido:</th>
                            <td><input type="text" name="apellido" id="apellido" value=""></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Usuario:</th>
                            <td><input type="text" name="username" id="username" value=""></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><input type="text" name="email" id="email" value=""></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Nueva contraseña:</th>
                            <td><input type="text" name="nuevaPassword" id="nuevaPassword" value=""></td>
                        </tr>
                        <tr>
                            <th>Repetir contraseña:</th>
                            <td><input type="text" name="repetirPassword" id="actualPassword" value=""></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                            <select name="rol">
                                <option value="usuario">Usuario</option>
                                <option value="admin">Administrador</option>
                            </select>
                            </td>
                        </tr>
                    </table>
                    <div class="divBotonGuardar">
                        <button type="submit" id="guardarDatosUsuario" class="diseñoBoton">Guardar</button>
                        <button type="button" class="diseñoBoton" onclick="location.reload();">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>