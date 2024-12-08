<?php

    require_once "mainModel.php";

    class categoriaModelo extends mainModel {

        //---------- Modelo para agregar categoria -------------//
        protected static function agregar_categoria_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO categoria(nombre,descripcion) 
            VALUES(:Nombre,:Descripcion);");

            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Descripcion", $datos['Descripcion']);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para eliminar categoria -------------//
        protected static function eliminar_categoria_modelo($id) {

            $sql = mainModel::conectar()->prepare("DELETE FROM categoria WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para obtener una categoria -------------//
        protected static function obtener_categoria_modelo($id) {

            $sql = mainModel::conectar()->prepare("SELECT * FROM categoria WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para obtener todas las categorias -------------//
        protected static function obtener_categorias_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM categoria;");

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //------------ Modelo para obtener cantidad de categorias ----------//
        protected static function obtener_cantidad_categorias_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM categoria;");
            $sql->execute();
            return $sql->fetch(PDO::FETCH_OBJ);
        }

        //------------ Modelo para actualizar categoria ----------//
        protected static function actualizar_categoria_modelo($datos) {

            $sql = mainModel::conectar()->prepare("UPDATE categoria SET
            nombre = :Nombre, descripcion = :Descripcion WHERE id = :Id");

            $sql->bindParam(":Id", $datos['Id']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Descripcion", $datos['Descripcion']);

            $sql->execute();
            return $sql;
        }
    }