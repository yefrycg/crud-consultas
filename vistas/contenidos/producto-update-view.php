<?php
if ($_SESSION['rol_sca'] != "Administrador") {
    echo $lc->forzar_cierre_sesion_controlador();
    exit();
}
?>

<!-- Actualizar producto -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 600px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Editar Producto</h6>
            
            <!-- Boton regresar -->
            <a href="<?php echo SERVER_URL; ?>productos/" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Regresar</span>
            </a>
        </div>

        <div class="card-body">
            <?php
                require_once "./controladores/productoControlador.php";
                $ins_producto = new productoControlador();
                $datos_producto = $ins_producto->obtener_producto_controlador($pagina[1]);

                if($datos_producto->rowCount() == 1) {
                    $campos = $datos_producto->fetch();
            ?>

            <!-- Formulario de registro -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/productoAjax.php" method="POST" data-form="update">
                
                <!-- Código -->
                <div class="mb-3">
                    <label for="codigo_actualizar" class="form-label">Código</label>
                    <input type="text" name="codigo_actualizar" id="codigo_actualizar" class="form-control" value="<?php echo $pagina[1]; ?>" readonly>
                </div>

                <!-- Categoría -->
                <div class="mb-3">
                    <label for="categoria_actualizar" class="form-label">Categoría</label>
                    <select name="categoria_actualizar" id="categoria_actualizar" class="form-control" required>
                    <?php
                        require_once "./controladores/categoriaControlador.php";
                        $ins_categoria = new categoriaControlador();
                        $categorias = $ins_categoria->obtener_categorias_controlador();
                        
                        if ($categorias) {
                            echo '<option value="">Seleccione</option>';
                            foreach ($categorias as $categoria) {
                                $selected = ($categoria->id == $campos['id_categoria']) ? 'selected' : '';
                                echo '<option value="' . $categoria->id . '" ' . $selected . '>' . $categoria->nombre . '</option>';
                            }
                        } else {
                            echo '<option value="">No hay categorías disponibles</option>';
                        }
                    ?>
                    </select>
                </div>

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre_actualizar" class="form-label">Nombre</label>
                    <input type="text" name="nombre_actualizar" id="nombre_actualizar" class="form-control" value="<?php echo $campos['nombre']; ?>" required>
                </div>

                <!-- Unidad de medida -->
                <div class="mb-3">
                    <label for="unidad_medida_actualizar" class="form-label">Unidad de Medida</label>
                    <select name="unidad_medida_actualizar" id="unidad_medida_actualizar" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="Unidad" <?php echo ($campos['unidad_medida'] == 'Unidad') ? 'selected' : ''; ?> >Unidad</option>
                        <option value="Kilogramo" <?php echo ($campos['unidad_medida'] == 'Kilogramo') ? 'selected' : ''; ?> >Kilogramo</option>
                        <option value="Libra" <?php echo ($campos['unidad_medida'] == 'Libra') ? 'selected' : ''; ?> >Libra</option>
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