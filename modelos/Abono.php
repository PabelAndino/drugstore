
<?php
//Incluímos inicialmente la conexión a la base de datos

//SELECT  a.idusuario,u.nombre as usuario, a.tipo_comprobante,a.num_comprobante,a.total_abono
//FROM abono a INNER JOIN venta v ON a.idventa=v.idventa INNER JOIN usuario u ON a.idusuario=u.idusuario


require "../config/Conexion.php";

Class Abono
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($idventa,$fecha,$idusuario,$tipo_comprobante,$num_comprobante,$total_abono)
    {
        $sql="INSERT INTO abono (idventa,fecha,idusuario,tipo_comprobante,num_comprobante,estado)
              VALUES ('$idventa','$fecha','$idusuario','$tipo_comprobante','$num_comprobante','Pendiente')";
              $idingresonew=ejecutarConsulta_retornarID($sql);

        $sw=true;
        $sql_detalle = "INSERT INTO detalle_abono(idabono,fecha,cantidad) VALUES ('$idingresonew','$fecha','$total_abono')";
            ejecutarConsulta($sql_detalle) or $sw = false;

        return $sw;
    }

    public function insertarDetalleAbono($idabono,$fecha,$cantidad){
        $sql="INSERT INTO detalle_abono (idabono,fecha,cantidad) VALUES ('$idabono','$fecha','$cantidad')";
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
        $sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado 
              FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa' ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function mostrarAbonos($idventa){

          $sql = "SELECT DATE(da.fecha)as fecha,da.cantidad,a.idabono
                  FROM detalle_abono da INNER JOIN abono a ON da.idabono=a.idabono INNER JOIN venta v ON a.idventa=v.idventa
                  WHERE a.idventa = '$idventa'";

                  return ejecutarConsulta($sql);
    }

    public function selectAbono($idventa){
        $sql = "SELECT idabono
                  FROM abono
                  WHERE idventa = '$idventa'";

        return ejecutarConsulta($sql);
    }

    public function abonoPagado($idventa,$idcliente){
        $sql = "UPDATE venta SET fecha_hora = NOW(), tipoVenta = 'Contado' WHERE idcliente = '$idcliente' AND idventa = '$idventa'";
        return ejecutarConsulta($sql);
    }

    public function abonoPagadoActualizaEstado($idventa){
        $sql = "UPDATE abono SET estado = 'Pagado' WHERE idventa = '$idventa'";
        return ejecutarConsulta($sql);

    }

    public  function listarC(){
        $sql = "SELECT DISTINCT c.idpersona,c.nombre 
                FROM venta v INNER JOIN persona c ON v.idcliente=c.idpersona WHERE v.tipoVenta = 'Credito'";
        return ejecutarConsulta($sql);
    }

    public function listarDetalle($idventa)
    {
        $sql="SELECT dv.idventa,dv.idarticulo,a.nombre_comercial,a.nombre_generico,dv.cantidad,dv.precio_venta,dv.descuento,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal 
              FROM detalle_venta dv inner join articulo a on dv.idarticulo=a.idarticulo where dv.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros
    public function listadoVentas()
    {
        $sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.num_comprobante,v.total_venta,v.estado
              FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.tipoVenta='Credito' ORDER by v.idventa desc";
        return ejecutarConsulta($sql);
    }

    public function listadoAbonos()
    {
        $sql="SELECT a.idventa,a.fecha,cl.nombre as cliente,us.nombre as usuario,v.tipo_comprobante,v.num_comprobante,
v.total_venta, a.estado
FROM abono a INNER JOIN venta v ON a.idventa = v.idventa
INNER JOIN persona cl ON v.idcliente=cl.idpersona
INNER JOIN usuario us ON v.idusuario=us.idusuario

WHERE a.estado='Pendiente' OR a.estado = 'Pagado' ORDER by a.idabono DESC ";
        return ejecutarConsulta($sql);
    }
    public function listarVentasCliente($idcliente)
    {
        $sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.tipo_comprobante,v.num_comprobante,v.total_venta,v.estado 
              FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario
              WHERE v.tipoVenta='Credito' AND v.idcliente = '$idcliente'  ORDER by v.idventa desc";
        return ejecutarConsulta($sql);
    }

    public  function sumarNumeroFactura()
    {

        $sql = "SELECT (max(num_comprobante)+1) as num_comprobante FROM abono ";
        return ejecutarConsulta($sql);

    }

    public  function calculaStock($idproducto)
    {
        $sql = "SELECT nombre, stock from productos WHERE idproducto = $idproducto";
        return ejecutarConsulta($sql);

    }

    public function ventacabecera($idventa)
    {
        $sql = "SELECT DATE(a.fecha) as fecha,cl.nombre as cliente,cl.num_documento,v.num_comprobante,v.total_venta as total
FROM abono a INNER JOIN venta v ON a.idventa= v.idventa INNER JOIN persona cl ON v.idcliente=cl.idpersona
WHERE a.idventa = '$idventa'";

        return ejecutarConsulta($sql);

    }

    public function ventadetalle($idventa)
    {
        $sql="SELECT DATE (da.fecha) as fecha, da.cantidad
FROM  detalle_abono da INNER JOIN abono a ON da.idabono=a.idabono INNER JOIN venta v ON a.idventa= v.idventa 
WHERE a.idventa = '$idventa' ";
        return ejecutarConsulta($sql);
    }


    public function idDevuelto()

    {

        $sql= "SELECT MAX(idventa) AS id FROM venta";
        return ejecutarConsulta($sql);

    }
}
?>