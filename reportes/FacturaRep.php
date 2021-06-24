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
    if ($_SESSION['ventas'] == 1)

    {


        require ('Factura.php');


        $logo = "Logo.jpg";
        $ext_logo = "jpg";
        $empresa = "MARALTTO SOLUCIONES TECTOLOGICAS";
        $documento = "2737-00000";
        $direccion = "Radio Redel 1 C al norte";
        $telefono = "2737-00000";
        $email = "pabelwitt@gmail.com";


        //Los datos de la cabecera
        require_once "../modelos/Venta.php";
        $venta= new Venta();
        $rsptav = $venta->ventacabecera($_GET["id"]);

        //todos los valores que se obtenganç
        $regv = $rsptav->fetch_object();

//Establecemos la configuración de la factura
        $pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
        $pdf->AddPage();

        //Enviamos los datos de la empresa al método addSociete de la clase Factura
        $pdf->addSociete(utf8_decode($empresa),
            $documento."\n" .
            utf8_decode("Dirección: ").utf8_decode($direccion)."\n".
            utf8_decode("Teléfono: ").$telefono."\n" .
            "Email : ".$email,$logo,$ext_logo);
        $pdf->fact_dev( "$regv->tipo_comprobante ", "$regv->serie_comprobante-$regv->num_comprobante" );
        $pdf->temporaire( "" );//////LA MARCA DE AGUA
        $pdf->addDate( $regv->fecha);

        //Enviamos los datos del cliente al método addClientAdresse de la clase Factura
        $pdf->addClientAdresse(utf8_decode($regv->cliente),"Domicilio: ".utf8_decode($regv->direccion),$regv->tipo_documento.": ".$regv->num_documento,"Email: ".$regv->email,"Telefono: ".$regv->telefono);

        //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
        $cols=array( "CODIGO"=>23,
            "DESCRIPCION"=>78,//ESTE NUMERO ES EL ANCHO DE LA COLUMNA
            "CANTIDAD"=>22,
            "P.U."=>25,
            "DSCTO"=>20,
            "SUBTOTAL"=>22);
        $pdf->addCols( $cols);
        $cols=array( "CODIGO"=>"L",//LA L ES LEFT DE IZQUIERDA ES DECIR QUE SE ALINEE A LA IZQUIERDA
            "DESCRIPCION"=>"L",
            "CANTIDAD"=>"C",
            "P.U."=>"R",
            "DSCTO" =>"R",
            "SUBTOTAL"=>"C");//QUE SE ALINEE AL CENTRO
        $pdf->addLineFormat( $cols);
        $pdf->addLineFormat($cols);

        //Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
        $y= 89;

        //Obtenemos todos los detalles de la venta actual
        $rsptad = $venta->ventadetalle($_GET["id"]);

        while ($regd = $rsptad->fetch_object()) {
            $line = array( "CODIGO"=> "$regd->codigo",
                "DESCRIPCION"=> utf8_decode("$regd->articulo"),
                "CANTIDAD"=> "$regd->cantidad",
                "P.U."=> "$regd->precio_venta",
                "DSCTO" => "$regd->descuento",
                "SUBTOTAL"=> "$regd->subtotal");
            $size = $pdf->addLine( $y, $line );
            $y   += $size + 2;
        }

//Convertimos el total en letras
        require_once "Letras.php";
        $V=new EnLetras();
        $con_letra=strtoupper($V->ValorEnLetras($regv->total_venta,"CORDOBAS"));
        $pdf->addCadreTVAs("---".$con_letra);

//Mostramos el impuesto
        $pdf->addTVAs( $regv->impuesto, $regv->total_venta,"C$/ ");
        $pdf->addCadreEurosFrancs("IGV"." $regv->impuesto %");
        $pdf->Output('Reporte de Venta','I');


    }//fin deel if de inicio de session que da los permisos

    else {
        echo 'No tiene permiso para ver este reporte';
    }


}

//libera el espacio del BUFFER
ob_end_flush();
?>



