<?php include_once(dirname(__FILE__).'/../header.php'); ?>
<section class="span9">
  <section class="tabbable tabs-top">
    <ul class="nav nav-tabs atendimentos"></ul>
    <section class="tab-content">
      <div class="tab-pane in active">
        Bem Vindo ao seu painel de atendimento online, Clique nos clientes ao lado para iniciar um atendimento
      </div>
    </section>
  </section>
</section>
<aside class="span3 pull-right">
  <div class="widget-box light-border">
    <div class="widget-header header-color-dark">
      <h5 class="With Badge"><i class="icon-bell-alt"></i> Espera</h5>
      <div class="widget-toolbar"><span class="badge badge-important total_atendimentos">0</span></div>
    </div>
    <div class="widget-body">
      <div class="widget-main">
        <ul id="espera" class="nav nav-pills nav-stacked"></ul>
      </div>
    </div>
  </div>
</aside>
<?php include_once(dirname(__FILE__).'/../footer.php'); ?>
