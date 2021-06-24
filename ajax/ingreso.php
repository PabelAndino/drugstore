<?php

require_once "../modelos/Ingreso.php";

if(strlen(session_id()) < 1){
    session_start();
}
$ingreso = new Ingreso();

$idingreso = isset($_POST["idingreso"])? limpiarCadena($_POST["idingreso"])  : "";//aqui recibo las primeras datos atravez del metodo POST
$idproveedor = isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"])  : "";
$idusuario = $_SESSION["idusuario"];//aqui ya trabaja con la session
$tipo_comprobante = isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"])  : "";
$serie_comprobante = isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"])  : "";
$num_comprobante = isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"])  : "";
$fecha_hora = isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"])  : "";

$total_compra = isset($_POST["total_compra"])? limpiarCadena($_POST["total_compra"])  : "";




$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]): "";
$descripcion = isset($_POST["descripcion"])? limpiarCadena( $_POST["descripcion"]):"";

switch ($_GET["op"])
{
    case 'guardaryeditar':
        if(empty($idingreso))//si el id esta vacio
        {

            $rspta=$ingreso->insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,
                $total_compra,$_POST["cantidad"], $_POST["costoU"],$_POST["idarticulo"],$_POST["porcentajeVenta"],$_POST["porV"],$_POST["precio_venta"]);
            echo $rspta ? "ingreso Registrado": "Ingreso no se pudo registrar";

        } else {



        }

        break;
    case 'editar':
        $cantidad=$_GET['cantidad'];
        $precioU=$_GET['costou'];
        $porcentajev=$_GET['porcentajev'];
        $porcentajeVU=$_GET['porcentajeVU'];
        $preciov=$_GET['preciov'];
        $iddetalle=$_GET['iddetalle'];
        $idarticulo=$_GET['idarticulo'];

        $rep=$ingreso->editar($idarticulo,$iddetalle,$cantidad,$precioU,$porcentajev,$porcentajeVU,$preciov);
        echo $rep ? "Ingreso Editado Correctamente" : "El ingreso no se pudo editar";

       // echo " Cantidad ",$cantidad," Precio U ", " Porcentaje ",$porcentajev," PORVu ",$porcentajeVU," Precio ",$preciov;
        break;
    case 'anular':
        $rspta=$ingreso->anular($idingreso);
        echo $rspta ? "ingreso desactivado" : "ingreso no se pudo desactivar";
        break;



    case 'mostrar':
        $rspta=$ingreso->mostrar($idingreso);
        echo json_encode($rspta);
        break;


    case 'listarDetalle':

        $id=$_GET['id'];

        $res=$ingreso->listarDetalles($id);

        $total=0;

        echo '<thead style="background-color: #72caff">

                                        <th>Opciones</th>
                                        <th>Cantidad</th>
                                        <th>Costo U</th>
                                        <th>Genérico</th>
                                        <th>Comercial</th>
                                        <th>Laboratorio</th>


                                        <th>% Venta</th>
                                        <th>% Venta U</th>
                                        <th>Precio de Venta</th>

                                        <th>Sub total</th>

                                        </thead>';


        while ($reg = $res->fetch_object())
        {
            echo '<tr>
                        <td ></td>
                        <td>'.$reg->cantidad.'</td>
                        <td>'.$reg->costoU.'</td>
                        <td>'.$reg->nombre_generico.'</td>
                        <td>'.$reg->nombre_comercial.'</td>
                        <td>'.$reg->laboratorio.'</td>
                        <td>'.$reg->porcentajeVenta.'</td> 
                        <td>'.$reg->porcentajeVentaU.'</td> 
                        <td>'.$reg->precioVenta.'</td>
                        <td>'.$reg->precioVenta * $reg->cantidad.'</td>


                </tr>';

                         $total=$total+($reg->precioVenta*$reg->cantidad);

        }


        echo '<tfoot>
                                        <th>Total</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th><h4 id="Total">$ '.$total.'</h4><input type="hidden" name="total_compra"  id="total_compra"></th> 
                                        </tfoot>';

        break;


    case 'listar':
        $rspta=$ingreso->listar();
        $data=Array();//almacenara todos los registros que voy a mostrar
        while ($reg=$rspta->fetch_object()) //recorrere todos los registros almacenare en la variable reg y almacenare en el indices cada dato y recorrera todos los registros
        {
            $data[]=array(
                "0"=>($reg->estado=='Aceptado')?'<button class="btn btn-warning"><i class="fa fa-eye" onclick="mostrarIngreso('.$reg->idingreso.')"></i> </button>'.
                    ' <button class="btn btn-danger"><i class="fa fa-close" onclick="anular('.$reg->idingreso.')"></i> </button>':
                    '<button class="btn btn-warning"><i class="fa fa-eye" onclick="mostrar('.$reg->idingreso.')"></i> </button>',//al hacer click manda el idingreso
                "1"=>$reg->fecha,
                "2"=>$reg->proveedor,
                "3"=>$reg->usuario,
                "4"=>$reg->tipo_comprobante,
                "5"=>$reg->serie_comprobante.'-'.$reg->num_comprobante,
                "6"=>$reg->total_compra,


                "7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>': '<span class="label bg-red">Anulado</span>',
            );
        }

        $reult= array(
            "sEcho"=>1, //informacion para el datatable
            "iTotalRecords"=>count($data),//se envia el total de registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a vizualizar
            "aaData"=>$data
        );
        echo json_encode($reult);
        break;
    case 'detalleslista':
        $rspta=$ingreso->detalleslista();
        $data=Array();//almacenara todos los registros que voy a mostrar
        while ($reg=$rspta->fetch_object()) //recorrere todos los registros almacenare en la variable reg y almacenare en el indices cada dato y recorrera todos los registros
        {
            $data[]=array(
                //al hacer click manda el idingreso
                "0"=> '<input type="number" step=".01" min="0" style="background: #E6E6E6 ;"  id="filacantidad'.$reg->idingreso.'" VALUE="'. $reg->cantidad.'"   readonly >',
                "1"=>'<input   step=".01" min="0"  style="background: #E6E6E6 ;" id="filacostou'.$reg->idingreso.'" VALUE="'.$reg->costoU. ' "readonly >',
                "2"=>'<input step=".01" min="0" style="background: #E6E6E6 ;" id="filaporcentaje'.$reg->idingreso.'" VALUE="'.$reg->porcentajeV .' " readonly>',
                "3"=>'<input step=".01" min="0" style="background: #E6E6E6 ;" id="filaporcentajevu'.$reg->idingreso.'" VALUE="'.$reg->porcentajeVentaU .' " readonly>',
                "4"=>'<input  step=".01" min="0" style="background: #E6E6E6 ;" id="filapreciov'.$reg->idingreso.'" VALUE="'.$reg->precioVenta.' " readonly>',
                "5"=>$reg->nombre_comercial,
                "6"=>$reg->nombre_generico,
                "7"=>$reg->laboratorio,

                "8"=>
                    ' <button class="btn btn-info"><i class="fa fa-edit" onclick="activarEditar('.$reg->idingreso.')"></i> </button>'.
                    '<button class="btn btn-dropbox"><i class="fa fa-refresh" onclick="actualizarEdicion('.$reg->idingreso.')"></i> </button>'.
                    '<button id="btnguardareditar" type="button" class="btn btn-success"><i class="fa fa-save" onclick="editar('.$reg->idingreso.','.$reg->idarticulo.')"></i> </button>'
            );
        }

        $reult= array(
            "sEcho"=>1, //informacion para el datatable
            "iTotalRecords"=>count($data),//se envia el total de registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a vizualizar
            "aaData"=>$data
        );
        echo json_encode($reult);
        break;

    case 'selectProveedor':
        require_once "../modelos/Persona.php";
        $persona=new Persona();

        $res=$persona->listarP();

        while ($reg = $res->fetch_object())
        {
            echo '<option value=' .$reg->idpersona. '>'. $reg->nombre. '</option>';
        }

        break;


    case 'listarArticulos':

        require_once "../modelos/Articulo.php";

        $articulo = new Articulo();
        $rspta=$articulo->listararticuloIngreso();
        $data=Array();//almacenara todos los registros que voy a mostrar
        while ($reg=$rspta->fetch_object()) //recorrere todos los registros almacenare en la variable reg y almacenare en el indices cada dato y recorrera todos los registros
        {
            $data[]=array(
                "0"=>'<button class="btn btn-warning"><span class="fa fa-plus" onclick="agregarDetalleingreso('.$reg->idarticulo.',
                       \''.$reg->nombre_comercial.'\',\''.$reg->nombre_generico.'\',\''.$reg->categoria.'\')"></span> </button>',//al hacer click manda el idarticulo y el nombre
                "1"=>$reg->nombre_comercial,
                "2"=>$reg->nombre_generico,
                "3"=>$reg->categoria,
                "4"=>$reg->codigo,
                "5"=>$reg->stock,
                "6"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >",


            );
        }

        $reult= array(
            "sEcho"=>1, //informacion para el datatable
            "iTotalRecords"=>count($data),//se envia el total de registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a vizualizar
            "aaData"=>$data
        );
        echo json_encode($reult);

        break;
}

?>