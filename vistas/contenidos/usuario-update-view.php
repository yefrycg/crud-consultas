<?php
if ($_SESSION['id_sca'] != $_SESSION['id_sca']) {
    if ($_SESSION['rol_sca'] != "Administrador") {
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }
}

// Comparar IDs y determinar la redirección
$redireccion = ($_SESSION['id_sca'] == $pagina[1]) ? SERVER_URL . 'panel/' : SERVER_URL . 'usuarios/';

?>

<!-- Actualizar usuario -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 600px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Editar Usuario</h6>

            <!-- Boton regresar -->
            <a href="<?php echo $redireccion; ?>" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Regresar</span>
            </a>
        </div>

        <div class="card-body">
            <?php
                require_once "./controladores/usuarioControlador.php";
                $ins_usuario = new usuarioControlador();
                $datos_usuario = $ins_usuario->obtener_usuario_controlador($pagina[1]);

                if($datos_usuario->rowCount() == 1) {
                    $campos = $datos_usuario->fetch();
            ?>

            <!-- Formulario de actualizacion -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/usuarioAjax.php" method="POST" data-form="update">

                <!-- Id -->
                <div class="mb-3">
                    <label for="id_actualizar" class="form-label">Id</label>
                    <input type="number" name="id_actualizar" id="id_actualizar" class="form-control" value="<?php echo $pagina[1]; ?>" readonly>
                </div>

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre_actualizar" class="form-label">Nombre</label>
                    <input type="text" name="nombre_actualizar" id="nombre_actualizar" class="form-control" value="<?php echo $campos['nombre']; ?>" maxlength="20" required>
                </div>

                <!-- Contraseña -->
                <div class="mb-3">
                    <label for="contrasena_actualizar" class="form-label">Contraseña</label>
                    <input type="text" name="contrasena_actualizar" id="contrasena_actualizar" class="form-control" value="<?php echo $campos['contrasena']; ?>" maxlength="20" required>
                </div>

                <!-- Rol -->
                <div class="mb-3">
                    <label for="rol_actualizar" class="form-label">Rol</label>
                    <select name="rol_actualizar" id="rol_actualizar" class="form-control" required >
                        <option value="">Seleccione</option>
                        <option value="Administrador" <?php echo ($campos['rol'] == 'Administrador') ? 'selected' : ''; ?> >Administrador</option>
                        <option value="Vendedor" <?php echo ($campos['rol'] == 'Vendedor') ? 'selected' : ''; ?> >Vendedor</option>
                    </select>
                </div>

                <!-- Boton actualizar -->
                <button type="submit" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Actualizar</span>
                </button>
            </form>
            <?php }?>
        </div>
    </div>
</div>