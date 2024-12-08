<?php

    require_once "mainModel.php";

    class loginModelo extends mainModel {

        //------------- Modelo para iniciar sesión -------------//
        protected static function iniciar_sesion_modelo($datos) {
            $sql = mainModel::conectar()->prepare("SELECT * FROM usuario 
            WHERE nombre = :Nombre AND contrasena = :Contrasena;");
            
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Contrasena", $datos['Contrasena']);
            
            $sql->execute();

            return $sql;
        }
    }