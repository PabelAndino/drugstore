
<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Pedido
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($idcliente,$idusuario,$idproveedor,$fecha_hora,$descripcion,$total,
                             $articulo,$cantidad,$precioU,$precioV)
    {
        $sql="INSERT INTO pedido (idcliente,idusuario,idproveedor,fecha,decripcion,total,estado)
        VALUES ('$idcliente','$idusuario','$idproveedor','$fecha_hora','$descripcion','$total','Pendiente')";
        //return ejecutarConsulta($sql);
        $idpedidonew=ejecutarConsulta_retornarID($sql);

        $num_elementos=0;
        $sw=true;

        while ($num_elementos < count($articulo))
        {
            $sql_detalle = "INSERT INTO detalle_pedido(idpedido, articulo,cantidad,precioU,precioV) VALUES ('$idpedidonew','$articulo[$num_elementos]',
                                                     '$cantidad[$num_elementos]','$precioU[$num_elementos]','$precioV[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        }

        return $sw;
    }


    //Implementamos un método para anular la venta
    public function anular($idpedido)
    {
        $sql="UPDATE pedido SET estado='Entregado' WHERE idpedido='$idpedido'";

        return ejecutarConsulta($sql);
    }

    public function anularDetalle($idventa)
    {
        $sql="UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'";

        return ejecutarConsulta($sql);
    }



    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idpedido)
    {
        $sql="SELECT p.idpedido,p.idcliente,c.nombre as cliente,p.idproveedor,pr.nombre as proveedor,DATE(p.fecha) as fecha,p.decripcion
              FROM pedido p INNER JOIN persona c ON p.idcliente=c.idpersona 
              INNER JOIN persona pr ON p.idproveedor=pr.idpersona WHERE p.idpedido='$idpedido'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idpedido)
    {
        $sql="SELECT dp.iddetalle_pedido, dp.articulo,dp.cantidad,dp.precioU,dp.precioV
FROM detalle_pedido dp 
WHERE dp.idpedido ='$idpedido'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT pd.idpedido ,DATE(pd.fecha) as fecha,pd.idcliente,p.nombre as cliente,pd.idproveedor,pr.nombre as proveedor,u.idusuario,u.nombre as usuario,
              pd.decripcion as descripcion,pd.total,pd.estado FROM pedido pd INNER JOIN persona p ON pd.idcliente=p.idpersona 
              INNER JOIN usuario u ON pd.idusuario=u.idusuario INNER JOIN persona pr ON pd.idproveedor = pr.idpersona  ORDER by pd.idpedido desc";
        return ejecutarConsulta($sql);
    }

    public  function sumarNumeroFactura()
    {

        $sql = "SELECT (max(num_comprobante)+1) as num_comprobante FROM venta ";
        return ejecutarConsulta($sql);

    }

    public  function calculaStock($idproducto)
    {
        $sql = "SELECT nombre, stock from productos WHERE idproducto = $idproducto";
        return ejecutarConsulta($sql);

    }

    public function ventacabecera($idventa)
    {
        $sql = "SELECT v.idventa, v.idcliente, p.nombre as cliente, p.direccion, p.tipo_documento,p.num_documento,
                p.email,p.telefono,v.idusuario,u.nombre as usuario, v.tipo_comprobante,v.serie_comprobante,
          v.num_comprobante,DATE(fecha_hora) as fecha,v.impuesto,v.total_venta FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona
            INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa'";
        return ejecutarConsulta($sql);

    }

    public function ventadetalle($idventa)
    {
        $sql="SELECT a.nombre as articulo, a.codigo,d.cantidad,d.precio_venta,d.descuento,(d.cantidad*d.precio_venta-d.descuento) as subtotal  FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo=a.idarticulo
              WHERE idventa='$idventa'";
        return ejecutarConsulta($sql);
    }


    public function idDevuelto()

    {

        $sql= "SELECT MAX(idventa) AS id FROM venta";
        return ejecutarConsulta($sql);

    }
}
?>