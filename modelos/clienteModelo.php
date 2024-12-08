<?php

    require_once "mainModel.php";

    class clienteModelo extends mainModel {

        //---------- Modelo para agregar cliente -------------//
        protected static function agregar_cliente_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO cliente(cedula,nombre,apellidos,telefono,direccion) 
            VALUES(:Cedula,:Nombre,:Apellidos,:Telefono,:Direccion);");

            $sql->bindParam(":Cedula", $datos['Cedula']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Apellidos", $datos['Apellidos']);
            $sql->bindParam(":Telefono", $datos['Telefono']);
            $sql->bindParam(":Direccion", $datos['Direccion']);

            $sql->execute();

            return $sql;
        }
        
        //---------- Modelo para eliminar cliente -------------//
        protected static function eliminar_cliente_modelo($cedula) {

            $sql = mainModel::conectar()->prepare("DELETE FROM cliente WHERE cedula = :Cedula;");

            $sql->bindParam(":Cedula", $cedula);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para obtener cliente -------------//
        protected static function obtener_cliente_modelo($cedula) {

            $sql = mainModel::conectar()->prepare("SELECT * FROM cliente WHERE cedula = :Cedula;");

            $sql->bindParam(":Cedula", $cedula);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para obtener todos los clientes -------------//
        protected static function obtener_clientes_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM cliente;");

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //------------ Modelo para obtener cantidad de clientes ----------//
        protected static function obtener_cantidad_clientes_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM cliente;");
            $sql->execute();
            return $sql->fetch(PDO::FETCH_OBJ);
        }

        //------------ Modelo para actualizar cliente ----------//
        protected static function actualizar_cliente_modelo($datos) {

            $sql = mainModel::conectar()->prepare("UPDATE cliente SET
            nombre = :Nombre,
            apellidos = :Apellidos,
            telefono = :Telefono,
            direccion = :Direccion
            WHERE cedula = :Cedula");

            $sql->bindParam(":Cedula", $datos['Cedula']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Apellidos", $datos['Apellidos']);
            $sql->bindParam(":Telefono", $datos['Telefono']);
            $sql->bindParam(":Direccion", $datos['Direccion']);

            $sql->execute();
            return $sql;
        }
    }