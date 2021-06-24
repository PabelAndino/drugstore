<?php

require_once "../modelos/Laboratorio.php";

$categoria = new Laboratorio();

$idcategoria = isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"])  : "";//aqui recibo las primeras datos atravez del metodo POST
$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]): "";
$descripcion = isset($_POST["descripcion"])? limpiarCadena( $_POST["descripcion"]):"";

switch ($_GET["op"])
{
    case 'guardaryeditar':
        if(empty($idcategoria))//si el id esta vacio
        {
            $rspta=$categoria->insertar($nombre,$descripcion);
            echo $rspta ? "Laboratorio Registrada": "Laboratorio no se pudo registrar";

        } else {

            $rspta=$categoria->editar($idcategoria,$nombre,$descripcion);
            echo $rspta ? "Laboratorio actualizada" : "categoria no se pudo actualizar";

        }

        break;

    case 'desactivar':
         $rspta=$categoria->desactivar($idcategoria);
        echo $rspta ? "Laboratorio desactivada" : "categoria no se pudo desactivar";
        break;

    case 'activar':
        $rspta=$categoria->activar($idcategoria);
        echo $rspta ? "Laboratorio Activada" : "categoria no se pudo activar";
        break;

    case 'mostrar':
        $rspta=$categoria->mostrar($idcategoria);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta=$categoria->listar();
        $data=Array();//almacenara todos los registros que voy a mostrar
        while ($reg=$rspta->fetch_object()) //recorrere todos los registros almacenare en la variable reg y almacenare en el indices cada dato y recorrera todos los registros
        {
            $data[]=array(
              "0"=>($reg->condicion)?'<button class="btn btn-warning"><i class="fa fa-pencil" onclick="mostrar('.$reg->idcategoria.')"></i> </button>'.' <button class="btn btn-danger"><i class="fa fa-close" onclick="desactivar('.$reg->idcategoria.')"></i> </button>':'<button class="btn btn-warning"><i class="fa fa-pencil" onclick="mostrar('.$reg->idcategoria.')"></i> </button>'.' <button class="btn btn-primary"><i class="fa fa-check" onclick="activar('.$reg->idcategoria.')"></i> </button>',//al hacer click manda el idcategoria
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