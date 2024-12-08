<?php
    if ($_SESSION['rol_sca'] != "Administrador") {
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }
?>

<!-- Registro de categorías -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 600px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Registrar Categoría</h6>

            <!-- Boton para regresar -->
            <a href="<?php echo SERVER_URL; ?>categorias/" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Regresar</span>
            </a>
        </div>

        <div class="card-body">

            <!-- Formulario de registro -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/categoriaAjax.php" method="POST" data-form="save">
                
                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                </div>

                <!-- Descripción (Opcional) -->
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <input type="text" name="descripcion" id="descripcion" class="form-control" value="">
                </div>

                <!-- Botón añadir categoría -->
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