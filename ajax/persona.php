<?php

require_once "../modelos/Persona.php";

$persona = new Persona();

$idpersona = isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"])  : "";//aqui recibo las primeras datos atravez del metodo POST
$tipo_persona = isset($_POST["tipo_persona"])? limpiarCadena($_POST["tipo_persona"])  : "";

$tipo_documento = isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"])  : "";
$num_documento = isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"])  : "";
$plazo_vencimiento = isset($_POST["plazo_vencimiento"])? limpiarCadena($_POST["plazo_vencimiento"])  : "";
$direccion = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"])  : "";
$telefono = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"])  : "";
$email = isset($_POST["email"])? limpiarCadena($_POST["email"])  : "";
$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]): "";


switch ($_GET["op"])
{
    case 'guardaryeditar':
        if(empty($idpersona))//si el id esta vacio
        {
            $rspta=$persona->insertar($tipo_persona,$nombre,$tipo_documento,$num_documento,$plazo_vencimiento,$direccion,$telefono,$email);
            echo $rspta ? "persona Registrada": "persona no se pudo registrar";

        } else {

            $rspta=$persona->editar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$plazo_vencimiento,$direccion,$telefono,$email);
            echo $rspta ? "persona actualizada" : "persona no se pudo actualizar";

        }

        break;

    case 'eliminar':
        $rspta=$persona->eliminar($idpersona);
        echo $rspta ? "persona desactivada" : "persona no se pudo desactivar";
        break;

    case 'mostrar':
        $rspta=$persona->mostrar($idpersona);
        echo json_encode($rspta);
        break;

    case 'listarp':
        $rspta=$persona->listarP();
        $data=Array();//almacenara todos los registros que voy a mostrar
        while ($reg=$rspta->fetch_object()) //recorrere todos los registros almacenare en la variable reg y almacenare en el indices cada dato y recorrera todos los registros
        {
            $data[]=array(
                "0"=>'<button class="btn btn-warning"><i class="fa fa-pencil" onclick="mostrar('.$reg->idpersona.')"></i> </button>'.' <button class="btn btn-danger"  onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i>  </button>',//al hacer click manda el idpersona
                "1"=>$reg->nombre,
                "2"=>$reg->tipo_documento,
                "3"=>$reg->num_documento,
                "4"=>$reg->telefono,
                "5"=>$reg->email

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


    case 'listarc':
        $rspta=$persona->listarC();
        $data=Array();//almacenara todos los registros que voy a mostrar
        while ($reg=$rspta->fetch_object()) //recorrere todos los registros almacenare en la variable reg y almacenare en el indices cada dato y recorrera todos los registros
        {
            $data[]=array(
                "0"=>'<button class="btn btn-warning"><i class="fa fa-pencil" onclick="mostrar('.$reg->idpersona.')"></i> </button>'.' <button class="btn btn-danger"  onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i>  </button>',//al hacer click manda el idpersona
                "1"=>$reg->nombre,
                "2"=>$reg->tipo_documento,
                "3"=>$reg->num_documento,
                "4"=>$reg->telefono,
                "5"=>$reg->email

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