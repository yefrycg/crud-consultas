<?php
if ($_SESSION['rol_sca'] != "Administrador") {
    echo $lc->forzar_cierre_sesion_controlador();
    exit();
}
?>
<!-- Registro de productos -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 600px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Registrar Proveedor</h6>

            <!-- Boton regresar -->
            <a href="<?php echo SERVER_URL; ?>proveedores/" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Regresar</span>
            </a>
        </div>

        <div class="card-body">

            <!-- Formulario de registro -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/proveedorAjax.php" method="POST" data-form="save">
                
                <!-- RUT -->
                <div class="mb-3">
                    <label for="rut" class="form-label">RUT</label>
                    <input type="text" name="rut" id="rut" class="form-control" maxlength="10" required>
                </div>

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" maxlength="20" required>
                </div>

                <!-- Contacto -->
                <div class="mb-3">
                    <label for="contacto" class="form-label">Contacto</label>
                    <input type="text" name="contacto" id="contacto" class="form-control" maxlength="10" required>
                </div>

                <!-- Dirección -->
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" maxlength="50" required>
                </div>

                <!-- Boton añadir -->
                <button type="submit" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Añadir</span>
                </button>
            </form>
        </div>
    </div>
</div>