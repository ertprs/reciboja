<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Boleto Já</title>

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


        <!--[if lte IE 8]>
          <link rel="stylesheet" href="/assets/admin/css/ace-ie.min.css" />
        <![endif]-->

        <!--inline styles related to this page-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
    <!--[if !IE]>-->



    <!--<![endif]-->

    <!--[if IE]>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <![endif]-->

    <!--[if !IE]>-->

    <body class="login-layout">
<?php echo 'erro';  exit();?>
  <?php if (isset($erro)): ?>
  <div class="alert alert-danger"><?php echo $erro ?></div>
  <?php endif; ?>
	<section class="content-open-call">
		<form action="<?php echo current_url() ?>" method="Post" name="frm-cad-cliente">
		<?php echo 'teste';?>
		</form>
	</section>
<?php endif; ?>
</body>
</html>

