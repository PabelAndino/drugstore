
<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Venta
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($cliente,$idusuario,$tipo_comprobante,$num_comprobante,$fecha_hora,
                             $total_venta,$tipoventa,$idarticulo,$cantidad,$precio_compra,$precio_venta,$descuento)
    {
        $sql="INSERT INTO venta (idcliente,idusuario,tipo_comprobante,num_comprobante,fecha_hora,total_venta,estado,tipoVenta)
        VALUES ('$cliente','$idusuario','$tipo_comprobante','$num_comprobante','$fecha_hora','$total_venta','Aceptado','$tipoventa')";
        //return ejecutarConsulta($sql);
        $idventanew=ejecutarConsulta_retornarID($sql);


        $num_elementos=0;
        $sw=true;

        while ($num_elementos < count($idarticulo))
        {
            $sql_detalle = "INSERT INTO detalle_venta(idventa, idarticulo,cantidad,precio_compra,precio_venta,descuento,estado) VALUES ('$idventanew', '$idarticulo[$num_elementos]',
                                                     '$cantidad[$num_elementos]','$precio_compra[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]','Aceptado')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        }

      //  $this->printS($idventanew);
        return $sw;
    }
    public function printS($id){
        //header('Location: ../reportes/TicketRep.php?id='.$id.' ');
        echo '
              <script>
                window.open("../reportes/TicketRep.php?id='.$id.'","_blank");       
              </script>';
    }

    public function retornaID(){
        $sql = "SELECT (max(idventa)) as venta FROM venta ";
        return ejecutarConsulta($sql);
    }


    //Implementamos un método para anular la venta
    public function anular($idventa)
    {
        $sql="UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'";

        return ejecutarConsulta($sql);
    }

    public function anularDetalle($idventa)
    {
        $sql="UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'";

        return ejecutarConsulta($sql);
    }



    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idventa)
    {
        $sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente as idcliente,u.idusuario,u.nombre as usuario,v.tipo_comprobante,v.num_comprobante,v.total_venta,v.estado FROM venta v 
              INNER JOIN persona p ON p.idpersona=v.idcliente INNER JOIN usuario u ON u.idusuario=v.idusuario WHERE v.idventa='$idventa' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idventa)
    {
        $sql="SELECT dv.idventa,dv.idarticulo,a.nombre_comercial,a.nombre_generico,dv.cantidad,dv.precio_venta,dv.descuento,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal 
              FROM detalle_venta dv inner join articulo a on dv.idarticulo=a.idarticulo where dv.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,c.nombre as cliente,u.idusuario,u.nombre as usuario,
                      v.tipo_comprobante,v.num_comprobante,v.total_venta,v.estado,v.tipoVenta FROM venta v 
                      INNER JOIN usuario u ON v.idusuario=u.idusuario INNER JOIN persona c ON c.idpersona=v.idcliente ORDER by v.idventa desc";
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
        $sql = "SELECT v.idventa, cl.nombre as cliente,v.idusuario,u.nombre as usuario, v.tipo_comprobante, v.num_comprobante,DATE(fecha_hora) as fecha,v.total_venta FROM venta v 
                INNER JOIN usuario u ON v.idusuario=u.idusuario INNER JOIN persona cl ON cl.idpersona=v.idcliente WHERE v.idventa='$idventa'";
        return ejecutarConsulta($sql);

    }

    public function ventadetalle($idventa)
    {
        $sql="SELECT a.nombre_comercial as articulo, a.codigo,d.cantidad,d.precio_venta,d.descuento,(d.cantidad*d.precio_venta-d.descuento) as subtotal  FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo=a.idarticulo
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