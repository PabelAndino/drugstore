
<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Proforma
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($idcliente,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,
                             $impuesto,$total_venta,$tipoventa,$idarticulo,$cantidad,$precio_venta,$descuento)
    {
        $sql="INSERT INTO proforma (idcliente,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_venta,estado,tipoVenta)
        VALUES ('$idcliente','$idusuario','$tipo_comprobante','$serie_comprobante','$num_comprobante','$fecha_hora','$impuesto','$total_venta','Aceptado','$tipoventa')";
        //return ejecutarConsulta($sql);
        $idproformanew=ejecutarConsulta_retornarID($sql);

        $num_elementos=0;
        $sw=true;

        while ($num_elementos < count($idarticulo))
        {
            $sql_detalle = "INSERT INTO detalle_proforma(idproforma, idarticulo,cantidad,precio_venta,descuento,estado) VALUES ('$idproformanew', '$idarticulo[$num_elementos]',
                                                     '$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]','Aceptado')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        }

        return $sw;
    }


    //Implementamos un método para anular la venta
    public function anular($idproforma)
    {
        $sql="UPDATE proforma SET estado='Anulado' WHERE idproforma='$idproforma'";

        return ejecutarConsulta($sql);
    }

    public function anularDetalle($idventa)
    {
        $sql="UPDATE proforma SET estado='Anulado' WHERE idproforma='$idproforma'";

        return ejecutarConsulta($sql);
    }



    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idproforma)
    {
        $sql="SELECT v.idproforma,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,
                    v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM proforma v 
                    INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idproforma='$idproforma' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idproforma)
    {
        $sql="SELECT dv.idproforma,dv.idarticulo,a.nombre,dv.cantidad,dv.precio_venta,dv.descuento,(dv.cantidad*dv.precio_venta-dv.descuento) 
                as subtotal FROM detalle_proforma dv inner join articulo a on dv.idarticulo=a.idarticulo where dv.idproforma='$idproforma'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT v.idproforma,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM proforma v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.tipoVenta='Contado'  ORDER by v.idproforma desc ";
        return ejecutarConsulta($sql);
    }

    public  function sumarNumeroFactura()
    {

        $sql = "SELECT (max(num_comprobante)+1) as num_comprobante FROM proforma ";
        return ejecutarConsulta($sql);

    }

    public  function calculaStock($idproducto)
    {
        $sql = "SELECT nombre, stock from productos WHERE idproducto = $idproducto";
        return ejecutarConsulta($sql);

    }

    public function ventacabecera($idproforma)
    {
        $sql = "SELECT v.idproforma, v.idcliente, p.nombre as cliente, p.direccion, p.tipo_documento,p.num_documento,p.email,p.telefono,v.idusuario,u.nombre as usuario, v.tipo_comprobante,v.serie_comprobante,
          v.num_comprobante,DATE(fecha_hora) as fecha,v.impuesto,v.total_venta FROM proforma v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idproforma='$idproforma'";
        return ejecutarConsulta($sql);

    }

    public function ventadetalle($idproforma)
    {
        $sql="SELECT a.nombre as articulo, a.codigo,d.cantidad,d.precio_venta,d.descuento,(d.cantidad*d.precio_venta-d.descuento) as subtotal  
                    FROM detalle_proforma d INNER JOIN articulo a ON d.idarticulo=a.idarticulo
                      WHERE idproforma='$idproforma'";
        return ejecutarConsulta($sql);
    }


    public function idDevuelto()

    {

        $sql= "SELECT MAX(idventa) AS id FROM venta";
        return ejecutarConsulta($sql);

    }
}
?>