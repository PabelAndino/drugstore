<?php
require "../config/Conexion.php";

Class Categoria
{
    //Constructor y crear instancaias a la clase sin parametros y accesder a todas las funciones que aqui se encuentran
    public function __construct()
    {
    }

    public function insertar($nombre, $descripcion)
    {
        $sql = "INSERT INTO categoria (nombre,descripcion,condicion) VALUES ('$nombre', '$descripcion', '1')";
        return ejecutarConsulta($sql);
    }

    public  function  editar($idcategoria,$nombre,$descripcion)
    {
        $sql = "UPDATE categoria SET nombre = '$nombre', descripcion ='$descripcion' WHERE idcategoria = '$idcategoria'";
        return ejecutarConsulta($sql);
    }

    public  function desactivar($idcategoria)
    {
        $sql = "UPDATE categoria SET condicion = '0' WHERE idcategoria = '$idcategoria'";
        return ejecutarConsulta($sql);
    }

    public function  activar($idcategoria)
    {
        $sql = "UPDATE categoria SET condicion = '1' WHERE idcategoria = '$idcategoria'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($idcategoria)
    {
        $sql = "SELECT * FROM categoria WHERE idcategoria = '$idcategoria'";
        return ejecutarConsultaSimpleFila($sql);

    }

    public function listar()
    {
        $sql = "SELECT * FROM categoria";
        return ejecutarConsulta($sql);
    }
    public function select()
    {
        $sql = "SELECT * FROM categoria WHERE condicion=1";
        return ejecutarConsulta($sql);
    }

}
?>