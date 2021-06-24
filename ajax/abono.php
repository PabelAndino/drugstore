<?php
if (strlen(session_id()) < 1)
    session_start();

require_once "../modelos/Abono.php";


$abono=new Abono();



$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";

$num_comprobante=isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
$fecha=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$total_abono=isset($_POST["cantidad_abonar"])? limpiarCadena($_POST["cantidad_abonar"]):"";
$tipoVenta=isset($_POST["tipoVenta"])? limpiarCadena($_POST["tipoVenta"]):"";
$idabono = isset($_POST["idabonok"])? limpiarCadena($_POST["idabonok"]):"";
$datoId = 0;

switch ($_GET["op"]){
    case 'guardaryeditar':

        if (!empty($idventa)){

            $rspta=$abono->insertar($idventa,$fecha,$idusuario,$tipo_comprobante,$num_comprobante,$total_abono);

            echo $rspta ? "Abono Registrado" : "No se pudieron registrar todos los datos del Abono";
        }else{

        }

        break;
    case 'guardaryeditar2':

        if (!empty($idventa)){
            require_once "../modelos/Venta.php";
            $venta = new Venta();
           // $rspta = $venta->insertar($idusuario,$tipo_comprobante,$num_comprobante,$fecha,$total_abono,$tipoVenta,);
            //$rspta=$abono->insertar($idventa,$fecha,$idusuario,$tipo_comprobante,$num_comprobante,$total_abono);

            echo $rspta ? "Abono Registrado" : "No se pudieron registrar todos los datos del Abono";
        }else{

        }

        break;//test esta malo
    case 'insertarDetalleAbono':

        $rspta = $abono->insertarDetalleAbono($idabono,$fecha,$total_abono);

        echo $rspta ? 'Abono guardado correctamente':'Detalle de abono no se pudo guardar';

        break;

    case 'abonoPagado':
        $rspta=$abono->abonoPagado($idventa,$idcliente);
        echo $rspta ? "Venta Pagada" : "No se pudieron registrar los datos de para Pagar la venta";
        break;

    case  'abonoPagadoActualizaEstado':

        $rspta=$abono->abonoPagadoActualizaEstado($idventa);
         echo $rspta ? "Venta Pagada , Se actualizo el estado del abono" : "No se pudieron registrar los datos de para Pagar la venta";

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

        $rspta=$abono->sumarNumeroFactura();
        //Codificar el resultado utilizando json

        while ($reg = $rspta->fetch_array())
        {
            echo $reg[0];
        }

        break;


    case 'listarDetalle':
        //Recibimos el idingreso
        $id=$_GET['id'];
        $rspta = $abono->listarDetalle($id);
        $total=0;
        echo '<thead style="background-color:#f5bbb8">
                                    <th>Opciones</th>
                                    <th>Nombre Comercial</th>
                                    <th>Nombre Generico</th>
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
                                    <th><h4 id="total">C$/ '.$total.'</h4><input type="hidden" name="total_venta" id="total_venta" value="'.$total.'"></th> 
                                </tfoot>';
        break;

    case 'listadoAbonos':
        $rspta=$abono->listadoAbonos();
        //Vamos a declarar un array

        $data= Array();


        while ($reg=$rspta->fetch_object()){

            $urlTICKET='../reportes/TicketRepAbono.php?id=';
            $urlFACT='../reportes/FacturaRepAbono.php?id=';


            $data[]=array(
                "0"=>



                    '<a target="_blank" href="'.$urlTICKET.$reg->idventa.'"> <button class="btn btn-flickr"><i class="fa fa-file-text"></i></button></a>',



                "1"=>$reg->fecha,
                "2"=>$reg->cliente,
                "3"=>$reg->usuario,
                "4"=>$reg->tipo_comprobante,
                "5"=>$reg->num_comprobante,
                "6"=>$reg->total_venta,
                "7"=>($reg->estado=='Pagado')?'<span class="label bg-green">Pagado</span>':
                    '<span class="label bg-red">Pendiente</span>'
            );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);

        break;

    case 'listadoVentas':
        $rspta=$abono->listadoVentas();
        //Vamos a declarar un array

        $data= Array();


        while ($reg=$rspta->fetch_object()){

            $urlTICKET='../reportes/TicketRepAbono.php?id=';
            $urlFACT='../reportes/FacturaRepAbono.php?id=';


            $data[]=array(
                "0"=>'<span class="shadow-sm label-danger">Creditos</span>',



                "1"=>$reg->fecha,
                "2"=>$reg->cliente,
                "3"=>$reg->usuario,
                "4"=>$reg->tipo_comprobante,
                "5"=>$reg->num_comprobante,
                "6"=>$reg->total_venta,
                "7"=>($reg->estado=='Contado')?'<span class="label bg-green">Pagado</span>':
                    '<span class="label bg-red">Pendiente</span>'
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


    case 'listarVentasCliente':
        $id=$_GET['id'];
        $rspta=$abono->listarVentasCliente($id);
        //Vamos a declarar un array

        $data= Array();


        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-success" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-plus"></i></button>',
                "1"=>$reg->fecha,
                "2"=>$reg->cliente,
                "3"=>$reg->usuario,
                "4"=>$reg->tipo_comprobante,
                "5"=>$reg->num_comprobante,
                "6"=>$reg->total_venta,
                "7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
                    '<span class="label bg-red">Anulado</span>'
            );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);

        break;

    case 'listarAbonos':
        $id=$_GET['id'];
        $rspta = $abono->mostrarAbonos($id);
        $total=0;
        echo '<thead style="background-color:#bcf5c2">
                                    <th>Fecha</th>
                                    
                                    <th>Cantidad</th>
                                 
                                   
                                </thead>';
      /*  if ($rspta = null){

        }*/

        while ($reg = $rspta->fetch_object())
        {
            echo '<tr>
                    <td>'.$reg->fecha.'</td>
                   
                    <td>'.$reg->cantidad.'</td>
                    </tr>';
            $total=$total+($reg->cantidad);

            $datoId = $reg->idabono;



        }
        echo '<tfoot>
                                    <th>TOTAL</th>
                                   
                                   
                                    
                               
                                    <th><h4 id="total">C$/ '.$total.'</h4><input type="hidden" name="total_abono" id="total_abono" value="'.$total.'"></th> 
                                </tfoot>';



        echo '<input type="hidden" name="idabonok2" id="idabonok2" value="'.$datoId.'">';

        break;


    case 'selectCliente':



        $rspta = $abono->listarC();

        while ($reg = $rspta->fetch_object())
        {
            echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
        }
        break;
}
?>