<?php

require_once "global.php";
$conexion = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
mysqli_query($conexion,'SET NAMES "'.DB_ENCONDE.'"');

if (mysqli_connect_errno())
{
    printf("Fallo al conectarse a la base de datos: % \n", mysqli_connect_error());
    exit();
}

if(!function_exists('ejecutarConsulta')) // si no existe la voy a definir
{
    function ejecutarConsulta($sql)
    {
        global  $conexion;
        $query = $conexion->query($sql);
        return $query; //solo devuelve 1 o O
    }

    function ejecutarConsultaSimpleFila($sql)
    {
        global  $conexion;
        $query = $conexion->query($sql);
        $row = $query->fetch_assoc();
        return $row; //aqui si devuelve toda la tabla el mae
    }

    function ejecutarConsulta_retornarID($sql)
    {
        global  $conexion;
        $query = $conexion->query($sql);
        return $conexion->insert_id;
    }

    function limpiarCadena($str)//escapar los caracteres especiales en una cadena para ponerlos en la sentencia sql
    {
        global  $conexion;
        $str = mysqli_real_escape_string($conexion,trim($str));
        return htmlspecialchars($str);
    }

}


?>