<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Helpdesk - Facileme</title>

        <meta name="description" content="User login page" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <!--basic styles-->

        <link href="<?php echo base_url() ?>assets/admin/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?php echo base_url() ?>assets/admin/css/bootstrap-responsive.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/font-awesome.min.css" />

        <!--[if IE 7]>
          <link rel="stylesheet" href="/assets/admin/css/font-awesome-ie7.min.css" />
        <![endif]-->

        <!--page specific plugin styles-->

        <!--fonts-->

        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,300" />

        <!--ace styles-->

        <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/ace.min.css" />
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/ace-responsive.min.css" />
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/ace-skins.min.css" />

        <!--[if lte IE 8]>
          <link rel="stylesheet" href="/assets/admin/css/ace-ie.min.css" />
        <![endif]-->

        <!--inline styles related to this page-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
    <!--[if !IE]>-->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

    <!--<![endif]-->

    <!--[if IE]>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <![endif]-->

    <!--[if !IE]>-->

    <script type="text/javascript">
        window.jQuery || document.write("<script src='<?php echo base_url() ?>assets/admin/js/jquery-2.0.3.min.js'>" + "<" + "/script>");
    </script>
    <body class="login-layout">
        <script>

            var base_url = '<?php echo base_url() ?>';

            window.fbAsyncInit = function() {
                FB.init({
                    appId: '305990076116431',
                    status: true,
                    xfbml: true
                });
            };

            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {
                    return;
                }
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/pt_BR/all.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));


            $(document).ready(function() {
                $('.fb_login').on('click', function(e) {
                    e.preventDefault()
                    $('.fb_login, .submit').hide()
                    $('.submit').hide().after('<i class="icon-spinner icon-spin orange bigger-200 loading pull-right"></i>')
                    FB.login(function(response) {
                        if (response.authResponse) {
                            $.post(base_url + 'login/fb', response.authResponse, function(data) {
                                $('.loading').remove()
                                if (data.status == 'sucesso') {
                                        location.href = data.redirect                                    
                                } else {
                                    alert(data.erro)
                                    $('.fb_login, .submit').show()
                                }
                            }, "json")

                        }
                    }, {'scope': 'email'})
                })
            })


        </script>
        <div class="main-container container-fluid">
            <div class="main-content">
                <div class="row-fluid">
                    <div class="span12">
                        <div class="login-container">
                            <div class="row-fluid">
                                <div class="center">
                                    <h1>
                                        <span class="red">Helpdesk</span>
                                        <span class="white">Facíleme</span>
                                    </h1>
                                    <h4 class="blue">&copy; Facíleme Social Commerce</h4>
                                </div>
                            </div>

                            <div class="space-6"></div>

                            <div class="row-fluid">
                                <div class="position-relative">
                                    <div id="login-box" class="login-box visible widget-box no-border">
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <h4 class="header blue lighter bigger">
                                                    <i class="icon-coffee green"></i>
                                                    Login de acesso
                                                </h4>

                                                <div class="space-6"></div>

                                                <form method="post" action="/login">
                                                    <fieldset>
                                                        <label>
                                                            <span class="block input-icon input-icon-right">
                                                                <input type="text" class="span12" name="login" value="<?php echo set_value('login'); ?>" placeholder="Login de acesso" />
                                                                <i class="icon-user"></i>
                                                            </span>
                                                        </label>

                                                        <label>
                                                            <span class="block input-icon input-icon-right">
                                                                <input type="password" class="span12" name="senha" placeholder="Senha" />
                                                                <i class="icon-lock"></i>
                                                            </span>
                                                        </label>

                                                        <p class="red"><?php echo (isset($erro) ? $erro : "") ?></p>
                                                        <!--<div class="space"></div>-->

                                                        <div class="clearfix">
                                                            <button type="submit" class="width-35 pull-right btn btn-small btn-primary">
                                                                <i class="icon-key"></i>
                                                                Entrar
                                                            </button>
                                                        </div>
                                                        <div class="space-4"></div>
                                                    </fieldset>
                                                </form>

                                            </div><!--/widget-main-->

                                            <div class="toolbar clearfix">
                                                <div>
                                                    <a href="#" onclick="show_box('forgot-box');
                        return false;" class="forgot-password-link">
                                                        <i class="icon-arrow-left"></i>
                                                        Esqueci minha senha
                                                    </a>
                                                </div>                                               
                                            </div>
                                        </div><!--/widget-body-->
                                    </div><!--/login-box-->

                                    <div id="forgot-box" class="forgot-box widget-box no-border">
                                        <div class="widget-body">
                                            <form method="post" action="/login/esqueci_senha">
                                                <div class="widget-main">
                                                    <h4 class="header red lighter bigger">
                                                        <i class="icon-key"></i>
                                                        Lembrar senha
                                                    </h4>

                                                    <div class="space-6"></div>
                                                    <p>
                                                        Coloque no campo abaixo o seu Login cadastrado
                                                    </p>

                                                    <fieldset>
                                                        <label>
                                                            <span class="block input-icon input-icon-right">
                                                                <input type="text" class="span12" name="login" placeholder="Login" />
                                                                <i class="icon-envelope"></i>
                                                            </span>
                                                        </label>

                                                        <div class="clearfix">
                                                            <button type="submit" class="width-35 pull-right btn btn-small btn-danger">
                                                                <i class="icon-lightbulb"></i>
                                                                Enviar
                                                            </button>
                                                        </div>
                                                    </fieldset>
                                                </div><!--/widget-main-->
                                                <div class="toolbar center">
                                                    <a href="#" onclick="show_box('login-box');
                return false;" class="back-to-login-link">
                                                        Voltar ao login
                                                        <i class="icon-arrow-right"></i>
                                                    </a>

                                                </div>
                                            </form>
                                        </div><!--/widget-body-->
                                    </div><!--/forgot-box-->
                                </div><!--/position-relative-->
                            </div>
                        </div>
                    </div><!--/.span-->
                </div><!--/.row-fluid-->
            </div>
        </div><!--/.main-container-->

        <!--basic scripts-->



        <!--<![endif]-->

        <!--[if IE]>
<script type="text/javascript">
window.jQuery || document.write("<script src='<?php echo base_url() ?>assets/admin/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

        <script type="text/javascript">
            if ("ontouchend" in document)
                document.write("<script src='<?php echo base_url() ?>assets/admin/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
        </script>
        <script src="<?php echo base_url() ?>assets/admin/js/bootstrap.min.js"></script>

        <!--page specific plugin scripts-->

        <!--ace scripts-->

        <script src="<?php echo base_url() ?>assets/admin/js/ace-elements.min.js"></script>
        <script src="<?php echo base_url() ?>assets/admin/js/ace.min.js"></script>

        <!--inline scripts related to this page-->

        <script type="text/javascript">
            function show_box(id) {
                $('.widget-box.visible').removeClass('visible');
                $('#' + id).addClass('visible');
            }
        </script>
    </body>
</html>
