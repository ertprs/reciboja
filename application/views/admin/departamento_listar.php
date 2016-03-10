<?php
$this->load->view("admin/header");
?>

<div class="row-fluid">
    <table id="sample-table-2" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>Departamento</th>
                <th>Mensagem padrão</th>
                <th>Ativo</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <? if(isset($departamentos) && isset($departamentos->num_rows) && $departamentos->num_rows>0){ ?>
               <?php foreach($departamentos->result_array() as $key => $value){ ?>
            <tr>
                <td>
                   <?php echo $value['nome'] ?>
                </td>
                <td>
                   <?php echo $value['mensagem_padrao'] ?>
                </td>
                <td>
                   <?php echo ($value['ativo'] == 1?"<span class='green'>Sim</span>":"<span class='red'>Não</span>") ?>
                </td>
                <td class="td-actions">
                    <div class="hidden-phone visible-desktop action-buttons">
                        <a class="green" href="<?php echo $action_edit.$value['id_departamento'] ?>" title="Editar <?php echo $value['nome'] ?>">
                            <i class="icon-pencil bigger-130"></i>
                        </a>

                        <a class="red" href="<?php echo $action_delete.$value['id_departamento'] ?>" title="Deletar <?php echo $value['nome'] ?>" onclick="return confirm('Deseja deletar este item?')">
                            <i class="icon-trash bigger-130"></i>
                        </a>
                    </div>
                </td>
            </tr>
            
               <?php } ?>            
            <? }else{ ?>            
            <tr>
                <td class="center" colspan="4">
                    Nenhum departamento localizado
                </td>
            </tr>
            <? } ?>
        </tbody>
    </table>
</div>

<?php
$this->load->view("admin/footer");
?>
