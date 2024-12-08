<?php

    require_once "mainModel.php";

    class compraModelo extends mainModel {
        
        //---------- Modelo para agregar compra -------------//
        protected static function agregar_compra_modelo($datos) {

            $conexion = mainModel::conectar();
            $sql = $conexion->prepare("INSERT INTO compra (rut_proveedor, id_comprobante, fecha_hora, total_compra, impuesto) 
                VALUES (:Rut_proveedor, :Id_comprobante, :Fecha_hora, :Total_compra, :Impuesto)");
            $sql->bindParam(":Rut_proveedor", $datos['Rut_proveedor']);
            $sql->bindParam(":Id_comprobante", $datos['Id_comprobante']);
            $sql->bindParam(":Fecha_hora", $datos['Fecha_hora']);
            $sql->bindParam(":Total_compra", $datos['Total_compra']);
            $sql->bindParam(":Impuesto", $datos['Impuesto']);

            if ($sql->execute()) {
                return $conexion->lastInsertId(); // Confirmar que devuelve un ID válido
            }
            return false;
        }


        //---------- Modelo para agregar compra_producto -------------//
        protected static function agregar_compra_producto_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO compra_producto (id_compra, cod_producto, cantidad, precio_compra, precio_compra) 
            VALUES (:Id_compra, :Cod_producto, :Cantidad, :Precio_compra, :Precio_compra);");

            $sql->bindParam(":Id_compra", $datos['Id_compra']);
            $sql->bindParam(":Cod_producto", $datos['Cod_producto']);
            $sql->bindParam(":Cantidad", $datos['Cantidad']);
            $sql->bindParam(":Precio_compra", $datos['Precio_compra']);
            $sql->bindParam(":Precio_compra", $datos['Precio_compra']);

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


        //---------- Modelo para eliminar compra -------------//
        protected static function eliminar_compra_modelo($id) {

            $sql = mainModel::conectar()->prepare("DELETE FROM compra WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para obtener compras -------------//
        protected static function obtener_compras_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM compra;");

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //------------ Modelo para obtener cantidad de compras en BD ----------//
        protected static function obtener_cantidad_compras_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM compra;");
            
            $sql->execute();
            
            return $sql->fetch(PDO::FETCH_OBJ);
        }
    }