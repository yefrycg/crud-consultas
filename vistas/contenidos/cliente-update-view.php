<!-- Actualizar cliente -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 600px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Editar Cliente</h6>

            <!-- Boton regresar -->
            <a href="<?php echo SERVER_URL; ?>clientes/" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Regresar</span>
            </a>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/clienteControlador.php";
                $ins_cliente = new clienteControlador();
                $datos_cliente = $ins_cliente->obtener_cliente_controlador($pagina[1]);

                if($datos_cliente->rowCount() == 1) {
                    $campos = $datos_cliente->fetch();
            ?>

            <!-- Formulario actualizar cliente -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/clienteAjax.php" method="POST" data-form="update">

                <!-- Cédula -->
                <div class="mb-3">
                    <label for="cedula_actualizar" class="form-label">Cédula</label>
                    <input type="text" name="cedula_actualizar" id="cedula_actualizar" class="form-control" value="<?php echo $pagina[1]; ?>" maxlength="10" readonly>
                </div>

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre_actualizar" class="form-label">Nombre</label>
                    <input type="text" name="nombre_actualizar" id="nombre_actualizar" class="form-control" value="<?php echo $campos['nombre']; ?>" maxlength="20" required>
                </div>

                <!-- Apellidos -->
                <div class="mb-3">
                    <label for="apellidos_actualizar" class="form-label">Apellidos</label>
                    <input type="text" name="apellidos_actualizar" id="apellidos_actualizar" class="form-control" value="<?php echo $campos['apellidos']; ?>" maxlength="40" required>
                </div>

                <!-- Teléfono -->
                <div class="mb-3">
                    <label for="telefono_actualizar" class="form-label">Teléfono</label>
                    <input type="text" name="telefono_actualizar" id="telefono_actualizar" class="form-control" value="<?php echo $campos['telefono']; ?>" maxlength="10" required>
                </div>

                <!-- Dirección -->
                <div class="mb-3">
                    <label for="direccion_actualizar" class="form-label">Dirección</label>
                    <input type="text" name="direccion_actualizar" id="direccion_actualizar" class="form-control" value="<?php echo $campos['direccion']; ?>" maxlength="50" required>
                </div>

                <!-- Boton Actualizar -->
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