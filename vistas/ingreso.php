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

    if ($_SESSION['compras'] == 1)//si es igual a uno el usuario tiene acceso a este contenido

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
                                <h1 class="box-title">Ingreso
                                    <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i
                                            class="fa fa-plus-circle"></i> Agregar
                                    </button>
                                </h1>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <table id="tbllistado"
                                       class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                    <th>Opciones</th>
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Usuario</th>
                                    <th>Documento</th>
                                    <th>Numero</th>
                                    <th>Total Compra</th>
                                    <th>Estado</th>

                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>

                                    </tfoot>
                                </table>

                                <br>
                                <br>
                                <br>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"">
                                <table id="detallesLista" class="table table-responsive table-bordered table-hover table-condensed table-striped">

                                    <thead style="background-color: #a1e0e8">


                                    <th>Cantidad</th>
                                    <th>Costo U</th>
                                    <th>% Venta</th>
                                    <th>% Venta U</th>
                                    <th>Precio de Venta</th>
                                    <th>Genérico</th>
                                    <th>Comercial</th>
                                    <th>Laboratorio</th>

                                    <th>Opciones</th>






                                    </thead>
                                    <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>



                                    </tfoot>

                                    <tbody>

                                    </tbody>
                                </table>
                            </div>


                            </div>


                            <div class="panel-body" style="height: 600px;" id="formularioregistros">
                                <form name="formulario" id="formulario" method="POST">


                               <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <label>Proveedor (*):</label>
                                        <input type="hidden" name="idingreso" id="idingreso">

                                        <select id="idproveedor" name="idproveedor" class="form-control selectpicker" title="Proveedor" data-live-search="true" required>

                                        </select>


                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Fecha:</label>
                                        <input type="date" class="form-control" name="fecha_hora" id="fecha_hora"
                                               required>
                                    </div>

                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Tipo Comprobante (*):</label>


                                        <select id="tipo_comprobante" name="tipo_comprobante" class="form-control selectpicker" data-live-search="true" required>

                                            <option value="Factura">Factura</option>
                                            <option value="Ticket">Ticket</option>
                                        </select>

                                    </div>

                                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <label>Serie (*)</label>
                                        <input type="text" class="form-control" name="serie_comprobante" id="serie_comprobante" maxlength="7" >

                                    </div>



                                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <label>Número (*)</label>
                                        <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" maxlength="10" placeholder="Número" required>

                                    </div>

                                   <!-- <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <label>Impuesto</label>
                                        <input type="number" class="form-control" name="impuesto" id="impuesto" maxlength="7" placeholder="Número">

                                    </div>-->


                                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                     <a data-toggle="modal" href="#myModal">
                                         <button id="btnAgregarArt" type="button" class="btn btn-instagram"><span class="fa fa-plus"></span>Articulos</button>
                                     </a>
                                    </div>






                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"">
                                    <table id="detalles" class="table table-responsive table-bordered table-hover table-condensed table-striped">

                                        <thead style="background-color: #a1e0e8">

                                        <th>Opciones</th>
                                        <th>Cantidad</th>
                                        <th>Costo U</th>
                                        <th>Genérico</th>
                                        <th>Comercial</th>
                                        <th>Categoria</th>


                                        <th>% Venta</th>

                                        <th>Precio de Venta</th>

                                        <th>Sub total</th>

                                        </thead>
                                        <tfoot>
                                        <th>Total</th>

                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>

                                        <th><h4 id="total">$ 0.00</h4><input type="hidden" name="total_compra" id="total_compra"></th>
                                        </tfoot>

                                        <tbody>

                                        </tbody>
                                    </table>
                                    </div>

                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="guardar">
                                        <button class="btn btn-primary" type="submit" id="btnGuardar"><i
                                                class="fa fa-save"></i> Guardar
                                        </button>

                                        <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i
                                                class="fa fa-arrow-circle-left"></i> Cancelar
                                        </button>
                                    </div>

                            <!--Fin de detalles-->


                                    


                                </form>
                            </div>
                            <!--Fin centro -->
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </section><!-- /.content -->

        </div><!-- /.content-wrapper -->
        <!--Fin-Contenido-->



        <!--Ventana modal -->


           <div class="modal fade in" id="myModal" style="size: 700px" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

             <div class="modal-dialog modal-lg" ><!--esta linea el modal-lg ajusta el tamaño correcto a la ventana -->
                 <div class="modal-content">

                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times</button>
                         <h4 class="modal-title">Seleccion un articulo</h4>
                     </div>

                     <div class="modal-body">

                         <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
                             <thead>
                             <th>Opciones</th>
                             <th>Nombre Comercial</th>
                             <th>Nombre Genérico</th>
                             <th>Laboratorio</th>
                             <th>Código</th>
                             <th>Stock</th>
                             <th>Imagen</th>
                             </thead>

                             <tbody>

                             </tbody>

                         </table>
                     </div>

                     <div class="modal-footer">
                         <button type="button" class="btn btn-default" data-dismiss="modal" >Cerrar</button>
                     </div>

                 </div>

             </div>

         </div>



         <!--Fin Ventana modal -->

        <?php

    }//fin deel if de inicio de session que da los permisos

    else {
        require 'noacceso.php';
    }
    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/ingreso.js"></script>

    <?php

}//Else End

//libera el espacio del BUFFER
ob_end_flush();
?>