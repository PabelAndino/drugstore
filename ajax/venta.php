<?php
if (strlen(session_id()) < 1)
    session_start();

require_once "../modelos/Venta.php";


$venta=new Venta();



$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$cliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";

$num_comprobante=isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";

$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
$tipoventa = isset($_POST["inputContadoCredito"])? limpiarCadena($_POST["inputContadoCredito"]):"";


switch ($_GET["op"]){
    case 'guardaryeditar':

        if (empty($idventa)){

            $rspta=$venta->insertar($cliente,$idusuario,$tipo_comprobante,$num_comprobante,$fecha_hora,$total_venta,$tipoventa,
                $_POST["idarticulo"],$_POST["cantidad"],$_POST["preciocompra"],$_POST["precio_venta"],$_POST["descuento"]);

            echo $rspta ? "Venta registrada" : "No se pudieron registrar todos los datos de la venta";
        }
        else {
        }
        break;

    case 'anular':
    $rspta=$venta->anular($idventa);
    echo $rspta ? "Venta anulada" : "Venta no se puede anular";
    break;

    case 'anularDetalle':
        $rspta=$venta->anularDetalle($idventa);
        echo $rspta ? "Venta anulada" : "Venta no se puede anular";
        break;

    case 'mostrar':

        $rspta=$venta->mostrar($idventa);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'mostrarNumero': //MUSTRA EL NUMERO DE SERIE AUTOINCREMENTADO

        $rspta=$venta->sumarNumeroFactura();
        //Codificar el resultado utilizando json

        while ($reg = $rspta->fetch_array())
        {
            echo $reg[0];
        }

        break;

    case 'imprimirTicket':
        $rspta=$venta->retornaID();
        //Codificar el resultado utilizando json

        while ($reg = $rspta->fetch_object())
        {

            echo $reg[0];
        }
        break;

    case 'listarDetalle':
        //Recibimos el idingreso
        $id=$_GET['id'];
        $rspta = $venta->listarDetalle($id);
        $total=0;
        echo '<thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Nombre Com</th>
                                    <th>Nombre Gen</th>
                                    <th>Cantidad</th>
                                    <th>Precio Venta</th>
                                    <th>Descuento</th>
                                    <th>Subtotal</th>
                                </thead>';

        while ($reg = $rspta->fetch_object())
        {
            echo '<tr><td></td>
                   <td>'.$reg->nombre_comercial.'</td>
                   <td>'.$reg->nombre_generico.'</td>
                   <td>'.$reg->cantidad.'</td>
                   <td>'.$reg->precio_venta.'</td>
                   <td>'.$reg->descuento.'</td>
                   <td>'.$reg->subtotal.'</td></tr>';
            $total=$total+($reg->precio_venta*$reg->cantidad-$reg->descuento);
        }
        echo '<tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="totals">C$/.'.$total.'</h4><input type="hidden" name="total_ventas" id="total_ventas"></th> 
                                </tfoot>';
        break;

    case 'listar':
        $rspta=$venta->listar();
        //Vamos a declarar un array

        $data= Array();


        while ($reg=$rspta->fetch_object()){


                $urlTICKET='../reportes/TicketRep.php?id=';


                $urlFACT='../reportes/FacturaRep.php?id=';


            $data[]=array(
                "0"=>(($reg->estado=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>'.
                    ' <button class="btn btn-danger" onclick="anular('.$reg->idventa.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>').

                   // '<a target="_blank" href="'.$urlFACT.$reg->idventa.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
                    '<a target="_blank" href="'.$urlTICKET.$reg->idventa.'"> <button class="btn btn-flickr"><i class="fa fa-file-text"></i></button></a>'.
                    '<a target="_blank" href="'.$urlFACT.$reg->idventa.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
                "1"=>$reg->fecha,
                "2"=>$reg->cliente,
                "3"=>$reg->usuario,
                "4"=>$reg->tipo_comprobante,
                "5"=>$reg->num_comprobante,
                "6"=>$reg->total_venta,
                "7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
                    '<span class="label bg-red">Anulado</span>',
                "8"=>($reg->tipoVenta=='Contado')?'<span class="label bg-primary">Contado</span>':
                '<span class="label bg-orange">Credito</span>'
            );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);

        break;

    case 'selectCliente':

        require_once "../modelos/Persona.php";
        $persona = new Persona();

        $rspta = $persona->listarC();

        while ($reg = $rspta->fetch_object())
        {
            echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
        }
        break;

    case 'listarArticulosVenta':

        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();

        $rspta=$articulo->listarActivosVenta();
        //Vamos a declarar un array
        $data= Array();

        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" id="botonon" onclick="agregarDetalle('.$reg->stock.',\''.$reg->idarticulo.'\', 
                       \''.$reg->nombre_comercial.'\', \''.$reg->nombre_generico.'\', \''.$reg->laboratorio.'\',  \''.$reg->precio_compra.'\',\''.$reg->precio_venta.'\')" ><span class="fa fa-plus"></span></button>',
                "1"=>$reg->nombre_comercial,
                "2"=>$reg->nombre_generico,
                "3"=>$reg->laboratorio,
                "4"=>$reg->codigo,
                "5"=>$reg->stock,
                "6"=>$reg->precio_compra,
                "7"=>$reg->precio_venta,
                "8"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >"
            );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
        break;


    case 'imprimirFactura':



        break;

}
?>