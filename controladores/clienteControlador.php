<?php

    if ($peticionAjax) {
        require_once "../modelos/clienteModelo.php";
    } else {
        require_once "./modelos/clienteModelo.php";
    }

    class clienteControlador extends clienteModelo {

        //---------- Controlador para agregar cliente -------------//
        public function agregar_cliente_controlador() {

            $cedula = $_POST['cedula'];
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];

            //---------- Comprobar campos vacios ------------//
            if ($cedula == "" || $nombre == "" || $apellidos == "" || $telefono == "" || $direccion == "") {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No haz llenado todos los campos que son obligatorios",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando la cedula del cliente -------------//
            $check_cliente = mainModel::ejecutar_consulta_simple("SELECT cedula FROM cliente WHERE cedula = '$cedula';");
            if ($check_cliente->rowCount() > 0) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El cliente ingresado ya se encuentra registrado en el sistema",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos_cliente = [
                "Cedula"=>$cedula,
                "Nombre"=>$nombre,
                "Apellidos"=>$apellidos,
                "Telefono"=>$telefono,
                "Direccion"=>$direccion
            ];

            $agregar_cliente = clienteModelo::agregar_cliente_modelo($datos_cliente);

            if ($agregar_cliente->rowCount() == 1) {
                $alerta = [
                    "Alerta"=>"limpiar",
                    "Titulo"=>"cliente registrado",
                    "Texto"=>"Los datos del cliente han sido registrados con éxito",
                    "Tipo"=>"success",
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No hemos podido registrar al cliente",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
            }
        } //Fin

        //---------- Controlador para paginar clientes -------------//
        public function paginar_cliente_controlador($pagina, $registros, $url, $filtros) {

            $url = SERVER_URL . $url . "/";
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

            $where = ""; // Inicializa como una cadena vacía

            // Procesar filtros
            $conditions = []; // Array para almacenar las condiciones
            
            if (isset($filtros['cedula']) && $filtros['cedula'] != "") {
                $conditions[] = "cedula LIKE '%" . $filtros['cedula'] . "%'";
            }
            if (isset($filtros['nombre']) && $filtros['nombre'] != "") {
                $conditions[] = "nombre LIKE '%" . $filtros['nombre'] . "%'";
            }
            if (isset($filtros['telefono']) && $filtros['telefono'] != "") {
                $conditions[] = "telefono LIKE '%" . $filtros['telefono'] . "%'";
            }
            if (isset($filtros['direccion']) && $filtros['direccion'] != "") {
                $conditions[] = "direccion LIKE '%" . $filtros['direccion'] . "%'";
            }
            
            // Solo añadir WHERE si hay condiciones
            if (!empty($conditions)) {
                $where = "WHERE " . implode(" AND ", $conditions);
            }
            
            // Ordenar por nombre (A-Z o Z-A)
            $orden = "";
            if (isset($filtros['orden']) && in_array($filtros['orden'], ['asc', 'desc'])) {
                $orden = "ORDER BY nombre " . strtoupper($filtros['orden']);
            }
            
            // Construir consultas finales
            $consulta = "SELECT * FROM cliente $where $orden LIMIT $inicio, $registros;";
            $consulta_total = "SELECT COUNT(*) FROM cliente $where;";

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
                            <th scope="col">Cédula</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Dirección</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            if ($total >= 1 && $pagina <= $n_paginas) {
                $contador = $inicio + 1;
                $reg_inicio = $inicio + 1;
                foreach ($datos as $rows) {
                    $tabla .= '
                    <tr>
                        <td>' . $rows['cedula'] . '</td>
                        <td>' . $rows['nombre'].' '.$rows['apellidos']. '</td>
                        <td>' . $rows['telefono'] . '</td>
                        <td>' . $rows['direccion'] . '</td>
                        <td>
                            <a href="' . SERVER_URL . 'cliente-update/' . $rows['cedula'] . '/" class="btn btn-small btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form class="FormularioAjax d-inline-block" action="' . SERVER_URL . 'ajax/clienteAjax.php" method="POST" data-form="delete">
                                <input type="hidden" name="cedula_eliminar" value="' . $rows['cedula'] . '">
                                <button type="submit" class="btn btn-small btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    ';
                    $contador++;
                }
                $reg_final = $contador - 1;
            } else {
                if ($total >= 1) {
                    $tabla .= '
                    <tr class="text-center">
                        <td colspan="6">
                            <a href="' . $url . '1/" class="btn btn-primary">
                                Haga clic acá para recargar el listado
                            </a>
                        </td>
                    </tr>
                    ';
                } else {
                    $tabla .= '
                    <tr class="text-center">
                        <td colspan="6">No hay registros en el sistema</td>
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
                $tabla .= '<p class="text-left">Mostrando cliente ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 10);
            }

            return $tabla;
        } // Fin

        //---------- Controlador eliminar cliente -----------------//
        public function eliminar_cliente_controlador() {

            // Recibiendo cedula del cliente
            $cedula = $_POST['cedula_eliminar'];

            //------------ Comprobando el cliente en BD ------------//
            $check_cliente = mainModel::ejecutar_consulta_simple("SELECT cedula FROM cliente WHERE cedula = '$cedula';");
            if ($check_cliente->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'El cliente que intenta eliminar no existe en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando las ventas asociadas ------------//
            $check_ventas = mainModel::ejecutar_consulta_simple("SELECT cedula_cliente FROM venta WHERE cedula_cliente = '$cedula' LIMIT 1;");
            if ($check_ventas->rowCount() > 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No podemos eliminar este cliente debido a que tiene ventas asociadas',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            $eliminar_cliente = clienteModelo::eliminar_cliente_modelo($cedula);

            if ($eliminar_cliente->rowCount() == 1) {
                $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'cliente eliminado',
                    "Texto" => 'El cliente ha sido eliminado del sistema exitosamente',
                    "Tipo" => 'success'
                ];
            } else {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos podido eliminar al cliente, intentelo nuevamente',
                    "Tipo" => 'error'
                ];
            }
            echo json_encode($alerta);
        } //Fin

        //---------- Controlador para obtener cliente -------------//
        public function obtener_cliente_controlador($cedula) {
            return clienteModelo::obtener_cliente_modelo($cedula);
        } // Fin

        //---------- Controlador para obtener la cantidad de clientes -------------//
        public function obtener_cantidad_clientes_controlador() {
            return clienteModelo::obtener_cantidad_clientes_modelo();
        } // Fin

        //---------- Controlador para obtener todos los clientes -------------//
        public function obtener_clientes_controlador() {
            return clienteModelo::obtener_clientes_modelo();
        } // Fin

        //---------- Controlador para actualizar los datos del cliente -------------//
        public function actualizar_cliente_controlador() {
            
            $cedula = $_POST['cedula_actualizar'];

            // Comprobar la cliente en la BD
            $check_cliente = mainModel::ejecutar_consulta_simple("SELECT * FROM cliente WHERE cedula = '$cedula'");
            if ($check_cliente->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos encontrado al cliente en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $datos = $check_cliente->fetch();
            }

            $nombre = $_POST['nombre_actualizar'];
            $apellidos = $_POST['apellidos_actualizar'];
            $telefono = $_POST['telefono_actualizar'];
            $direccion = $_POST['direccion_actualizar'];

            //---------- Comprobar campos vacios ------------//
            if ($nombre == "" || $apellidos == "" || $telefono == "" || $direccion == "") {
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
                "Cedula" => $cedula,
                "Nombre" => $nombre,
                "Apellidos" => $apellidos,
                "Telefono" => $telefono,
                "Direccion" => $direccion,
            ];

            $actualizar_cliente = clienteModelo::actualizar_cliente_modelo($datos);

            if ($actualizar_cliente->rowCount() == 1) {
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