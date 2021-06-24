<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1) 
  session_start();

if (!isset($_SESSION["nombre"]))
{
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}
else
{
if ($_SESSION['ventas']==1)
{
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link href="../public/css/ticket.css" rel="stylesheet" type="text/css">
</head>
<body onload="window.print();"><!--IMPRIME DIRECTAMENTE AL LLAMARLO-->
<?php

//Incluímos la clase Venta
require_once "../modelos/Abono.php";
//Instanaciamos a la clase con el objeto venta
$abono = new Abono();
//En el objeto $rspta Obtenemos los valores devueltos del método ventacabecera del modelo
$rspta = $abono->ventacabecera($_GET["id"]);
//Recorremos todos los valores obtenidos
$reg = $rspta->fetch_object();

//Establecemos los datos de la empresa

$empresa = "MARALTTO SOLUCIONES TECTOLOGICAS";
$documento = "2737-00000";
$direccion = "Radio Redel 1 C al norte";
$telefono = "2737-00000";
$email = "pabelwitt@gmail.com";
?>
<div class="zona_impresion">
<!-- codigo imprimir -->
<br>
<table border="1" align="center" width="300px">
    <tr>
        <td align="center">
        <!-- Mostramos los datos de la empresa en el documento HTML -->
        .::<strong> <?php echo $empresa; ?></strong>::.<br>
        <?php echo $documento; ?><br>
        <?php echo $direccion .' - '.$telefono; ?><br>
        </td>
    </tr>
    <tr>
        <td align="center"><?php echo $reg->fecha; ?></td>
    </tr>
    <tr>
      <td align="center"></td>
    </tr>
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td>Cliente: <?php echo $reg->cliente; ?></td>
    </tr>
    <tr>
        <td><?php echo ": ".$reg->num_documento; ?></td>
    </tr>
    <tr>
        <td>Nº de venta: <?php echo " - ".$reg->num_comprobante ; ?></td>
    </tr>


</table>
<br>
<!-- Mostramos los detalles de la venta en el documento HTML -->
<table border="0" align="center" width="300px">
    <tr>
        <td>Fecha</td>
        <td>Cantidad</td>

    </tr>
    <tr>
      <td colspan="3">==========================================</td>
    </tr>
    <?php
    $rsptad = $abono->ventadetalle($_GET["id"]);
    $cantidad=0;
    while ($regd = $rsptad->fetch_object()) {
        echo "<tr>";
        echo "<td>".$regd->fecha."</td>";
        echo "<td></td>";
        echo "<td>".$regd->cantidad."</td>";

        echo "</tr>";
        $cantidad+=$regd->cantidad;
    }
    ?>
    <!-- Mostramos los totales de la venta en el documento HTML -->
    <tr>

    <td align="left"><b>Total Abonado:</b></td>
        <td></td>
    <td><b>C$/  <?php echo $cantidad;  ?></b></td>
    </tr>

    <tr>

        <td align="left"><b>Pendiente:</b></td>
        <td></td>
        <td><b>C$/  <?php echo ($reg->total) - $cantidad;  ?></b></td>
    </tr>

    <tr>

        <td align="left"><b>Deuda Total:</b></td>
        <td></td>
        <td><b>C$/  <?php echo $reg->total;  ?></b></td>
    </tr>

    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>      
    <tr>
      <td colspan="3" align="center">¡Gracias por su compra!</td>
    </tr>
    <tr>
      <td colspan="3" align="center">ElsaSoft</td>
    </tr>
    <tr>
      <td colspan="3" align="center"> Nicaragua- Jalapa</td>
    </tr>
    
</table>
<br>
</div>
<p>&nbsp;</p>

</body>
</html>
<?php 
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>