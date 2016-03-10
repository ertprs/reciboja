<div class="tab-pane in active" id="sala_<?php echo $id_atendimento ?>">
  <div class="widget-header">
    <h4 class="lighter smaller"><i class="icon-comment blue"></i> Atendimento</h4>
  </div>

  <div class="widget-body">
    <div class="widget-main no-padding">
	  <div class="content-message" style="max-height:300px; overflow:auto;">
        <div class="dialogs"></div>
      </div>
      <form id="enviarMsg" data-salaId="<?php echo $id_atendimento ?>" method="post">
        <div class="form-actions input-append">
          <input type="text" placeholder="Digite sua mensagem..." class="width-75" name="message" />
          <button class="btn btn-small btn-info no-radius" type="submit"><i class="icon-share-alt"></i> Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>
