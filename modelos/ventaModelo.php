<?php

    require_once "mainModel.php";

    class ventaModelo extends mainModel {

        //---------- Modelo para agregar venta -------------//
        protected static function agregar_venta_modelo($datos) {

            $conexion = mainModel::conectar();
            $sql = $conexion->prepare("INSERT INTO venta (cedula_cliente, cod_vendedor, id_comprobante, fecha_hora, tipo_venta, direccion_entrega, impuesto, total_venta) 
            VALUES (:Cedula_cliente, :Cod_vendedor, :Id_comprobante, :Fecha_hora, :Tipo_venta, :Direccion_entrega, :Impuesto, :Total_venta);");

            $sql->bindParam(":Cedula_cliente", $datos['Cedula_cliente']);
            $sql->bindParam(":Cod_vendedor", $datos['Cod_vendedor']);
            $sql->bindParam(":Id_comprobante", $datos['Id_comprobante']);
            $sql->bindParam(":Fecha_hora", $datos['Fecha_hora']);
            $sql->bindParam(":Tipo_venta", $datos['Tipo_venta']);
            $sql->bindParam(":Direccion_entrega", $datos['Direccion_entrega']);
            $sql->bindParam(":Impuesto", $datos['Impuesto']);
            $sql->bindParam(":Total_venta", $datos['Total_venta']);

            if ($sql->execute()) {
                return $conexion->lastInsertId(); // Confirmar que devuelve un ID válido
            }
            return false;
        }

        //---------- Modelo para agregar venta_producto -------------//
        protected static function agregar_venta_producto_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO venta_producto (id_venta, cod_producto, cantidad, precio, descuento) 
            VALUES (:Id_venta, :Cod_producto, :Cantidad, :Precio, :Descuento);");

            $sql->bindParam(":Id_venta", $datos['Id_venta']);
            $sql->bindParam(":Cod_producto", $datos['Cod_producto']);
            $sql->bindParam(":Precio", $datos['Precio']);
            $sql->bindParam(":Cantidad", $datos['Cantidad']);
            $sql->bindParam(":Descuento", $datos['Descuento']);

            $sql->execute();

            return $sql;
        }

        //--------------- Modelo para agregar comprobante ---------------//
        protected static function agregar_comprobante_modelo($datos) {

            $conexion = mainModel::conectar();
            $sql = $conexion->prepare("INSERT INTO comprobante (tipo_comprobante) VALUES (:Tipo_comprobante)");
            
            $sql->bindParam(":Tipo_comprobante", $datos['Tipo_comprobante']);
            
            $sql->execute();

            return $conexion->lastInsertId(); // Confirmar que devuelve un ID válido
        }

        //---------- Modelo para eliminar venta -------------//
        protected static function eliminar_venta_modelo($id) {

            $sql = mainModel::conectar()->prepare("DELETE FROM venta WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para obtener ventas -------------//
        protected static function obtener_ventas_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM venta;");

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //------------ Modelo para obtener cantidad de ventas en BD ----------//
        protected static function obtener_cantidad_ventas_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM venta;");
            
            $sql->execute();
            
            return $sql->fetch(PDO::FETCH_OBJ);
        }
    }