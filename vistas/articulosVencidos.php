<?php

//se activa el almacenamiento el Buffer para iniciar sesion
ob_start();
session_start();

if(!isset($_SESSION["nombre"]))
{
    header("Location: login.html");
}
else
{

    if ($_SESSION['almacen'] == 1)

    {

        require 'header.php';
        ?>
        <!--Contenido-->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h1 class="box-title">Articulos a Vencerse
                                  <!-- <button class="btn btn-success" onclick="mostrarform(true)" id="btnAgregar"><i class="fa fa-plus-circle"></i> Agregar </button><a target="_blank" href="../reportes/rptarticulos.php">  <button  class="btn btn-info" >Reporte</button></a></h1> -->
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <!-- /.box-header -->

                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoregistros">

                                <table id="tbllistadovencido" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>

                                    <th>Nombre Comercial</th>
                                    <th>Nombre Gennérico</th>
                                    <th>Categoria</th>

                                    <th>Fecha V</th>
                                    <th>Laboratorio</th>
                                    <th>Plazo V meses</th>
                                    <th>Stock</th>

                                    </thead>
                                    <tbody>
                                    <!----  La loquera de aqui quien lo llena es el dataTable-->
                                    </tbody>

                                    <tfoot>

                                    </tfoot>
                                </table>


                            </div>

                            <!--Fin centro -->
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </section><!-- /.content -->

        </div><!-- /.content-wrapper -->
        <!--Fin-Contenido-->
        <?php
    }//fin deel if de inicio de session que da los permisos

    else {
        require 'noacceso.php';
    }
    require 'footer.php';
    ?>
    <script type="text/javascript" src="../public/js/JsBarcode.all.min.js"></script>
    <script type="text/javascript" src="../public/js/jquery.PrintArea.js"></script>
    <script type="text/javascript" src="scripts/articulo.js"></script>
    <?php
}

//libera el espacio del BUFFER
ob_end_flush();
?>