<?php

    require_once "mainModel.php";

    class productoModelo extends mainModel {

        //---------- Modelo para agregar producto -------------//
        protected static function agregar_producto_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO producto(id_categoria, nombre, unidad_medida) 
            VALUES (:Id_categoria,:Nombre,:Unidad_medida);");

            $sql->bindParam(":Id_categoria", $datos['Id_categoria']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Unidad_medida", $datos['Unidad_medida']);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para eliminar producto -------------//
        protected static function eliminar_producto_modelo($codigo) {

            $sql = mainModel::conectar()->prepare("DELETE FROM producto WHERE codigo = :Codigo;");

            $sql->bindParam(":Codigo", $codigo);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para obtener un producto -------------//
        protected static function obtener_producto_modelo($codigo) {

            $sql = mainModel::conectar()->prepare("SELECT * FROM producto WHERE codigo = :Codigo;");

            $sql->bindParam(":Codigo", $codigo);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para obtener todos los productos -------------//
        protected static function obtener_productos_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM producto;");

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //------------ Modelo para obtener cantidad de productos en BD ----------//
        protected static function obtener_cantidad_productos_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM producto;");
            
            $sql->execute();
            
            return $sql->fetch(PDO::FETCH_OBJ);
        }

        //------------ Modelo para actualizar producto ----------//
        protected static function actualizar_producto_modelo($datos) {
            $sql = mainModel::conectar()->prepare("UPDATE producto SET
            id_categoria = :Id_categoria,
            nombre = :Nombre,
            unidad_medida = :Unidad_medida
            WHERE codigo = :Codigo");

            $sql->bindParam(":Id_categoria", $datos['Id_categoria']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Unidad_medida", $datos['Unidad_medida']);
            $sql->bindParam(":Codigo", $datos['Codigo']);

            $sql->execute();
            
            return $sql;
        }
    }