<?php
    if ($_SESSION['rol_sca'] != "Administrador") {
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }
?>

<!-- Categorías -->
<div class="container-fluid">

    <!-- Cabecera de página principal -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Categorías</h1>

        <!-- Añadir categoría -->
        <a href="<?php echo SERVER_URL; ?>categoria-new/" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Añadir</span>
        </a>
    </div>

    <!-- Filtro de Categorías -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de Categorías</h6>
        </div>

        <div class="card-body">
            <form id="filtroCategorias" method="POST" action="<?php echo SERVER_URL; ?>categorias/">
                <div class="row">

                    <!-- Id -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_id">Id</label>
                            <select name="id" id="filtro_id" class="form-control">
                                <?php
                                require_once "./controladores/categoriaControlador.php";
                                $ins_categoria = new categoriaControlador();
                                $categorias = $ins_categoria->obtener_categorias_controlador();

                                if ($categorias) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($categorias as $categoria) {
                                        $selected = ($categoria->id == $_POST['id']) ? 'selected' : '';
                                        echo '<option value="' . $categoria->id . '" ' . $selected . '>' . $categoria->id . '</option>';
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
                                require_once "./controladores/categoriaControlador.php";
                                $ins_categoria = new categoriaControlador();
                                $categorias = $ins_categoria->obtener_categorias_controlador();

                                if ($categorias) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($categorias as $categoria) {
                                        $selected = ($categoria->nombre == $_POST['nombre']) ? 'selected' : '';
                                        echo '<option value="' . $categoria->nombre . '" ' . $selected . '>' . $categoria->nombre . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay categorías disponibles</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filtro_descripcion">Descripción</label>
                            <input type="text" class="form-control" id="filtro_descripcion" name="descripcion" value="<?php echo isset($_POST['descripcion']) ? $_POST['descripcion'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Ordenar por:(A-Z, Z-A) -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_orden">Ordenar categorias de:</label>
                            <select class="form-control" id="filtro_orden" name="orden">
                                <option value="">Seleccione</option>
                                <option value="ASC" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'ASC') ? 'selected' : ''; ?> >A-Z</option>
                                <option value="DESC" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'DESC') ? 'selected' : ''; ?> >Z-A</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botones -> Buscar | Reestablecer -->
                    <div class="col-md-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Buscar</button>
                        <a href="<?php echo SERVER_URL; ?>categorias/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Categorías -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Categorías</h6>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/categoriaControlador.php";
                $ins_categoria = new categoriaControlador();

                $filtros = [
                    'id' => isset($_POST['id']) ? trim($_POST['id']) : '',
                    'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
                    'descripcion' => isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '',
                    'orden' => isset($_POST['orden']) ? trim($_POST['orden']) : ''
                ];

                echo $ins_categoria->paginar_categoria_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>
</div>