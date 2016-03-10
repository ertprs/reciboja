<!DOCTYPE html>
<html>
    <head>
        <title><?= (isset($titulo) ? $titulo : 'Facileme Ajuda.') ?></title>
        <meta name="description" content=""/>
        <meta name="keywords" content=""/>
        <meta name="copyright" content=""/>
        <meta name="author" content=""/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="robots" content="INDEX, FOLLOW" />
            <script>
                var base_url = '<?= base_url() ?>';
                <?php if ($this->uri->segment(3) == 'sala'): ?>
                var cliente = {
                    id_cliente: <?php echo $id_cliente ?>,
                    nome: "<?php echo $nome ?>",
                    msg: "<?php echo $mensagem_padrao ?>",
                    id_atendimento: <?php echo $id_atendimento ?> 
                  }
                <?php endif; ?>
            </script>
            <link rel="stylesheet" href="<?= base_url() ?>assets/atendimento/css/chat.css" />
            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
            <?php if (isset($cssFiles)): ?>
                <?php foreach ($cssFiles as $css): ?>
                    <link rel="stylesheet" href="<?= (strstr($css, 'https://') ? $css : base_url() . 'assets/atendimento/css/' . $css) ?>" />
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (isset($jsFiles)): ?>
                <?php foreach ($jsFiles as $js): ?>
                    <script src="<?= (strpos($js, 'http') === 0 ? $js : base_url() . 'assets/atendimento/js/' . $js) ?>"></script>
                <?php endforeach; ?>
            <?php endif; ?>
    </head>
    <body>
        <div class="container">
