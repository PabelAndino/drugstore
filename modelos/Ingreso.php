<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Ingreso
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,
                             $total_compra,$cantidad,$costoU,$idarticulo,$porcentajeVenta,$porcentajeVentaU,$precioVenta)
    {
        $sql="INSERT INTO ingreso (idproveedor,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,total_compra,estado)
        VALUES ('$idproveedor','$idusuario','$tipo_comprobante','$serie_comprobante','$num_comprobante','$fecha_hora','$total_compra','Aceptado')";
        //return ejecutarConsulta($sql);
        $idingresonew=ejecutarConsulta_retornarID($sql);

        $num_elementos=0;
        $sw=true;

        while ($num_elementos < count($cantidad))//mientras hayan articulos que se ejecute esta sentencia
        {
            $sql_detalle = "INSERT INTO detalle_ingreso(idingreso,cantidad,costoU,idarticulo,porcentajeVenta,porcentajeVentaU,precioVenta,estado) VALUES 
                            ('$idingresonew','$cantidad[$num_elementos]','$costoU[$num_elementos]','$idarticulo[$num_elementos]',
                             '$porcentajeVenta[$num_elementos]','$porcentajeVentaU[$num_elementos]','$precioVenta[$num_elementos]','Aceptado')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        }

        return $sw;
    }

    public function editar($idarticulo,$iddetalle,$cantidad,$costoU,$porcentajeVenta,$porcentajeventaU,$precioVenta){
        $sql="UPDATE detalle_ingreso SET cantidad='$cantidad',costoU='$costoU',porcentajeVenta='$porcentajeVenta',porcentajeVentaU='$porcentajeventaU',precioVenta='$precioVenta' WHERE iddetalle_ingreso='$iddetalle'";



        $this->editarStock($idarticulo,$cantidad);
        return ejecutarConsulta($sql);
    }

    public function editarStock($idarticulo,$stock){
        $sql="UPDATE articulo SET stock='$stock' WHERE idarticulo='$idarticulo'  ";
        return ejecutarConsulta($sql);
    }


    //Implementamos un método para anular categorías
    public function anular($idingreso)
    {
        $sql="UPDATE ingreso SET estado='Anulado' WHERE idingreso='$idingreso'";
        return ejecutarConsulta($sql);
    }




    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idingreso)
    {
        $sql="SELECT i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor,p.nombre as proveedor,u.idusuario,u.nombre as usuario,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE i.idingreso='$idingreso'";
        return ejecutarConsultaSimpleFila($sql);
    }


    public function listarDetalles($idingreso)
    {
        $sql="SELECT di.idingreso,di.cantidad,di.costoU,a.nombre_generico,a.nombre_comercial,lab.nombre as laboratorio,di.porcentajeVenta,di.porcentajeVentaU,di.precioVenta 
              FROM detalle_ingreso di INNER JOIN articulo a on di.idarticulo=a.idarticulo INNER JOIN categoria l ON a.idcategoria=l.idcategoria INNER JOIN ingreso ig
              ON ig.idingreso=di.idingreso INNER JOIN persona lab ON lab.idpersona=ig.idproveedor WHERE di.idingreso =  '$idingreso' ";

        return ejecutarConsulta($sql);
    }

    public function listarDetalle($idingreso)
    {
        $sql="SELECT di.idingreso,di.idarticulo,a.nombre,di.cantidad,di.precio_compra,di.precio_venta
              FROM detalle_ingreso di inner join articulo a on di.idarticulo=a.idarticulo where di.idingreso='$idingreso'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor,p.nombre as proveedor,u.idusuario,u.nombre as usuario,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.estado
 FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario  ORDER BY i.idingreso desc";
        return ejecutarConsulta($sql);
    }
    public function detalleslista()
    {
        $sql="SELECT di.iddetalle_ingreso as  idingreso,di.idarticulo,a.nombre_comercial,a.nombre_generico,a.stock as cantidad,di.costoU,di.porcentajeVenta as porcentajeV,di.porcentajeVentaU,di.precioVenta,lb.nombre as laboratorio 
              FROM detalle_ingreso di INNER JOIN articulo a on di.idarticulo=a.idarticulo
              INNER JOIN ingreso i ON i.idingreso=di.idingreso INNER JOIN persona lb ON lb.idpersona=i.idproveedor  WHERE di.estado='Aceptado' ORDER BY di.idingreso desc";
        return ejecutarConsulta($sql);
    }
}



?>