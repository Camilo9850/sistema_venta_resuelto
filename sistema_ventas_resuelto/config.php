<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ELIMINAR CUALQUIER session_start() O LOGICA DE SESION DE AQUI.
// ESTE ARCHIVO ES SOLO PARA CONFIGURACION.

date_default_timezone_set('America/Bogota');

class Config {
    const BBDD_HOST = "127.0.0.1";
    const BBDD_PORT= "3306";
    const BBDD_USUARIO = "root";
    const BBDD_CLAVE = "";
    const BBDD_NOMBRE = "abmventas";
}
?>