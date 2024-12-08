<?php

    if ($peticionAjax) {
        require_once "../modelos/usuarioModelo.php";
    } else {
        require_once "./modelos/usuarioModelo.php";
    }

    class usuarioControlador extends usuarioModelo {

        //---------- Controlador para agregar usuario -------------//
        public function agregar_usuario_controlador() {

            $nombre = $_POST['nombre'];
            $contrasena = $_POST['contrasena'];
            $rol = $_POST['rol'];

            //---------- Comprobar campos vacios ------------//
            if ($nombre == "" || $contrasena == "" || $rol == "") {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No haz llenado todos los campos que son obligatorios",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando el nombre del usuario -------------//
            $check_usuario = mainModel::ejecutar_consulta_simple("SELECT nombre FROM usuario WHERE nombre = '$nombre';");
            if ($check_usuario->rowCount() > 0) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El usuario ingresado ya se encuentra registrado en el sistema",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos_usuario = [
                "Nombre"=>$nombre,
                "Contrasena"=>$contrasena,
                "Rol"=>$rol
            ];

            $agregar_usuario = usuarioModelo::agregar_usuario_modelo($datos_usuario);

            if ($agregar_usuario->rowCount() == 1) {
                $alerta = [
                    "Alerta"=>"limpiar",
                    "Titulo"=>"Usuario registrado",
                    "Texto"=>"Los datos del usuario han sido registrados con éxito",
                    "Tipo"=>"success",
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No hemos podido registrar al usuario",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
            }
            
        }

        //---------- Controlador para paginar usuarios -------------//
        public function paginar_usuario_controlador($pagina, $registros, $url, $filtros) {

            $url = SERVER_URL . $url . "/";
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

            $where = "WHERE id != 1"; // Excluir el ID del administrador principal

            // Procesar filtros
            if (isset($filtros['id']) && $filtros['id'] != "") {
                $where .= " AND id LIKE '%" . $filtros['id'] . "%'";
            }
            if (isset($filtros['nombre']) && $filtros['nombre'] != "") {
                $where .= " AND nombre LIKE '%" . $filtros['nombre'] . "%'";
            }
            if (isset($filtros['rol']) && $filtros['rol'] != "") {
                $where .= " AND rol = '" . $filtros['rol'] . "'";
            }
        
            $consulta = "SELECT * FROM usuario $where LIMIT $inicio, $registros;";
            $consulta_total = "SELECT COUNT(*) FROM usuario $where;";
        

            $conexion = mainModel::conectar();

            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();

            $total = $conexion->query($consulta_total);
            $total = (int) $total->fetchColumn();

            $n_paginas = ceil($total / $registros);

            $tabla .= '
            <div class="table-responsive mb-2">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Contraseña</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            if ($total >= 1 && $pagina <= $n_paginas) {
                $contador = $inicio + 1;
                $reg_inicio = $inicio + 1;
                foreach ($datos as $rows) {
                    if ($_SESSION['id_sca'] != $rows['id']) {
                        $tabla .= '
                        <tr>
                        <td>' . $rows['id'] . '</td>
                        <td>' . $rows['nombre'] . '</td>
                        <td>' . $rows['contrasena'] . '</td>
                        <td>' . $rows['rol'] . '</td>
                        <td>
                        <a href="' . SERVER_URL . 'usuario-update/' . $rows['id'] . '/" class="btn btn-small btn-warning">
                        <i class="fas fa-edit"></i>
                        </a>
                        <form class="FormularioAjax d-inline-block" action="' . SERVER_URL . 'ajax/usuarioAjax.php" method="POST" data-form="delete">
                        <input type="hidden" name="id_eliminar" value="' . $rows['id'] . '">
                        <button type="submit" class="btn btn-small btn-danger">
                        <i class="fas fa-trash"></i>
                        </button>
                        </form>
                        </td>
                        </tr>
                        ';
                        $contador++;
                    }
                }
                $reg_final = $contador - 1;
            } else {
                if ($total >= 1) {
                    $tabla .= '
                    <tr class="text-center">
                        <td colspan="5">
                            <a href="' . $url . '1/" class="btn btn-primary">
                                Haga clic acá para recargar el listado
                            </a>
                        </td>
                    </tr>
                    ';
                } else {
                    $tabla .= '
                    <tr class="text-center">
                        <td colspan="5">No hay registros en el sistema</td>
                    </tr>
                    ';
                }
            }

            $tabla .= '
                    </tbody>
                </table>
            </div>
            ';

            if ($total >= 1 && $pagina <= $n_paginas) {
                $tabla .= '<p class="text-left">Mostrando usuario ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 10);
            }

            return $tabla;
        } // Fin

        //---------- Controlador eliminar usuario -----------------//
        public function eliminar_usuario_controlador() {
            
            // Recibiendo id del usuario
            $id = $_POST['id_eliminar'];

            //------------ Comprobando el usuario en BD ------------//
            $check_usuario = mainModel::ejecutar_consulta_simple("SELECT id FROM usuario WHERE id != 1 AND id = '$id';");
            if ($check_usuario->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'El usuario que intenta eliminar no existe en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando las ventas asociadas ------------//
            $check_ventas = mainModel::ejecutar_consulta_simple("SELECT cod_vendedor FROM venta WHERE cod_vendedor = '$id' LIMIT 1;");
            if ($check_ventas->rowCount() > 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No podemos eliminar este usuario debido a que tiene ventas asociadas',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            $eliminar_usuario = usuarioModelo::eliminar_usuario_modelo($id);

            if ($eliminar_usuario->rowCount() == 1) {
                $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'usuario eliminado',
                    "Texto" => 'El usuario ha sido eliminado del sistema exitosamente',
                    "Tipo" => 'success'
                ];
            } else {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos podido eliminar al usuario, intentelo nuevamente',
                    "Tipo" => 'error'
                ];
            }
            echo json_encode($alerta);
        } //Fin

        //---------- Controlador para obtener usuario -------------//
        public function obtener_usuario_controlador($id) {
            return usuarioModelo::obtener_usuario_modelo($id);
        } // Fin

        //---------- Controlador para obtener usuario -------------//
        public function obtener_cantidad_usuarios_controlador() {
            return usuarioModelo::obtener_cantidad_usuarios_modelo();
        } // Fin

        //---------- Controlador para obtener todos los usuarios -------------//
        public function obtener_usuarios_controlador() {
            return usuarioModelo::obtener_usuarios_modelo();
        } // Fin

        //---------- Controlador para actualizar los datos del usuario -------------//
        public function actualizar_usuario_controlador() {

            $id = $_POST['id_actualizar'];

            // Comprobar al usuario en la BD
            $check_usuario = mainModel::ejecutar_consulta_simple("SELECT * FROM usuario WHERE id = '$id'");
            if ($check_usuario->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos encontrado al usuario en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $datos = $check_usuario->fetch();
            }

            $nombre = $_POST['nombre_actualizar'];
            $contrasena = $_POST['contrasena_actualizar'];
            $rol = $_POST['rol_actualizar'];

            //---------- Comprobar campos vacios ------------//
            if ($nombre == "" || $rol == "" || $contrasena == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No haz llenado todos los campos que son obligatorios",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos = [
                "Id" => $id,
                "Nombre" => $nombre,
                "Contrasena" => $contrasena,
                "Rol" => $rol
            ];

            $actualizar_usuario = usuarioModelo::actualizar_usuario_modelo($datos);

            if ($actualizar_usuario->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Titulo" => "Datos actualizados",
                    "Texto" => "Los datos han sido actualizados con éxito",
                    "Tipo" => "success",
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido actualizar los datos",
                    "Tipo" => "error",
                ];
            }
            echo json_encode($alerta);
        } //Fin
    }