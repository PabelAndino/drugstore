<?php
if (strlen(session_id()) < 1)
    session_start();

require_once "../modelos/Pedido.php";


$pedido=new Pedido();



$idpedido=isset($_POST["idpedido"])? limpiarCadena($_POST["idpedido"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario=$_SESSION["idusuario"];
$idProveedor = isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";

$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$descripcion=isset($_POST["comment"])? limpiarCadena($_POST["comment"]):"";
$total=isset($_POST["total_Pedido"])? limpiarCadena($_POST["total_Pedido"]):"";



switch ($_GET["op"]){

    case 'guardaryeditar':

        if (empty($idpedido)){

            $rspta=$pedido->insertar($idcliente,$idusuario,$idProveedor,$fecha_hora,$descripcion,$total,
                $_POST["articulo"],$_POST["cantidad"],$_POST["precioU"],$_POST["precioV"]);

            echo $rspta ? "Pedido registrado" : "No se pudieron registrar todos los datos del Pedido";
        }
        else {
        }
        break;

    case 'anular':
    $rspta=$pedido->anular($idpedido);
    echo $rspta ? "Pedido Entregado" : "Pedido no se pudo entregar";
    break;

    case 'anularDetalle':
        $rspta=$venta->anularDetalle($idpedido);
        echo $rspta ? "Pedido Entregado" : "Pedido no se pudo entregar";
        break;

    case 'mostrar':

        $rspta=$pedido->mostrar($idpedido);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta=$pedido->listar();
        $data=Array();//almacenara todos los registros que voy a mostrar
        while ($reg=$rspta->fetch_object()) //recorrere todos los registros almacenare en la variable reg y almacenare en el indices cada dato y recorrera todos los registros
        {
            $data[]=array(
                "0"=>($reg->estado)?'<button class="btn btn-warning"><i class="fa fa-pencil" onclick="mostrar('.$reg->idpedido.')"></i> </button>'.' <button class="btn btn-bitbucket"><i class="fa fa-close" onclick="anular('.$reg->idpedido.')"></i> </button>':'<button class="btn btn-warning"><i class="fa fa-pencil" onclick="mostrar('.$reg->idpedido.')"></i> </button>'.' <button class="btn btn-primary"><i class="fa fa-check" onclick="activar('.$reg->idpedido.')"></i> </button>',//al hacer click manda el idarticulo
                "1"=>$reg->fecha,
                "2"=>$reg->cliente,
                "3"=>$reg->proveedor,
                "4"=>$reg->usuario,
                "5"=>$reg->descripcion,
                "6"=>$reg->total,
                "7"=>($reg->estado == 'Pendiente' )?'<span class="label bg-green">Pendiente</span>':'<span class="label bg-red">Entregado</span>'
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
        $rspta = $pedido->listarDetalle($id);
        $total=0;
        $subtotal = 0;
        echo '<thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Precio Venta</th>
                                    <th>Subtotal</th>
                                </thead>';

        while ($reg = $rspta->fetch_object())
        {
            echo '<tr ><td></td><td>'.$reg->articulo.'</td><td>'.$reg->cantidad.'</td><td>'.$reg->precioU.'</td><td>'.$reg->precioV.'</td><td>'.($reg->precioV*$reg->cantidad).'</td></tr>';
            $total=$total+($reg->precioV*$reg->cantidad);
        }
        echo '<tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="total">C$/.'.$total.'</h4><input type="hidden" name="total_venta" id="total_venta"></th> 
                                </tfoot>';
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








        break;

}
?>