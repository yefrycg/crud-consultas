<?php
if ($_SESSION['rol_sca'] != "Administrador") {
    echo $lc->forzar_cierre_sesion_controlador();
    exit();
}
?>

<!-- Proveedores -->
<div class="container-fluid">

    <!-- Cabecera de página -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Proveedores</h1>
        <a href="<?php echo SERVER_URL; ?>proveedor-new/" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Añadir</span>
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de proveedores</h6>
        </div>

        <div class="card-body">

            <!-- Formulario de filtros -->
            <form id="filtroProveedores" method="POST" action="<?php echo SERVER_URL; ?>proveedores/">
                <div class="row">

                    <!-- RUT -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_rut">RUT</label>
                            <select name="rut" id="filtro_rut" class="form-control">
                            <?php
                                require_once "./controladores/proveedorControlador.php";
                                $ins_proveedor = new proveedorControlador();
                                $proveedores = $ins_proveedor->obtener_proveedores_controlador();

                                if ($proveedores) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($proveedores as $proveedor) {
                                        $selected = ($proveedor->rut == $_POST['rut']) ? 'selected' : '';
                                        echo '<option value="' . $proveedor->rut . '" ' . $selected . '>' . $proveedor->rut . '</option>';
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
                                require_once "./controladores/proveedorControlador.php";
                                $ins_proveedor = new proveedorControlador();
                                $proveedores = $ins_proveedor->obtener_proveedores_controlador();

                                if ($proveedores) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($proveedores as $proveedor) {
                                        $selected = ($proveedor->nombre == $_POST['nombre']) ? 'selected' : '';
                                        echo '<option value="' . $proveedor->nombre . '" ' . $selected . '>' . $proveedor->nombre . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay categorías disponibles</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_contacto">Contacto</label>
                            <input type="text" class="form-control" id="filtro_contacto" name="contacto" value="<?php echo isset($_POST['contacto']) ? $_POST['contacto'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filtro_direccion">Dirección</label>
                            <input type="text" class="form-control" id="filtro_direccion" name="direccion" value="<?php echo isset($_POST['direccion']) ? $_POST['direccion'] : ''; ?>">
                        </div>
                    </div>

                    <!-- filtro de orden -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_orden">Ordenar proveedores de:</label>
                            <select class="form-control" id="filtro_orden" name="orden">
                                <option value="">Seleccione</option>
                                <option value="asc" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'asc') ? 'selected' : ''; ?>>A-Z</option>
                                <option value="desc" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'desc') ? 'selected' : ''; ?>>Z-A</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botones (Buscar, Reestablecer) -->
                    <div class="col-md-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Buscar</button>
                        <a href="<?php echo SERVER_URL; ?>proveedores/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Proveedores -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Proveedores</h6>
        </div>

        <div class="card-body">
            <?php
            require_once "./controladores/proveedorControlador.php";
            $ins_proveedor = new proveedorControlador();
            $filtros = [
                'rut' => isset($_POST['rut']) ? trim($_POST['rut']) : '',
                'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
                'contacto' => isset($_POST['contacto']) ? trim($_POST['contacto']) : '',
                'direccion' => isset($_POST['direccion']) ? trim($_POST['direccion']) : '',
                'orden' => isset($_POST['orden']) ? trim($_POST['orden']) : ''
            ];

            echo $ins_proveedor->paginar_proveedor_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>
</div>