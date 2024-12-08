<?php
if ($_SESSION['rol_sca'] != "Administrador") {
    echo $lc->forzar_cierre_sesion_controlador();
    exit();
}
?>

<!-- Productos -->
<div class="container-fluid">

    <!-- Cabecera de página -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Productos</h1>
        
        <!-- Boton añadir -->
        <a href="<?php echo SERVER_URL; ?>producto-new/" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Añadir</span>
        </a>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de Productos</h6>
        </div>

        <div class="card-body">

            <!-- Formulario de filtros -->
            <form id="filtroProductos" method="POST" action="<?php echo SERVER_URL; ?>productos/">
                <div class="row">

                    <!-- Categoria -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_categoria" class="form-label">Categoría</label>
                            <select name="id_categoria" id="id_categoria" class="form-control">
                                <?php
                                require_once "./controladores/categoriaControlador.php";
                                $ins_categoria = new categoriaControlador();
                                $categorias = $ins_categoria->obtener_categorias_controlador();

                                if ($categorias) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($categorias as $categoria) {
                                        $selected = ($categoria->id == $_POST['id_categoria']) ? 'selected' : '';
                                        echo '<option value="' . $categoria->id . '" ' . $selected . '>' . $categoria->nombre . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay categorías disponibles</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Nombre -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_nombre">Nombre</label>
                            <select name="nombre" id="filtro_nombre" class="form-control">
                            <?php
                                require_once "./controladores/productoControlador.php";
                                $ins_producto = new productoControlador();
                                $productos = $ins_producto->obtener_productos_controlador();

                                if ($productos) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($productos as $producto) {
                                        $selected = ($producto->nombre == $_POST['nombre']) ? 'selected' : '';
                                        echo '<option value="' . $producto->nombre . '" ' . $selected . '>' . $producto->nombre . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay categorías disponibles</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <!-- Precio mínimo -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_precio_min">Precio Mínimo</label>
                            <input type="number" step="0.01" class="form-control" id="filtro_precio_min" name="precio_min" value="<?php echo isset($_POST['precio_min']) ? $_POST['precio_min'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Precio máximo -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_precio_max">Precio Máximo</label>
                            <input type="number" step="0.01" class="form-control" id="filtro_precio_max" name="precio_max" value="<?php echo isset($_POST['precio_max']) ? $_POST['precio_max'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Unidad de medida -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_unidad">Unidad de medida</label>
                            <select class="form-control" id="filtro_unidad" name="unidad_medida">
                                <option value="">Seleccione</option>
                                <option value="Unidad" <?php echo (isset($_POST['unidad_medida']) && $_POST['unidad_medida'] == 'Unidad') ? 'selected' : ''; ?>>Unidad</option>
                                <option value="Kilogramo" <?php echo (isset($_POST['unidad_medida']) && $_POST['unidad_medida'] == 'Kilogramo') ? 'selected' : ''; ?>>Kilogramo</option>
                                <option value="Libra" <?php echo (isset($_POST['unidad_medida']) && $_POST['unidad_medida'] == 'Libra') ? 'selected' : ''; ?>>Libra</option>
                            </select>
                        </div>
                    </div>

                    <!-- Stock mínimo -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_stock_min">Stock Mínimo</label>
                            <input type="number" class="form-control" id="filtro_stock_min" name="stock_min" value="<?php echo isset($_POST['stock_min']) ? $_POST['stock_min'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Stock máximo -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_stock_max">Stock Máximo</label>
                            <input type="number" class="form-control" id="filtro_stock_max" name="stock_max" value="<?php echo isset($_POST['stock_max']) ? $_POST['stock_max'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Filtro por precio (Mayor, Menor) -->
                    <div class="col-md-2">
                        <label for="filtro_precio">Filtrar por precio:</label>
                        <select name="precio" id="filtro_precio" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="Menor" <?php echo (isset($_POST['precio']) && $_POST['precio'] == 'Menor') ? 'selected' : ''; ?>>Menor</option>
                            <option value="Mayor" <?php echo (isset($_POST['precio']) && $_POST['precio'] == 'Mayor') ? 'selected' : ''; ?>>Mayor</option>
                        </select>
                    </div>

                    <!-- Filtro por stock (Mayor, Menor) -->
                    <div class="col-md-2">
                        <label for="filtro_stock">Filtrar por stock:</label>
                        <select name="stock" id="filtro_stock" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="Menor" <?php echo (isset($_POST['stock']) && $_POST['stock'] == 'Menor') ? 'selected' : ''; ?>>Menor</option>
                            <option value="Mayor" <?php echo (isset($_POST['stock']) && $_POST['stock'] == 'Mayor') ? 'selected' : ''; ?>>Mayor</option>
                        </select>
                    </div>

                    <!-- Botones (Buscar, Reestablecer) -->
                    <div class="col-md-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Buscar</button>
                        <a href="<?php echo SERVER_URL; ?>productos/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Producto -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Productos</h6>
        </div>
        
        <div class="card-body">
            <?php
            require_once "./controladores/productoControlador.php";
            $ins_producto = new productoControlador();

            $filtros = [
                'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
                'id_categoria' => isset($_POST['id_categoria']) ? trim($_POST['id_categoria']) : '',
                'precio_min' => isset($_POST['precio_min']) ? trim($_POST['precio_min']) : '',
                'precio_max' => isset($_POST['precio_max']) ? trim($_POST['precio_max']) : '',
                'unidad_medida' => isset($_POST['unidad_medida']) ? trim($_POST['unidad_medida']) : '',
                'stock_min' => isset($_POST['stock_min']) ? trim($_POST['stock_min']) : '',
                'stock_max' => isset($_POST['stock_max']) ? trim($_POST['stock_max']) : '',
                'stock' => isset($_POST['stock']) ? trim($_POST['stock']) : '',
                'precio' => isset($_POST['precio']) ? trim($_POST['precio']) : ''
            ];

            echo $ins_producto->paginar_producto_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>
</div>