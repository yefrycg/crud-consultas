<?php

    if ($peticionAjax) {
        require_once "../modelos/ventaModelo.php";
    } else {
        require_once "./modelos/ventaModelo.php";
    }

    class ventaControlador extends ventaModelo {
        
        //---------- Controlador para finalizar una venta -----------------//
        public function finalizar_venta_controlador() {
            try {
                $cedula_cliente = $_POST['cliente_id'];
                $cod_vendedor = $_POST['vendedor_id']; // Suponiendo que es el ID del vendedor logueado
                $tipo_venta = $_POST['tipo_venta'];
                $direccion_entrega = $_POST['direccion_entrega'] ?? "NO APLICA";
                $tipo_comprobante = $_POST['comprobante_id'];
                $detalles = json_decode($_POST['detalles'], true);
                $total_venta = $_POST['total'];
                $impuesto = $total_venta * 0.19;

                if (!$cedula_cliente || !$cod_vendedor || !$tipo_venta || !$tipo_comprobante || !$detalles) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Datos incompletos o mal formateados",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }

                // Insertar el comprobante
                $id_comprobante = ventaModelo::agregar_comprobante_modelo(["Tipo_comprobante" => $tipo_comprobante]);
                if (!$id_comprobante) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Error al registrar el comprobante.",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }

                // Insertar la venta
                $venta_id = ventaModelo::agregar_venta_modelo([
                    "Cedula_cliente" => $cedula_cliente,
                    "Cod_vendedor" => $cod_vendedor,
                    "Id_comprobante" => $id_comprobante,
                    "Fecha_hora" => date("Y-m-d H:i:s"),
                    "Tipo_venta" => $tipo_venta,
                    "Direccion_entrega" => $direccion_entrega,
                    "Total_venta" => $total_venta,
                    "Impuesto" => $impuesto
                ]);
                if (!$venta_id) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Error al registrar la venta.",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }

                // Insertar los productos de la venta
                foreach ($detalles as $detalle) {
                    $resultado = ventaModelo::agregar_venta_producto_modelo([
                        "Id_venta" => $venta_id,
                        "Cod_producto" => $detalle["productoId"],
                        "Cantidad" => $detalle["cantidad"],
                        "Precio" => $detalle["precioVenta"],
                        "Descuento" => $detalle["descuento"]
                    ]);

                    if (!$resultado) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "Error al registrar un detalle de la venta.",
                            "Tipo" => "error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                }
                $detalles = json_encode($detalles);
                $alerta = [
                    "Alerta" => "limpiar",
                    "Titulo" => "Compra registrada",
                    "Texto" => "La compra fue registrada exitosamente.",
                    "Tipo" => "success"
                ];
                echo json_encode($alerta);
            } catch (Exception $e) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => $e->getMessage(),
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
            }
        } //Fin

        //---------- Controlador para paginar ventas -----------------//
        public function paginar_venta_controlador($pagina, $registros, $url, $filtros) {

            $url = SERVER_URL . $url . "/";
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

            $where = "WHERE 1=1"; // Condición base

            // Filtros básicos
            if (isset($filtros['id']) && $filtros['id'] != "") {
                $where .= " AND venta.id = " . (int)$filtros['id'];
            }
            if (isset($filtros['comprobante']) && $filtros['comprobante'] != "") {
                $where .= " AND comprobante.tipo_comprobante = '" . $filtros['comprobante'] . "'";
            }
            if (isset($filtros['cliente']) && $filtros['cliente'] != "") {
                $where .= " AND (cliente.nombre LIKE '%" . $filtros['cliente'] . "%' OR cliente.apellidos LIKE '%" . $filtros['cliente'] . "%')";
            }
            if (isset($filtros['fecha_desde']) && $filtros['fecha_desde'] != "") {
                $where .= " AND DATE(venta.fecha_hora) >= '" . $filtros['fecha_desde'] . "'";
            }
            if (isset($filtros['fecha_hasta']) && $filtros['fecha_hasta'] != "") {
                $where .= " AND DATE(venta.fecha_hora) <= '" . $filtros['fecha_hasta'] . "'";
            }
            if (isset($filtros['vendedor']) && $filtros['vendedor'] != "") {
                $where .= " AND usuario.nombre LIKE '%" . $filtros['vendedor'] . "%'";
            }
            if (isset($filtros['total_desde']) && $filtros['total_desde'] != "") {
                $where .= " AND (venta.total_venta + venta.impuesto) >= " . (float)$filtros['total_desde'];
            }
            if (isset($filtros['total_hasta']) && $filtros['total_hasta'] != "") {
                $where .= " AND (venta.total_venta + venta.impuesto) <= " . (float)$filtros['total_hasta'];
            }

            // Filtros avanzados para totales
            if (isset($filtros['total']) && $filtros['total'] != "") {
                $subWhere = str_replace("venta.", "v.", $where); // Adaptar alias para la subconsulta
                $subWhere = str_replace("usuario.", "u.", $subWhere); // Adaptar alias para la subconsulta
                $subWhere = str_replace("cliente.", "cl.", $subWhere); // Adaptar alias para la subconsulta
                $subWhere = str_replace("comprobante.", "co.", $subWhere); // Adaptar alias para la subconsulta
                if ($filtros['total'] == "Mayor") {
                    $where .= " AND (venta.total_venta + venta.impuesto) = (SELECT MAX(v.total_venta + v.impuesto) FROM venta AS v 
                    INNER JOIN cliente AS cl ON v.cedula_cliente = cl.cedula 
                    INNER JOIN usuario AS u  ON v.cod_vendedor = u.id 
                    INNER JOIN comprobante AS co ON v.id_comprobante = co.id " . $subWhere . ")";
                } else {
                    $where .= " AND (venta.total_venta + venta.impuesto) = (SELECT MIN(v.total_venta + v.impuesto) FROM venta AS v 
                    INNER JOIN cliente AS cl ON v.cedula_cliente = cl.cedula 
                    INNER JOIN usuario AS u  ON v.cod_vendedor = u.id 
                    INNER JOIN comprobante AS co ON v.id_comprobante = co.id " . $subWhere . ")";
                }
            }

            // Orden
            $orderBy = "ORDER BY venta.id DESC";
            if (isset($filtros['orden']) && $filtros['orden'] != "") {
                $orden = ($filtros['orden'] === 'ASC') ? 'ASC' : 'DESC';
                $orderBy = "ORDER BY cliente.nombre $orden";
            }

            // Consulta principal
            $consulta = "SELECT venta.*, 
                CONCAT_WS(' ', cliente.nombre, cliente.apellidos) AS nombre_cliente, 
                usuario.nombre AS nombre_vendedor, 
                comprobante.tipo_comprobante
            FROM venta
            INNER JOIN cliente ON venta.cedula_cliente = cliente.cedula
            INNER JOIN usuario ON venta.cod_vendedor = usuario.id
            INNER JOIN comprobante ON venta.id_comprobante = comprobante.id
            $where
            $orderBy
            LIMIT $inicio, $registros;";

            // Consulta para el total
            $consulta_total = "SELECT COUNT(*) AS total
            FROM venta
            INNER JOIN cliente ON venta.cedula_cliente = cliente.cedula
            INNER JOIN usuario ON venta.cod_vendedor = usuario.id
            INNER JOIN comprobante ON venta.id_comprobante = comprobante.id
            $where;";

            // Conexión y ejecución
            $conexion = mainModel::conectar();

            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();

            $total = $conexion->query($consulta_total);
            $total = (int)$total->fetchColumn();

            $n_paginas = ceil($total / $registros);

            // Construir tabla
            $tabla .= '<div class="table-responsive mb-2">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Comprobante</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Fecha y Hora</th>
                            <th scope="col">Vendedor</th>
                            <th scope="col">Total</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>';

            if ($total >= 1 && $pagina <= $n_paginas) {
                $contador = $inicio + 1;
                $reg_inicio = $inicio + 1;
                foreach ($datos as $rows) {
                    $tabla .= '
                        <tr>
                            <td>' . $rows['id'] . '</td>
                            <td>' . $rows['tipo_comprobante'] . '</td>
                            <td>' . $rows['nombre_cliente'] . '</td>
                            <td>' . $rows['fecha_hora'] . '</td>
                            <td>' . $rows['nombre_vendedor'] . '</td>
                            <td>' . $rows['total_venta'] + $rows['impuesto'] . '</td>
                            <td>
                                <form class="FormularioAjax d-inline-block" action="' . SERVER_URL . 'ajax/ventaAjax.php" method="POST" data-form="delete">
                                    <input type="hidden" name="id_eliminar" value="' . $rows['id'] . '">
                                    <button type="submit" class="btn btn-small btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>';
                    $contador++;
                }
                $reg_final = $contador - 1;
            } else {
                $tabla .= '<tr class="text-center"><td colspan="7">No hay registros en el sistema</td></tr>';
            }

            $tabla .= '</tbody></table></div>';

            if ($total >= 1 && $pagina <= $n_paginas) {
                $tabla .= '<p class="text-left">Mostrando venta ' . $reg_inicio . ' a la ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 10);
            }

            return $tabla;
        } //Fin
        
        //---------- Controlador eliminar venta -----------------//
        public function eliminar_venta_controlador() {

            // Recibiendo id de la venta
            $id = $_POST['id_eliminar'];

            //------------ Comprobando la venta en BD ------------//
            $check_venta = mainModel::ejecutar_consulta_simple("SELECT id FROM venta WHERE id = '$id';");
            if ($check_venta->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'La venta que intenta eliminar no existe en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Eliminando venta ------------//
            $eliminar_venta = ventaModelo::eliminar_venta_modelo($id);

            if ($eliminar_venta->rowCount() == 1) {
                $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'venta eliminada',
                    "Texto" => 'La venta ha sido eliminada del sistema exitosamente',
                    "Tipo" => 'success'
                ];
            } else {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos podido eliminar la venta, inténtelo nuevamente',
                    "Tipo" => 'error'
                ];
            }
            echo json_encode($alerta);
        } //Fin

        //---------- Controlador para obtener ventas -------------//
        public function obtener_ventas_controlador() {
            return ventaModelo::obtener_ventas_modelo();
        } //Fin

        //---------- Controlador para obtener cantidad de ventas -------------//
        public function obtener_cantidad_ventas_controlador() {
            return ventaModelo::obtener_cantidad_ventas_modelo();
        } //Fin
    }