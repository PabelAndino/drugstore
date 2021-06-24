
<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Articulo
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($idcategoria,$codigo,$nombre_comercial,$nombre_generico,$stock,$descripcion,$imagen,$fecha)
    {
        $sql="INSERT INTO articulo (idcategoria,codigo,nombre_comercial,nombre_generico,stock,descripcion,imagen,condicion,fecha_vencimiento)
        VALUES ('$idcategoria','$codigo','$nombre_comercial','$nombre_generico','$stock','$descripcion','$imagen','1','$fecha')";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($idarticulo,$idcategoria,$codigo,$nombre_comercial,$nombre_generico,$stock,$descripcion,$imagen,$fecha)
    {
        $sql="UPDATE articulo SET idcategoria='$idcategoria',codigo='$codigo',nombre_comercial='$nombre_comercial',nombre_generico ='$nombre_generico',
            stock='$stock',descripcion='$descripcion',imagen='$imagen',fecha_vencimiento='$fecha' WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar registros
    public function desactivar($idarticulo)
    {
        $sql="UPDATE articulo SET condicion='0' WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar registros
    public function activar($idarticulo)
    {
        $sql="UPDATE articulo SET condicion='1' WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idarticulo)
    {
        $sql="SELECT ar.idarticulo,ar.idcategoria,ar.codigo,ar.nombre_comercial,ar.nombre_generico,ar.stock,ar.descripcion,ar.imagen,ar.condicion, 
              DATE(ar.fecha_vencimiento) as fecha_vencimiento FROM articulo  ar WHERE ar.idarticulo='$idarticulo'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre_comercial,a.nombre_generico,a.stock,a.descripcion,a.imagen,a.condicion,DATE(a.fecha_vencimiento) as fecha_vencimiento
            FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria ORDER BY a.idarticulo DESC";
        return ejecutarConsulta($sql);
    }

    public function listararticuloIngreso()
    {
        $sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre_comercial,a.nombre_generico,a.stock,a.descripcion,a.imagen,a.condicion,DATE(a.fecha_vencimiento) as fecha_vencimiento
            FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.stock=0 ORDER BY a.idarticulo DESC";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros activos
    public function listarActivos()
    {
        $sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre_comercial,a.nombre_generico,a.stock,a.descripcion,
              a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros activos, su último precio y el stock (vamos a unir con el último registro de la tabla detalle_ingreso)
    public function listarActivosVenta()
    {
        /*$sql="SELECT a.idarticulo,a.nombre_comercial,a.nombre_generico,(SELECT l.nombre FROM ingreso ig INNER JOIN persona l ON l.idpersona=ig.idproveedor
              INNER JOIN detalle_ingreso dt ON dt.idingreso=ig.idingreso WHERE a.idarticulo=dt.idarticulo ) as laboratorio,a.codigo,a.stock, 
              (SELECT di.costoU FROM detalle_ingreso di WHERE di.idarticulo=a.idarticulo order by di.iddetalle_ingreso desc limit 0,1)as precio_compra, 
              (SELECT precioVenta FROM detalle_ingreso WHERE idarticulo=a.idarticulo order by iddetalle_ingreso desc limit 0,1) as precio_venta, a.descripcion,a.imagen,a.condicion 
              FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria

              WHERE a.condicion='1' AND a.stock>0 ORDER BY a.idarticulo DESC";*/
        $sql="SELECT ar.idarticulo,ar.nombre_comercial,ar.nombre_generico,lab.nombre as laboratorio, ar.codigo,ar.stock,di.costoU as precio_compra,di.precioVenta as precio_venta,ar.imagen FROM detalle_ingreso di INNER JOIN articulo ar ON ar.idarticulo=di.idarticulo INNER JOIN ingreso i ON i.idingreso=di.idingreso INNER JOIN persona lab ON 
              lab.idpersona=i.idproveedor WHERE ar.condicion='1' AND ar.stock>0 AND di.estado='Aceptado' ORDER BY ar.idarticulo DESC
                ";
        return ejecutarConsulta($sql);
    }

    function mostrarProductosVencidos(){
        $sql = "SELECT ar.nombre_comercial,ar.nombre_generico,lb.nombre as categoria,DATE(ar.fecha_vencimiento)as fecha_vencimiento,pr.nombre as proveedor,pr.plazo_vencimiento,ar.stock FROM detalle_ingreso di INNER JOIN ingreso i ON i.idingreso=di.idingreso INNER JOIN articulo ar ON ar.idarticulo=di.idarticulo INNER JOIN persona pr ON pr.idpersona=i.idproveedor 
                INNER JOIN categoria lb ON lb.idcategoria=ar.idcategoria WHERE ar.fecha_vencimiento between curdate() and curdate() + interval pr.plazo_vencimiento MONTH AND ar.condicion = 1 ORDER BY ar.fecha_vencimiento DESC";
        return ejecutarConsulta($sql);
    }

    function consultaStock($stock){
        $sql="SELECT a.idarticulo,a.idcategoria,l.nombre as categoria,a.codigo,a.nombre_comercial,a.nombre_generico,a.stock,a.descripcion,a.imagen,a.condicion,a.fecha_vencimiento
            FROM articulo a INNER JOIN categoria l ON a.idcategoria=l.idcategoria WHERE a.stock='$stock'";
        return ejecutarConsulta($sql);
    }

    public  function generarCodigo(){
        $sql="SELECT MAX(idarticulo) AS id FROM articulo";
        return ejecutarConsulta($sql);
    }


}

?>