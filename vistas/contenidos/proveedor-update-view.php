<?php
if ($_SESSION['rol_sca'] != "Administrador") {
    echo $lc->forzar_cierre_sesion_controlador();
    exit();
}
?>
<!-- Actualizar proveedor -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 600px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Editar Proveedor</h6>

            <!-- Boton regresar -->
            <a href="<?php echo SERVER_URL; ?>proveedores/" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Regresar</span>
            </a>
        </div>

        <div class="card-body">
            <?php
                require_once "./controladores/proveedorControlador.php";
                $ins_proveedor = new proveedorControlador();
                $datos_proveedor = $ins_proveedor->obtener_proveedor_controlador($pagina[1]);

                if($datos_proveedor->rowCount() == 1) {
                    $campos = $datos_proveedor->fetch();
            ?>

            <!-- Formulario de actualización -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/proveedorAjax.php" method="POST" data-form="update">

                <!-- RUT -->
                <div class="mb-3">
                    <label for="rut_actualizar" class="form-label">RUT</label>
                    <input type="text" name="rut_actualizar" id="rut_actualizar" class="form-control" value="<?php echo $pagina[1]; ?>" maxlength="10" readonly>
                </div>

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre_actualizar" class="form-label">Nombre</label>
                    <input type="text" name="nombre_actualizar" id="nombre_actualizar" class="form-control" value="<?php echo $campos['nombre']; ?>" maxlength="20" required>
                </div>

                <!-- Contacto -->
                <div class="mb-3">
                    <label for="contacto_actualizar" class="form-label">Contacto</label>
                    <input type="text" name="contacto_actualizar" id="contacto_actualizar" class="form-control" value="<?php echo $campos['contacto']; ?>" maxlength="10" required>
                </div>

                <!-- Dirección -->
                <div class="mb-3">
                    <label for="direccion_actualizar" class="form-label">Dirección</label>
                    <input type="text" name="direccion_actualizar" id="direccion_actualizar" class="form-control" value="<?php echo $campos['direccion']; ?>" maxlength="50" required>
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