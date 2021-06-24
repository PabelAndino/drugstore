<?php

require_once "../modelos/Laboratorio.php";

$laboratorio = new Laboratorio();

$idlaboratorio = isset($_POST["idlaboratorio"])? limpiarCadena($_POST["idlaboratorio"])  : "";//aqui recibo las primeras datos atravez del metodo POST
$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]): "";
$descripcion = isset($_POST["descripcion"])? limpiarCadena( $_POST["descripcion"]):"";

switch ($_GET["op"])
{
    case 'guardaryeditar':
        if(empty($idlaboratorio))//si el id esta vacio
        {
            $rspta=$laboratorio->insertar($nombre,$descripcion);
            echo $rspta ? "Laboratorio Registrado": "Laboratorio no se pudo registrar";

        } else {

            $rspta=$laboratorio->editar($idlaboratorio,$nombre,$descripcion);
            echo $rspta ? "Laboratorio actualizado" : "Laboratorio no se pudo actualizar";

        }

        break;

    case 'desactivar':
         $rspta=$laboratorio->desactivar($idlaboratorio);
        echo $rspta ? "Laboratorio desactivado" : "laboratorio no se pudo desactivar";
        break;

    case 'activar':
        $rspta=$laboratorio->activar($idlaboratorio);
        echo $rspta ? "Laboratorio Activado" : "laboratorio no se pudo activar";
        break;

    case 'mostrar':
        $rspta=$laboratorio->mostrar($idlaboratorio);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta=$laboratorio->listar();
        $data=Array();//almacenara todos los registros que voy a mostrar
        while ($reg=$rspta->fetch_object()) //recorrere todos los registros almacenare en la variable reg y almacenare en el indices cada dato y recorrera todos los registros
        {
            $data[]=array(
              "0"=>($reg->condicion)?'<button class="btn btn-warning"><i class="fa fa-pencil" onclick="mostrar('.$reg->idlaboratorio.')"></i> </button>'.' <button class="btn btn-danger"><i class="fa fa-close" onclick="desactivar('.$reg->idlaboratorio.')"></i> </button>':'<button class="btn btn-warning"><i class="fa fa-pencil" onclick="mostrar('.$reg->idlaboratorio.')"></i> </button>'.
                  ' <button class="btn btn-primary"><i class="fa fa-check" onclick="activar('.$reg->idlaboratorio.')"></i> </button>',//al hacer click manda el idlaboratorio
              "1"=>$reg->nombre,
              "2"=>$reg->descripcion,
              "3"=>($reg->condicion)?'<span class="label bg-green">Activado</span>': '<span class="label bg-red">Desactivado</span>',
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