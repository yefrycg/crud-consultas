<?php
    if ($peticionAjax) {
        require_once "../modelos/compraModelo.php";
    } else {
        require_once "./modelos/compraModelo.php";
    }

    class compraControlador extends compraModelo {

        //---------- Controlador para finalizar una compra -----------------//
        public function finalizar_compra_controlador() {
            try {
                $rut_proveedor = $_POST['rut_proveedor'];
                $tipo_comprobante = $_POST['id_comprobante'];
                $detalles = json_decode($_POST['detalles'], true);
                $total_compra = $_POST['total'];
                $impuesto = $total_compra * 0.19;

                if (!$rut_proveedor || !$tipo_comprobante || !$detalles) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Datos incompletos.",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }

                // Insertar el comprobante
                $id_comprobante = compraModelo::agregar_comprobante_modelo(["Tipo_comprobante" => $tipo_comprobante]);
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

                // Insertar la compra
                $compra_id = compraModelo::agregar_compra_modelo([
                    "Rut_proveedor" => $rut_proveedor,
                    "Id_comprobante" => $id_comprobante,
                    "Fecha_hora" => date("Y-m-d H:i:s"),
                    "Total_compra" => $total_compra,
                    "Impuesto" => $impuesto
                ]);
                if (!$compra_id) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Error al registrar la compra.",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }

                // Insertar los detalles de la compra
                foreach ($detalles as $detalle) {
                    $resultado = compraModelo::agregar_compra_producto_modelo([
                        "Id_compra" => $compra_id,
                        "Cod_producto" => $detalle["productoId"],
                        "Cantidad" => $detalle["cantidad"],
                        "Precio_compra" => $detalle["precioCompra"],
                        "Precio_venta" => $detalle["precioVenta"]
                    ]);
                    if (!$resultado) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "Error al registrar un detalle de la compra.",
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
        }

        //---------- Controlador paginar compras -----------------//
        public function paginar_compra_controlador($pagina, $registros, $url, $filtros) {
            $url = SERVER_URL . $url . "/";
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

            $where = "WHERE 1=1"; // Condición base

            // Filtros básicos
            if (isset($filtros['id']) && $filtros['id'] != "") {
                $where .= " AND compra.id = " . (int)$filtros['id'];
            }
            if (isset($filtros['comprobante']) && $filtros['comprobante'] != "") {
                $where .= " AND comprobante.tipo_comprobante = '" . $filtros['comprobante'] . "'";
            }
            if (isset($filtros['proveedor']) && $filtros['proveedor'] != "") {
                $where .= " AND proveedor.nombre LIKE '%" . $filtros['proveedor'] . "%'";
            }
            if (isset($filtros['fecha_desde']) && $filtros['fecha_desde'] != "") {
                $where .= " AND DATE(compra.fecha_hora) >= '" . $filtros['fecha_desde'] . "'";
            }
            if (isset($filtros['fecha_hasta']) && $filtros['fecha_hasta'] != "") {
                $where .= " AND DATE(compra.fecha_hora) <= '" . $filtros['fecha_hasta'] . "'";
            }
            if (isset($filtros['total_desde']) && $filtros['total_desde'] != "") {
                $where .= " AND (compra.total_compra + compra.impuesto) >= " . (float)$filtros['total_desde'];
            }
            if (isset($filtros['total_hasta']) && $filtros['total_hasta'] != "") {
                $where .= " AND (compra.total_compra + compra.impuesto) <= " . (float)$filtros['total_hasta'];
            }
            
            // Filtros avanzados para totales
            if (isset($filtros['total']) && $filtros['total'] != "") {
                $subWhere = str_replace("compra.", "c.", $where); // Adaptar alias para la subconsulta
                $subWhere = str_replace("proveedor.", "p.", $subWhere); // Adaptar alias para la subconsulta
                $subWhere = str_replace("comprobante.", "co.", $subWhere); // Adaptar alias para la subconsulta
                if ($filtros['total'] == "Mayor") {
                    $where .= " AND (compra.total_compra + compra.impuesto) = (SELECT MAX(c.total_compra + c.impuesto) FROM compra AS c 
                    INNER JOIN proveedor AS p ON c.rut_proveedor = p.rut 
                    INNER JOIN comprobante AS co ON c.id_comprobante = co.id " . $subWhere . ")";
                } else {
                    $where .= " AND (compra.total_compra + compra.impuesto) = (SELECT MIN(c.total_compra + c.impuesto) FROM compra AS c 
                    INNER JOIN proveedor AS p ON c.rut_proveedor = p.rut 
                    INNER JOIN comprobante AS co ON c.id_comprobante = co.id " . $subWhere . ")";
                }
            }
            
            // Orden
            $orderBy = "ORDER BY compra.id DESC";
            if (isset($filtros['orden']) && $filtros['orden'] != "") {
                $orden = ($filtros['orden'] === 'ASC') ? 'ASC' : 'DESC';
                $orderBy = "ORDER BY proveedor.nombre $orden";
            }
            
            // Consulta principal
            $consulta = "SELECT compra.*, 
                proveedor.nombre AS nombre_proveedor, 
                comprobante.tipo_comprobante
            FROM compra
            INNER JOIN proveedor ON compra.rut_proveedor = proveedor.rut
            INNER JOIN comprobante ON compra.id_comprobante = comprobante.id
            $where
            $orderBy
            LIMIT $inicio, $registros;";
            
            // Consulta para el total
            $consulta_total = "SELECT COUNT(*) AS total
            FROM compra
            INNER JOIN proveedor ON compra.rut_proveedor = proveedor.rut
            INNER JOIN comprobante ON compra.id_comprobante = comprobante.id
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
                            <th scope="col">Proveedor</th>
                            <th scope="col">Fecha y Hora</th>
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
                            <td>' . $rows['nombre_proveedor'] . '</td>
                            <td>' . $rows['fecha_hora'] . '</td>
                            <td>' . ($rows['total_compra'] + $rows['impuesto']) . '</td>
                            <td>
                                <form class="FormularioAjax d-inline-block" action="' . SERVER_URL . 'ajax/compraAjax.php" method="POST" data-form="delete">
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
                $tabla .= '<tr class="text-center"><td colspan="6">No hay registros en el sistema</td></tr>';
            }

            $tabla .= '</tbody></table></div>';

            if ($total >= 1 && $pagina <= $n_paginas) {
                $tabla .= '<p class="text-left">Mostrando compra ' . $reg_inicio . ' a la ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 10);
            }

            return $tabla;
        }

        //---------- Controlador eliminar compra -----------------//
        public function eliminar_compra_controlador() {
            // Recibiendo id de la compra
            $id = $_POST['id_eliminar'];

            //------------ Comprobando la compra en BD ------------//
            $check_compra = mainModel::ejecutar_consulta_simple("SELECT id FROM compra WHERE id = '$id';");
            if ($check_compra->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'La compra que intenta eliminar no existe en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Eliminando compra ------------//
            $eliminar_compra = compraModelo::eliminar_compra_modelo($id);

            if ($eliminar_compra->rowCount() == 1) {
                $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'Compra eliminada',
                    "Texto" => 'La compra ha sido eliminada del sistema exitosamente',
                    "Tipo" => 'success'
                ];
            } else {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos podido eliminar la compra, inténtelo nuevamente',
                    "Tipo" => 'error'
                ];
            }
            echo json_encode($alerta);
        }

        //---------- Controlador para obtener compras -------------//
        public function obtener_compras_controlador() {
            return compraModelo::obtener_compras_modelo();
        } //Fin

        //---------- Controlador para obtener cantidad de compras -------------//
        public function obtener_cantidad_compras_controlador() {
            return compraModelo::obtener_cantidad_compras_modelo();
        }
    }