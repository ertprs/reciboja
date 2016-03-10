<?php

$this->load->view("admin/header");
?>
<div class="row-fluid">
    <table id="sample-table-2" class="table table-striped table-bordered table-hover">
        <tbody>
            <tr>
            	 <th>Data</th>
                <td>
                   <?php echo formata_time($detalhes->inicio);?> até <?php echo formata_time($detalhes->fim) ?>
                </td>
            </tr>
            <tr>
            	 <th>Cliente</th>
                <td>
                   <p><?php echo $detalhes->cliente_nome ?></p>
                   <p><?php echo $detalhes->cliente_email ?></p>
                   <p><?php echo $detalhes->cliente_telefone ?></p>
                </td>
            </tr>
            	 <th>Operador</th>
                <td>
                   <p><?php echo $detalhes->operador_nome ?></p>
                   <p><?php echo $detalhes->operador_email ?></p>
                   <p><?php echo $detalhes->operador_sexo ?></p>
                </td>
            <tr>
            	 <th>Departamento</th>
                 <td>
                   <?php echo $detalhes->departamento ?>
                </td>
            </tr>
        </tbody>
    </table>
  <div class="widget-box">
    <div class="widget-header">
      <h4 class="lighter smaller"><i class="icon-comment blue"></i> Historico</h4>
    </div>

    <div class="widget-body">
      <div class="widget-main no-padding">
        <div class="content-message" style="max-height:300px; overflow:auto;">
          <div class="dialogs">
            <?php echo $detalhes->historico ?>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
<?php
$this->load->view("admin/footer");
?>
