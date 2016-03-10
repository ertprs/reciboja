<?php if ($pendentes): ?>
  <?php foreach ($pendentes as $item): ?>
    <li><a href="<?php echo site_url('admin/chat/inicio/'.$item->id_atendimento_fila) ?>"> <?php echo $item->nome ?> <span class="label label-important arrowed"><?php echo $item->departamento ?></span></a></li>
  <?php endforeach; ?>
<?php else: ?>
  <div class="alert alert-info">Não Existem atendimentos pendentes até o momento.</div>
<?php endif; ?>
