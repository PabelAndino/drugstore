<?php

//se activa el almacenamiento el Buffer para iniciar sesion
ob_start();
session_start();

if(!isset($_SESSION["nombre"]))
{
    header("Location: login.html");
}
else {


    require 'header.php';

    if ($_SESSION['consultac'] == 1)

    {



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
                                <h1 class="box-title">Consulta de Compras por fecha

                                </h1>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <label>Fecha de Inicio</label>
                                <input class="form-control" type="date" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d")  ?>">

                                </div>

                                <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <label>Fecha de Fin</label>
                                    <input class="form-control" type="date" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d")  ?>">

                                </div>


                                <table id="tbllistado"
                                       class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Proveedor</th>
                                    <th>Comprobante</th>
                                    <th>Numero</th>
                                    <th>Total Compra</th>

                                    <th>Estado</th>



                                    </thead>
                                    <tbody>
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
    <script type="text/javascript" src="scripts/comprasfecha.js"></script>

    <?php

}//Else End

//libera el espacio del BUFFER
ob_end_flush();
?>