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
            <h6 class="m-0 font-weight-bold text-primary">Registrar Producto</h6>
            
            <!-- Boton regresar -->
            <a href="<?php echo SERVER_URL; ?>productos/" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Regresar</span>
            </a>
        </div>

        <div class="card-body">

            <!-- Formulario de registro -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/productoAjax.php" method="POST" data-form="save">
                
                <!-- Categoria -->
                <div class="mb-3">
                    <label for="id_categoria" class="form-label">Categoría</label>
                    <select name="id_categoria" id="id_categoria" class="form-control" required>
                    <?php
                        require_once "./controladores/categoriaControlador.php";
                        $ins_categoria = new categoriaControlador();
                        $categorias = $ins_categoria->obtener_categorias_controlador();

                        if ($categorias) {
                            echo '<option value="">Seleccione</option>';
                            foreach ($categorias as $categoria) {
                                echo '<option value="' . $categoria->id . '">' . $categoria->nombre . '</option>';
                            }
                        } else {
                            echo '<option value="">No hay categorías disponibles</option>';
                        }
                    ?>
                    </select>
                </div>

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                </div>

                <!-- Unidad de medida -->
                <div class="mb-3">
                    <label for="unidad_medida" class="form-label">Unidad de Medida</label>
                    <select name="unidad_medida" id="unidad_medida" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="Unidad">Unidad</option>
                        <option value="Kilogramo">Kilogramo</option>
                        <option value="Libra">Libra</option>
                    </select>
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