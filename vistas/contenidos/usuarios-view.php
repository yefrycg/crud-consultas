<?php
if ($_SESSION['rol_sca'] != "Administrador") {
    echo $lc->forzar_cierre_sesion_controlador();
    exit();
}
?>


<!-- Usuarios -->
<div class="container-fluid">

    <!-- Cabecera de página -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Usuarios</h1>

        <!-- Boton añadir -->
        <a href="<?php echo SERVER_URL; ?>usuario-new/" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Añadir</span>
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de usuarios</h6>
        </div>

        <div class="card-body">

            <!-- Formulario de filtros -->
            <form id="filtroUsuarios" method="POST" action="<?php echo SERVER_URL; ?>usuarios/">
                <div class="row">

                    <!-- Id -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_id">Id</label>
                            <select name="id" id="filtro_id" class="form-control">
                            <?php
                                require_once "./controladores/usuarioControlador.php";
                                $ins_usuario = new usuarioControlador();
                                $usuarios = $ins_usuario->obtener_usuarios_controlador();

                                if ($usuarios) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($usuarios as $usuario) {
                                        $selected = ($usuario->id == $_POST['id']) ? 'selected' : '';
                                        echo '<option value="' . $usuario->id . '" ' . $selected . '>' . $usuario->id . '</option>';
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
                                require_once "./controladores/usuarioControlador.php";
                                $ins_usuario = new usuarioControlador();
                                $usuarios = $ins_usuario->obtener_usuarios_controlador();

                                if ($usuarios) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($usuarios as $usuario) {
                                        $selected = ($usuario->nombre == $_POST['nombre']) ? 'selected' : '';
                                        echo '<option value="' . $usuario->nombre . '" ' . $selected . '>' . $usuario->nombre . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay categorías disponibles</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <!-- Rol -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_rol">Rol</label>
                            <select class="form-control" id="filtro_rol" name="rol">
                                <option value="">Seleccione</option>
                                <option value="Administrador" <?php echo (isset($_POST['rol']) && $_POST['rol'] == 'Administrador') ? 'selected' : ''; ?>>Administrador</option>
                                <option value="Vendedor" <?php echo (isset($_POST['rol']) && $_POST['rol'] == 'Vendedor') ? 'selected' : ''; ?>>Vendedor</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botones (Buscar, Reestablecer) -->
                    <div class="col-md-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Buscar</button>
                        <a href="<?php echo SERVER_URL; ?>usuarios/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Usuarios -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Usuarios</h6>
        </div>

        <div class="card-body">
            <?php
            require_once "./controladores/usuarioControlador.php";
            $ins_usuario = new usuarioControlador();
            $filtros = [
                'id' => isset($_POST['id']) ? trim($_POST['id']) : '',
                'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
                'rol' => isset($_POST['rol']) ? trim($_POST['rol']) : ''
            ];

            echo $ins_usuario->paginar_usuario_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>
</div>