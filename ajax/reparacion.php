<?php
if (strlen(session_id()) < 1)
    session_start();

require_once "../modelos/Reparacion.php";


$reparacion=new Reparacion();
$idarticulo;


$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idarticuloRP=isset($_POST["rar"])? limpiarCadena($_POST["rar"]):"";
$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$serie_comprobante=isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"]):"";
$num_comprobante=isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";//********************
$tipoventa = isset($_POST["inputestado"])? limpiarCadena($_POST["inputestado"]):"";
$detalle = isset($_POST["comment"])? limpiarCadena($_POST["comment"]):"";
$equipo = isset($_POST["equipo"])? limpiarCadena($_POST["equipo"]):"";
$precioTotal = isset($_POST["precio"])? limpiarCadena($_POST["precio"]):"";

switch ($_GET["op"]){
    case 'guardaryeditar':

        if (empty($idventa)){

            $rspta=$reparacion->insertar($idcliente,$idusuario,$tipo_comprobante,$serie_comprobante,
                                         $num_comprobante,$fecha_hora,$impuesto,$precioTotal,$tipoventa,$detalle,$equipo,$idarticuloRP);

            echo $rspta ? "Reparacion Registrada" : "No se pudieron registrar todos los datos de la Reparacion";
          //  echo "GUARDA";
        }
        else {
            $rspta=$reparacion->editar($detalle,$equipo,$precioTotal,$idventa);
            echo $rspta ? "Reparacion Modificada" : "No se pudo modificar la Reparacion";
           // echo "ACTUALIZA";
        }
        break;

    case 'entregado':

        $resta=$reparacion->entregar($idventa,$precioTotal);
        echo $resta ? "Equipo Entregado" : "No se pududo registrar el equipo";
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

        $rspta=$reparacion->mostrar($idventa);
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


    case 'listarDetalle':
        //Recibimos el idingreso
        $id=$_GET['id'];
        $rspta = $reparacion->listarDetalle($id);
        $total=0;
        echo '<thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precio Venta</th>
                                    <th>Descuento</th>
                                    <th>Subtotal</th>
                                </thead>';

        while ($reg = $rspta->fetch_object())
        {
            echo '<tr><td></td>
                   <td>'.$reg->nombre.'</td>
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
                                    <th><h4 id="totals">C$/.'.$total.'</h4><input type="hidden" name="total_ventas" id="total_ventas"></th> 
                                </tfoot>';
        break;

    case 'listar':
        $rspta=$reparacion->listar1();
        //Vamos a declarar un array

        $data= Array();


        while ($reg=$rspta->fetch_object()){


                $urlTICKET='../reportes/TicketReparacion.php?id=';


                $urlFACT='../reportes/FacturaReparacion.php?id=';


            $data[]=array(
                "0"=>(($reg->estado=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>'.
                    ' <button class="btn btn-danger" onclick="anular('.$reg->idventa.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>').

                   // '<a target="_blank" href="'.$urlFACT.$reg->idventa.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
                    '<a target="_blank" href="'.$urlTICKET.$reg->idventa.'"> <button class="btn btn-flickr"><i class="fa fa-file-text"></i></button></a>',

                "1"=>$reg->fecha,
                "2"=>$reg->cliente,
                "3"=>$reg->usuario,
                "4"=>$reg->tipo_comprobante,
                "5"=>$reg->num_comprobante,
                "6"=>$reg->total_venta,
                "7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Entregado</span>':
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
    case 'listar2':
        $rspta=$reparacion->listar2();
        //Vamos a declarar un array

        $data= Array();


        while ($reg=$rspta->fetch_object()){


            $urlTICKET='../reportes/TicketReparacion.php?id=';


            $urlFACT='../reportes/FacturaReparacion.php?id=';


            $data[]=array(
                "0"=>(($reg->estado=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.','.$reg->total_venta.')"><i class="fa fa-eye"></i></button>'.
                        ' <button class="btn btn-facebook" onclick="entregar('.$reg->idventa.','.$reg->precio.','.$reg->articulo.')"><i class="">Entregar</i></button>':
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>').

                    // '<a target="_blank" href="'.$urlFACT.$reg->idventa.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
                    '<a target="_blank" href="'.$urlTICKET.$reg->idventa.'"> <button class="btn btn-flickr"><i class="fa fa-file-text"></i></button></a>',

                "1"=>$reg->fecha,
                "2"=>$reg->cliente,
                "3"=>$reg->usuario,
                "4"=>$reg->tipo_comprobante,
                "5"=>$reg->num_comprobante,
                "6"=>$reg->total_venta,
                "7"=>($reg->estado=='Aceptado')?'<span class="label bg-purple">Recibido</span>':
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
                "0"=>'<button class="btn btn-warning" id="botonon" onclick="agregarDetalle('.$reg->stock.',\''.$reg->idarticulo.'\', \''.$reg->nombre.'\',  \''.$reg->precio_compra.'\',\''.$reg->precio_venta.'\')" ><span class="fa fa-plus"></span></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->categoria,
                "3"=>$reg->codigo,
                "4"=>$reg->stock,
                "5"=>$reg->precio_compra,
                "6"=>$reg->precio_venta,
                "7"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >"
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

    case 'entregar':


        $rspta=$reparacion->entregar($idventa,$idarticuloRP,$precioTotal);

        echo $rspta ? "Reparacion Entregada" : "No se pudieron registrar todos los datos de la Reparacion";

        break;

    case 'entregarActualizarVenta':

        $respt=$reparacion->entregarActualizaVenta($idventa);
        echo $respt ? "Reparacion Entregada" : "No se pudo entregar la reparacion";
        break;

    case 'selectAR':
        $rspta=$reparacion->artReparacion();
        //Codificar el resultado utilizando json
        while ($reg = $rspta->fetch_array())
        {
            echo $reg[0];
        }
        break;
}
?>