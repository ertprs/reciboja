<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <title>HelpDesk - Facileme</title>

        <meta name="description" content="Common form elements and layouts" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <!--basic styles-->

        <link href="/assets/admin/css/bootstrap.min.css" rel="stylesheet" />
        <link href="/assets/admin/css/bootstrap-responsive.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="/assets/admin/css/font-awesome.min.css" />

        <!--[if IE 7]>
          <link rel="stylesheet" href="/assets/admin/css/font-awesome-ie7.min.css" />
        <![endif]-->

        <!--page specific plugin styles-->

        <link rel="stylesheet" href="/assets/admin/css/jquery-ui-1.10.3.custom.min.css" />
        <link rel="stylesheet" href="/assets/admin/css/chosen.css" />
        <link rel="stylesheet" href="/assets/admin/css/datepicker.css" />
        <link rel="stylesheet" href="/assets/admin/css/bootstrap-timepicker.css" />
        <link rel="stylesheet" href="/assets/admin/css/daterangepicker.css" />
        <link rel="stylesheet" href="/assets/admin/css/colorpicker.css" />

        <!--fonts-->

        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300" />

        <!--ace styles-->

        <link rel="stylesheet" href="/assets/admin/css/ace.min.css" />
        <link rel="stylesheet" href="/assets/admin/css/ace-responsive.min.css" />
        <link rel="stylesheet" href="/assets/admin/css/ace-skins.min.css" />

        <!--[if lte IE 8]>
          <link rel="stylesheet" href="/assets/admin/css/ace-ie.min.css" />
        <![endif]-->

        <!--[if !IE]>-->


		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script>var base_url = "<?php echo base_url() ?>";</script>

        <!--<![endif]-->

        <!--[if IE]>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <![endif]-->

        <!--[if !IE]>-->

        <script type="text/javascript">
            window.jQuery || document.write("<script src='assets/js/jquery-2.0.3.min.js'>" + "<" + "/script>");
        </script>

        <!--<![endif]-->

        <!--[if IE]>
        <script type="text/javascript">
        window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
        </script>
        <![endif]-->


        <?php if ($this->uri->segment(2) == 'chat'): ?>

        <script>
          var socket_url = '<?php echo SOCKET_URL ?>'; 
          var operador = {id_operador: <?php echo $this->session->userdata('operador')->id_operador ?>,
                          nome: '<?php echo $this->session->userdata('operador')->nome ?>'
                         };
        </script>
        <script src="<?php echo SOCKET_URL ?>/socket.io/socket.io.js"></script>
        <script src="<?php echo base_url() ?>assets/admin/js/jquery.tmpl.min.js"></script>
        <script src="<?php echo base_url() ?>assets/admin/js/jquery.slimscroll.min.js"></script>
        <script src="<?php echo base_url() ?>assets/admin/js/operador.js"></script>

        <?php endif; ?>
        <script src="<?php echo base_url() ?>assets/admin/js/utility.js"></script>

        <!--inline styles related to this page-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

    <body><div id="fb-root"></div>
       
        <div class="navbar">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a href="<?php echo site_url('admin') ?>" class="brand">
                        <small>
                            <i class="icon-gift"></i>
                            Helpdesk <strong>Facíleme</strong>
                        </small>
                    </a><!--/.brand-->

                    <ul class="nav ace-nav pull-right">
                        <li class="light-blue">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                <!--<img class="nav-user-photo" src="\\graph.facebook.com/<?php echo $this->session->userdata('operador')->fan_page ?>/picture?type=square" alt="Fan Page" />-->
                                <span class="user-info">
                                    <small>Olá,</small><?= $this->session->userdata('operador')->name ?>
                                </span>

                                <i class="icon-caret-down"></i>
                            </a>

                            <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
                                <li>
                                    <a href="<?php echo site_url('login/sair') ?>">
                                        <i class="icon-off"></i>
                                        Sair
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul><!--/.ace-nav-->
                </div><!--/.container-fluid-->
            </div><!--/.navbar-inner-->
        </div>

        <div class="main-container container-fluid">
            <a class="menu-toggler" id="menu-toggler" href="#">
                <span class="menu-text"></span>
            </a>

            <div class="sidebar" id="sidebar">
                <ul class="nav nav-list"> 
                    <li class="<?php echo isset($menu_chat) ? "active": "" ?>">
                        <a href="<?php echo site_url('admin/chat') ?>"><i class="icon-comments"></i> Entrar no Chat</a>
                    </li>

                    <li class="<?php echo isset($menu_departamentos) ? $menu_departamentos : "" ?>">

                        <a href="#" class="dropdown-toggle">
                            <i class="icon-group"></i>
                            <span class="menu-text">Departamentos</span>
                            <b class="arrow icon-angle-down"></b>
                        </a>
                        <ul class="submenu">
                            <li class="<?php echo isset($menu_departamentos_listar) ? $menu_departamentos_listar : "" ?>">

                                <a href="<?php echo site_url('admin/departamentos') ?>">
                                    <i class="icon-double-angle-right"></i>
                                    Listar
                                </a>
                            </li>
                            <li class="<?php echo isset($menu_departamentos_criar) ? $menu_departamentos_criar : "" ?>">

                                <a href="<?php echo site_url('admin/departamentos/criar') ?>">
                                    <i class="icon-double-angle-right"></i>
                                    Criar
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="<?php echo isset($menu_operadores) ? $menu_operadores : "" ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="icon-headphones"></i>
                            <span class="menu-text">Operadores</span>
                            <b class="arrow icon-angle-down"></b>
                        </a>
                        <ul class="submenu">
                            <li class="<?php echo isset($menu_operadores_listar) ? $menu_operadores_listar : "" ?>">
                                <a href="<?php echo site_url('admin/operadores') ?>">
                                    <i class="icon-double-angle-right"></i>
                                    Listar
                                </a>
                            </li>
                            <li class="<?php echo isset($menu_operadores_criar) ? $menu_operadores_criar : "" ?>">
                                <a href="<?php echo site_url('admin/operadores/criar') ?>">
                                    <i class="icon-double-angle-right"></i>
                                    Criar
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="<?php echo isset($menu_relatorios) ? "active" : "" ?>">
                        <a href="<?php echo site_url('admin/relatorios') ?>">
                            <i class="icon-dashboard"></i>
                            <span class="menu-text">Relatórios</span>
                        </a>
                    </li>
                    <li class="desinstalar">
                        <a href="<?php echo site_url('admin/desinstalar') ?>">
                            <i class="icon-warning-sign"></i>
                            <span class="menu-text">Desistalar Helpdesk</span>
                        </a>
                    </li>

                </ul><!--/.nav-list-->
            </div>

            <div class="main-content">
                <div class="page-content">
                    <div class="page-header position-relative">
                        <h1><?php echo (isset($titulo) ? $titulo : "") ?>
                            <small>
                                <i class="icon-double-angle-right"></i><?php echo (isset($sub_titulo) ? $sub_titulo : "") ?>
                            </small>
                        </h1>
                    </div><!--/.page-header-->

                    <div class="row-fluid">
                        <div class="span12">
                            <!--PAGE CONTENT BEGINS-->
