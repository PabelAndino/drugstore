<?php

require "../config/Conexion.php";

Class Persona
{
    //Constructor y crear instancaias a la clase sin parametros y accesder a todas las funciones que aqui se encuentran
    public function __construct()
    {
    }

    public function insertar($tipo_persona,$nombre,$tipo_documento,$num_documento,$plazo_vencimiento,$direccion,$telefono,$email)
    {
        $sql = "INSERT INTO  persona(tipo_persona,nombre,tipo_documento,num_documento,plazo_vencimiento,direccion,telefono,email) VALUES ('$tipo_persona', '$nombre','$tipo_documento','$num_documento','$plazo_vencimiento','$direccion','$telefono','$email')";
        return ejecutarConsulta($sql);
    }

    public  function  editar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$plazo_vencimiento,$direccion,$telefono,$email)
    {
        $sql = "UPDATE  persona SET  tipo_persona='$tipo_persona',nombre='$nombre', tipo_documento='$tipo_documento',num_documento='$num_documento',
                plazo_vencimiento='$plazo_vencimiento',direccion='$direccion',telefono='$telefono',email='$email' WHERE idpersona='$idpersona' ";
        return ejecutarConsulta($sql);
    }

    public  function eliminar($idpersona)
    {
        $sql = "DELETE FROM persona  WHERE idpersona = '$idpersona'";
        return ejecutarConsulta($sql);
    }



    public function mostrar($idpersona)
    {
        $sql = "SELECT * FROM persona WHERE idpersona = '$idpersona'";
        return ejecutarConsultaSimpleFila($sql);

    }

    public function listarP()
    {
        //$sql = "SELECT a.idpersona,a.idpersona,c.nombre as persona, a.codigo, a.nombre, a.stock, a.descripcion,a.imagen,a.condicion FROM persona a INNER JOIN persona c ON a.idpersona=c.idpersona";
        $sql = "SELECT * FROM persona WHERE tipo_persona = 'Proveedor' ORDER BY idpersona ASC";
        return ejecutarConsulta($sql);
    }

    public  function  listarC()
    {
        $sql = "SELECT * FROM persona WHERE tipo_persona = 'Cliente'";
        return ejecutarConsulta($sql);
    }

}
?>
