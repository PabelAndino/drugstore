

<?php

    if(strlen(session_id()) < 1)//si no existe una variable de session  y si esta iniciada no hay falla
    {
        session_start();
    }
//valida si la session esta iniciada
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ElsaSoft | </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
      <link rel="stylesheet" href="../public/bootstrap/css/_variables.scss">
      <link rel="stylesheet" href="../public/css/toastr.css">
      <link rel="stylesheet" href="../public/js/toastr.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../public/css/font-awesome.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../public/css/AdminLTE.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../public/css/_all-skins.min.css">
    <link rel="apple-touch-icon" href="../public/img/apple-touch-icon.png">
    <link rel="shortcut icon" href="../public/img/favicon.ico">

    <!-- DATATABLES -->
    <link rel="stylesheet" type="text/css" href="../public/datatables/jquery.dataTables.min.css">    
    <link href="../public/datatables/buttons.dataTables.min.css" rel="stylesheet"/>
    <link href="../public/datatables/responsive.dataTables.min.css" rel="stylesheet"/>

    <link rel="stylesheet" type="text/css" href="../public/css/bootstrap-select.min.css">

  </head>
  <body class="hold-transition skin-black-light sidebar-mini  ">  <!-- Colores o temas del sistema -->
    <div class="wrapper">

      <header class="main-header">

        <!-- Logo -->

          <a href="venta.php" class="logo">

              <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>E</b>S</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>ElsaSoft</b></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo $_SESSION['nombre']; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="img-circle" alt="User Image">
                    <p>
                      www.pabel.com - Software Development
                      <small>www.youtube.com/pabelwitt</small>
                    </p>
                  </li>
                  
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    
                    <div class="pull-right">
                      <a href="../ajax/usuario.php?op=salir" class="btn btn-default btn-flat">Cerrar</a>
                    </div>
                  </li>
                </ul>
              </li>
              
            </ul>
          </div>

        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar ">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar ">
          <!-- sidebar menu: : style can be found in sidebar.less -->


          <ul class="sidebar-menu ">
            <li class="header"></li>
            <?php

            if ($_SESSION['escritorio']==1)
            {
                echo '<li>
              <a href="dashboard.php">
                <i class="fa fa-columns"></i> <span>Dashboard</span>
              </a>
              
            </li>    ';
            }
            ?>
                <?php
              if ($_SESSION['almacen']==1)
              {
              echo '<li class="treeview">
              <a href="#">
                <i class="fa fa-laptop"></i>
                <span>Inventario</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="articulo.php"><i class="fa fa-circle-o"></i> Artículos</a></li>
                <li><a href="categoria.php"><i class="fa fa-circle-o"></i> Categoria</a></li>
                
              </ul>
            </li>';
              }
              ?>



              <?php
              if ($_SESSION['compras']==1)
              {
                  echo '<li class="treeview">
              <a href="#">
                <i class="fa fa-th"></i>
                <span>Ingreso</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="ingreso.php"><i class="fa fa-circle-o"></i> Ingresos</a></li>
                <li><a href="proveedor.php"><i class="fa fa-circle-o"></i> Laboratorio</a></li>
              </ul>
            </li>';
              }
              ?>

              <?php
              if ($_SESSION['ventas']==1)
              {
                  echo '<li class="treeview">
              <a href="#">
                <i class="fa fa-shopping-cart "></i>
                <span>Ventas</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="venta.php"><i class="fa fa-circle-o"></i> Ventas</a></li>
                <li><a href="cliente.php"><i class="fa fa-circle-o"></i> Clientes</a></li>
                 <li><a href="abonos.php"><i class="fa fa-circle-o"></i> Abonos</a></li>
              
               
              </ul>
            </li>   ';
              }
              ?>


              <?php
              if ($_SESSION['ventas']==1)
              {
                  echo '<li class="treeview">
              <a href="#">
                <i class="fa fa-sellsy "></i>
                <span>Pedido</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="pedido.php"><i class="fa fa-circle-o"></i> Pedidos</a></li>
                
              </ul>
            </li>   ';
              }
              ?>



             <!-- <?php
/*              if ($_SESSION['ventas']==1)
              {
                  echo '<li class="treeview">
              <a href="#">
                <i class="fa fa-file-excel-o "></i>
                <span>Proforma</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="proforma.php"><i class="fa fa-circle-o"></i> Proforma</a></li>
                
              </ul>
            </li>   ';
              }
              */?> -->






              <?php
              if ($_SESSION['acceso']==1)
              {
                  echo '<li class="treeview">
              <a href="#">
                <i class="fa fa-cog"></i> <span> Configuracion</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="usuario.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                <li><a href="permiso.php"><i class="fa fa-circle-o"></i> Permisos</a></li>
                
              </ul>
            </li>';
              }
              ?>


              <?php
              if ($_SESSION['consultac']==1)
              {
                  echo '<li class="treeview">
              <a href="#">
                <i class="fa fa-bar-chart"></i> <span>Consulta Compras</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="comprasfecha.php"><i class="fa fa-circle-o"></i> Consulta Compras</a></li>                
              </ul>
            </li>';
              }
              ?>


              <?php
              if ($_SESSION['consultav1']==1)
              {
                  echo '<li class="treeview">
              <a href="#">
                <i class="fa fa-balance-scale"></i> <span>Consultas</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="ventasfecha.php"><i class="fa fa-circle-o"></i> Consulta Ventas</a></li> 
                <!-- <li><a href="ventasfechacliente.php"><i class="fa fa-circle-o"></i> Consulta Ventas Cliente</a></li>    -->
                 <li><a href="articulosVencidos.php"><i class="fa fa-circle-o"></i> Artículos Vencimiento</a></li>
                <li><a href="articulosConsulta.php"><i class="fa fa-circle-o"></i> Consultar Artículos</a></li>             
              </ul>
            </li>
            <li>';
              }
              ?>



              <?php
              if ($_SESSION['consultav1']==1)
              {
                  echo '<li class="treeview">
              <a href="#">
               <i class="fa fa-info-circle"></i> <span>Respaldo DB</span>
                <small class="label pull-right bg-yellow">ES</small>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
               
                <li><a href="respaldar.php"><i class="fa fa-circle-o"></i> Respaldar Base de datos</a></li>             
              </ul>
            </li>
            <li>';
              }
              ?>



              <!--    <a href="#">
                    <i class="fa fa-plus-square"></i> <span>Ayuda</span>
                    <small class="label pull-right bg-red">PDF</small>
                  </a>
                </li>
                <li>-->

            </li>
                        
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
