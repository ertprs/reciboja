<?php include_once(dirname(__FILE__).'/header.php'); ?>
<div class="span9">
  <img src="<?php echo base_url() ?>assets/admin/img/inicio.jpg" alt="Trazendo seu cliente pra perto de você" class="img-responsive" />
</div>
<div class="span3">
  <div class="widget-box">
    <div class="widget-header">
      <h5>Últimos atendimentos</h5>
    </div>
    <div class="widget-body">
    <?php if ($atendimentos): ?>

      <table class="table table-striped">
        <thead>
          <tr>
            <th>Data</th>
            <th>Departamento</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($atendimentos as $item): ?>
          <tr>
            <td><?php echo formata_time($item->inicio) ?></td>
            <td><?php echo $item->departamento ?></td>
            <td class="td-actions"><a href="<?php echo site_url('admin/relatorios/detalhar/'.$item->id_atendimento) ?>" class="green"><i class="icon-zoom-in bigger-130"></i></a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <p class="text-center"><a href="<?php echo site_url('admin/relatorios') ?>" class="btn btn-primary btn-small">Ver Mais</a></p>
    <?php else: ?>
      <p class="alert alert-info">Não foram realizados atendimentos ainda...</p>
    <?php endif; ?>
    </div>
  </div>
</div>
<?php include_once(dirname(__FILE__).'/footer.php'); ?>
