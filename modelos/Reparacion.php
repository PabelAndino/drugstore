
<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Reparacion
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($idcliente,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,
                             $impuesto,$total_venta,$tipoventa,$detalle,$equipo,$reparacionarticulo)
    {
        $sql="INSERT INTO venta (idcliente,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_venta,estado,tipoVenta)
        VALUES ('$idcliente','$idusuario','$tipo_comprobante','$serie_comprobante','$num_comprobante','$fecha_hora','$impuesto','$total_venta','Aceptado','$tipoventa')";
        //return ejecutarConsulta($sql);
        $idventanew=ejecutarConsulta_retornarID($sql);


        $sw=true;


            $sql_detalle = "INSERT INTO reparacion(idventa, detalles,equipo,precio,idarticulo,estado) VALUES 
          ('$idventanew', '$detalle','$equipo','$total_venta','$reparacionarticulo','Pendiente')";
            ejecutarConsulta($sql_detalle) or $sw = false;

        return $sw;
    }

    public function editar($detalle,$equipo,$precio,$ideventa)
    {
            $sql="UPDATE reparacion r INNER JOIN venta v ON r.idventa=v.idventa SET r.detalles='$detalle',
r.equipo='$equipo',r.precio='$precio', v.total_venta='$precio' WHERE r.idventa='$ideventa'";
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
        $sql="SELECT r.idventa, r.detalles,r.equipo,r.precio,v.idcliente,p.nombre as cliente,DATE(v.fecha_hora) as fecha,v.tipo_comprobante,v.num_comprobante
              FROM reparacion r INNER JOIN venta v ON r.idventa=v.idventa INNER JOIN persona p ON v.idcliente=p.idpersona
              WHERE r.idventa='$idventa'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idventa)
    {
        $sql="SELECT r.detalles,r.equipo,r.precio,v.idcliente,p.nombre as cliente,v.fecha_hora,v.tipo_comprobante,v.num_comprobante
              FROM reparacion r INNER JOIN venta v ON r.idventa=v.idventa INNER JOIN persona p ON v.idcliente=p.idpersona
              WHERE r.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros
    public function listar1()
    {
        $sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,
            v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u 
            ON v.idusuario=u.idusuario WHERE v.tipoVenta='Reparacion_Mantenimiento_Entregado'   ORDER by v.idventa desc";
        return ejecutarConsulta($sql);
    }

    public function listar2()
    {
        $sql= "SELECT v.idventa,DATE(v.fecha_hora) as fecha, (SELECT idarticulo FROM reparacion WHERE idventa=v.idventa) AS articulo,
              (SELECT precio FROM reparacion WHERE idventa=v.idventa) AS precio,
               v.idcliente,p.nombre as cliente,
              u.idusuario,u.nombre as usuario,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,
              v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u 
              ON v.idusuario=u.idusuario WHERE v.tipoVenta='Reparacion_Mantenimiento_Pendiente' ORDER by v.idventa desc";

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
        $sql = "SELECT v.idventa, v.idcliente, p.nombre as cliente, p.direccion, p.tipo_documento,p.num_documento,p.email,p.telefono,v.idusuario,u.nombre as usuario, v.tipo_comprobante,v.serie_comprobante,
          v.num_comprobante,DATE(fecha_hora) as fecha,v.impuesto,v.total_venta FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa'";
        return ejecutarConsulta($sql);

    }

    public function ventadetalle($idventa)
    {
        $sql="SELECT r.detalles,r.equipo,r.precio FROM reparacion r 
              WHERE r.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }


    public function idDevuelto()

    {

        $sql= "SELECT MAX(idventa) AS id FROM venta";
        return ejecutarConsulta($sql);

    }

    public function entregar($idventa,$idarticulo,$precio){

        $sql="INSERT INTO detalle_venta (idventa,idarticulo,cantidad,precio_compra,precio_venta,descuento,estado) VALUES
                            ('$idventa','$idarticulo','1','0','$precio','0','Aceptado')";
        return ejecutarConsulta($sql);

    }
    public function entregarActualizaVenta($idventa){
        $sql="UPDATE venta SET tipoVenta= 'Reparacion_Mantenimiento_Entregado' ,fecha_hora= NOW() WHERE idventa = '$idventa'";
        return ejecutarConsulta($sql);
    }

    public function artReparacion(){
        $sql="SELECT idarticulo FROM articulo WHERE nombre='REPARACION' ";

        return ejecutarConsulta($sql);
    }
}
?>