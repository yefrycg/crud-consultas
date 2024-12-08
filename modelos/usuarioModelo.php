<?php

    require_once "mainModel.php";

    class usuarioModelo extends mainModel {

        //---------- Modelo para agregar usuario -------------//
        protected static function agregar_usuario_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO usuario (nombre, contrasena, rol) VALUES (:Nombre,:Contrasena,:Rol);");

            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Contrasena", $datos['Contrasena']);
            $sql->bindParam(":Rol", $datos['Rol']);

            $sql->execute();

            return $sql;
        }
        
        //---------- Modelo para eliminar usuario -------------//
        protected static function eliminar_usuario_modelo($id) {

            $sql = mainModel::conectar()->prepare("DELETE FROM usuario WHERE id != 1 AND id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para obtener un usuario -------------//
        protected static function obtener_usuario_modelo($id) {

            $sql = mainModel::conectar()->prepare("SELECT * FROM usuario WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para obtener todos los usuarios -------------//
        protected static function obtener_usuarios_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM usuario WHERE id != 1;");

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //------------ Modelo para obtener cantidad de usuarios en BD -----------//
        protected static function obtener_cantidad_usuarios_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM usuario WHERE id != 1;");
            
            $sql->execute();
            
            return $sql->fetch(PDO::FETCH_OBJ);
        }

        //------------ Modelo para actualizar usuario -----------//
        protected static function actualizar_usuario_modelo($datos) {

            $sql = mainModel::conectar()->prepare("UPDATE usuario SET
            nombre = :Nombre,
            contrasena = :Contrasena,
            rol = :Rol
            WHERE id = :Id");

            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Contrasena", $datos['Contrasena']);
            $sql->bindParam(":Rol", $datos['Rol']);
            $sql->bindParam(":Id", $datos['Id']);

            $sql->execute();
            
            return $sql;
        }
    }