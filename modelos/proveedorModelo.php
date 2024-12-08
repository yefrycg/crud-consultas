<?php

    require_once "mainModel.php";

    class proveedorModelo extends mainModel {

        //---------- Modelo para agregar proveedor -------------//
        protected static function agregar_proveedor_modelo($datos) {
            $sql = mainModel::conectar()->prepare("INSERT INTO proveedor(rut,nombre,contacto,direccion) VALUES(:Rut,:Nombre,:Contacto,:Direccion);");

            $sql->bindParam(":Rut", $datos['Rut']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Contacto", $datos['Contacto']);
            $sql->bindParam(":Direccion", $datos['Direccion']);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para eliminar proveedor -------------//
        protected static function eliminar_proveedor_modelo($rut) {

            $sql = mainModel::conectar()->prepare("DELETE FROM proveedor WHERE rut = :Rut;");

            $sql->bindParam(":Rut", $rut);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para obtener proveedor -------------//
        protected static function obtener_proveedor_modelo($rut) {

            $sql = mainModel::conectar()->prepare("SELECT * FROM proveedor WHERE rut = :Rut;");

            $sql->bindParam(":Rut", $rut);

            $sql->execute();

            return $sql->fetch(PDO::FETCH_OBJ);
        }

        //---------- Modelo para obtener todos los proveedores -------------//
        protected static function obtener_proveedores_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM proveedor;");

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //------------ Modelo para obtener cantidad de proveedores ----------//
        protected static function obtener_cantidad_proveedores_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM proveedor;");
            
            $sql->execute();
            
            return $sql->fetch(PDO::FETCH_OBJ);
        }

        //------------ Modelo para actualizar proveedor ----------//
        protected static function actualizar_proveedor_modelo($datos) {

            $sql = mainModel::conectar()->prepare("UPDATE proveedor SET
            nombre = :Nombre,
            contacto = :Contacto,
            direccion = :Direccion
            WHERE rut = :Rut");

            $sql->bindParam(":Rut", $datos['Rut']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Contacto", $datos['Contacto']);
            $sql->bindParam(":Direccion", $datos['Direccion']);

            $sql->execute();
            return $sql;
        }
    }