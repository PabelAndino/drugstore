<?php

//se activa el almacenamiento el Buffer para iniciar sesion
ob_start();
session_start();

if(!isset($_SESSION["nombre"]))
{
    echo 'Debe ingresar al sistema primero para ver los reportes';
}
else
{
    if ($_SESSION['almacen'] == 1)

    {


        require ('PDF_MC_Table.php');


        //crea una instancia de la clase para generar un documento PDF

        $pdf=new PDF_MC_Table();


//agrega la primera pagina al documento
        $pdf->AddPage();

        //el margen superorio r de 25 pixeles
        $y_axis_initial = 25;

        //Configuracion del tipo de letra y el titulo

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(40,6,'',0,0,'C');
        $pdf->Cell(100,6,'Lista de articulos',1,0,'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial','B',10);
        $pdf->SetFillColor(232,232,232);
        $pdf->Cell(58,6,'Nombre',1,0,'C',1);
        $pdf->Cell(50,6,utf8_decode('Categoría'),1,0,'C',1);
        $pdf->Cell(30,6,utf8_decode('Código'),1,0,'C',1);
        $pdf->Cell(12,6,'Stock',1,0,'C',1);
        $pdf->Cell(35,6,utf8_decode('Descripción'),1,0,'C',1);
        $pdf->Ln(10);//quiero que las celdas esten dentro de un rectangulo


    //Se crean las las filas segun los regustros de la consulta mysql
        require_once '../modelos/Articulo.php';
        $articulos = new Articulo();

        $rspta= $articulos->listar();

        $pdf->SetWidths(array(58,50,30,12,35));

        while($reg= $rspta->fetch_object())
        {
            $nombre = $reg->nombre;
            $categoria = $reg->categoria;
            $codigo = $reg->codigo;
            $stock = $reg->stock;
            $descripcion =$reg->descripcion;

            $pdf->SetFont('Arial','',10);
            $pdf->Row(array(utf8_decode($nombre),utf8_decode($categoria),$codigo,$stock,utf8_decode($descripcion)));


        }

        //Mostramos el documento pdf
        $pdf->Output();



    }//fin deel if de inicio de session que da los permisos

    else {
        echo 'No tiene permiso para ver este reporte';
    }


}

//libera el espacio del BUFFER
ob_end_flush();
?>



