<?php

require_once "../modelos/Consultas.php";

$consulta = new Consultas();



switch ($_GET["op"])
{

    case 'comprasfecha':

        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];

        $rspta=$consulta->comprasFechas($fecha_inicio,$fecha_fin);

        $data=Array();//almacenara todos los registros que voy a mostrar
        while ($reg=$rspta->fetch_object()) //recorrere todos los registros almacenare en la variable reg y almacenare en el indices cada dato y recorrera todos los registros
        {
            $data[]=array(
                "0"=>$reg->fecha,
                "1"=>$reg->usuario,
                "2"=>$reg->proveedor,
                "3"=>$reg->tipo_comprobante,
                "4"=>$reg->num_comprobante,
                "5"=>$reg->total_compra,

                "6"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>': '<span class="label bg-red">Anulado</span>',
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


    case 'ventasFechaCliente':

        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];
        $idcliente=$_REQUEST["idcliente"];

        $rspta=$consulta->ventasFechaCliente($fecha_inicio,$fecha_fin,$idcliente);

        $data=Array();//almacenara todos los registros que voy a mostrar
        while ($reg=$rspta->fetch_object()) //recorrere todos los registros almacenare en la variable reg y almacenare en el indices cada dato y recorrera todos los registros
        {
            $data[]=array(
                "0"=>$reg->fecha,
                "1"=>$reg->usuario,
                "2"=>$reg->cliente,
                "3"=>$reg->tipo_comprobante,
                "4"=>$reg->serie_comprobante.''.$reg->num_comprobante,
                "5"=>$reg->total_venta,
                "6"=>$reg->impuesto,
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

    case 'ventasFecha':

        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];


        $rspta=$consulta->ventasFecha($fecha_inicio,$fecha_fin);

        $data=Array();//almacenara todos los registros que voy a mostrar
        while ($reg=$rspta->fetch_object()) //recorrere todos los registros almacenare en la variable reg y almacenare en el indices cada dato y recorrera todos los registros
        {
            $data[]=array(
                "0"=>$reg->fecha,
                "1"=>$reg->usuario,
                "2"=>$reg->cliente,
                "3"=>$reg->num_comprobante,
                "4"=>$reg->nombre_comercial,
                "5"=>$reg->nombre_generico,
                "6"=>$reg->precioCompra,

                "7"=>$reg->vendido,
                "8"=>$reg->precio_venta,
                "9"=>$reg->cantidad,
                "10"=>$reg->descuento,
                "11"=>$reg->totalVenta,
                "12"=>'-',
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