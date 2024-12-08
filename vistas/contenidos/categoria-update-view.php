<?php
    if ($_SESSION['rol_sca'] != "Administrador") {
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }
?>

<!-- Actualizar categoría -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 600px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Editar Categoría</h6>

            <!-- Boton para regresar -->
            <a href="<?php echo SERVER_URL; ?>categorias/" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Regresar</span>
            </a>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/categoriaControlador.php";
                $ins_categoria = new categoriaControlador();
                $datos_categoria = $ins_categoria->obtener_categoria_controlador($pagina[1]);

                if($datos_categoria->rowCount() == 1) {
                    $campos = $datos_categoria->fetch();
            ?>
            <!-- Formulario para actualizar categoría -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/categoriaAjax.php" method="POST" data-form="update">
                
                <!-- Id -->
                <div class="mb-3">
                    <label for="id_actualizar" class="form-label">Id</label>
                    <input type="number" name="id_actualizar" id="id_actualizar" class="form-control" value="<?php echo $pagina[1]; ?>" readonly>
                </div>

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre_actualizar" class="form-label">Nombre</label>
                    <input type="text" name="nombre_actualizar" id="nombre_actualizar" class="form-control" value="<?php echo $campos['nombre']; ?>" required>
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label for="descripcion_actualizar" class="form-label">Descripción</label>
                    <input type="text" name="descripcion_actualizar" id="descripcion_actualizar" class="form-control" value="<?php echo $campos['descripcion']; ?>">
                </div>

                <!-- Actualizar -->
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