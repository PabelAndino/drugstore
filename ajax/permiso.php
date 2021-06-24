

<?php

require_once "../modelos/Permiso.php";

$permiso = new Permiso();


switch ($_GET["op"])
{


    case 'listar':
        $rspta=$permiso->listar();
        $data=Array();//almacenara todos los registros que voy a mostrar
        while ($reg=$rspta->fetch_object()) //recorrere todos los registros almacenare en la variable reg y almacenare en el indices cada dato y recorrera todos los registros
        {
            $data[]=array(
                "0"=>$reg->nombre

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

