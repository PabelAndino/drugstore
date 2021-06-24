<?php
require "../config/Conexion.php";

Class Laboratorio
{
    //Constructor y crear instancaias a la clase sin parametros y accesder a todas las funciones que aqui se encuentran
    public function __construct()
    {
    }

    public function insertar($nombre, $descripcion)
    {
        $sql = "INSERT INTO laboratorio (nombre,descripcion,condicion) VALUES ('$nombre', '$descripcion', '1')";
        return ejecutarConsulta($sql);
    }

    public  function  editar($idlaboratorio,$nombre,$descripcion)
    {
        $sql = "UPDATE laboratorio SET nombre = '$nombre', descripcion ='$descripcion' WHERE idlaboratorio = '$idlaboratorio'";
        return ejecutarConsulta($sql);
    }

    public  function desactivar($idlaboratorio)
    {
        $sql = "UPDATE laboratorio SET condicion = '0' WHERE idlaboratorio = '$idlaboratorio'";
        return ejecutarConsulta($sql);
    }

    public function  activar($idlaboratorio)
    {
        $sql = "UPDATE laboratorio SET condicion = '1' WHERE idlaboratorio = '$idlaboratorio'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($idlaboratorio)
    {
        $sql = "SELECT * FROM laboratorio WHERE idlaboratorio = '$idlaboratorio'";
        return ejecutarConsultaSimpleFila($sql);

    }

    public function listar()
    {
        $sql = "SELECT * FROM laboratorio";
        return ejecutarConsulta($sql);
    }
    public function select()
    {
        $sql = "SELECT * FROM laboratorio WHERE condicion=1";
        return ejecutarConsulta($sql);
    }

}
?>