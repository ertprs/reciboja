<?php

$this->load->view("admin/header");
?>
<div class="row-fluid">
    <table id="sample-table-2" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Login</th>
                <th>Cadastro ativo</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <? if(isset($operadores) && isset($operadores->num_rows) && $operadores->num_rows>0){ ?>
               <?php foreach($operadores->result_array() as $key => $value){ ?>
            <tr>
                <td>
                   <?php echo $value['nome'] ?>
                </td>
                <td>
                   <?php echo $value['email'] ?>
                </td>
                <td>
                   <?php echo $value['login'] ?>
                </td>
                <td>
                   <?php echo ($value['ativo'] == 1?"<span class='green'>Sim</span>":"<span class='red'>Não</span>") ?>
                </td>
                <td>
                   <?php echo ($value['status'] == "online"?"<span class='green'>Online</span>":"<span class='red'>Offline</span>") ?>
                </td>
                <td class="td-actions">
                    <div class="hidden-phone visible-desktop action-buttons">
                        <a class="green" href="<?php echo $action_edit."?id_operador=".$value['id_operador'] ?>" title="Editar <?php echo $value['nome'] ?>">
                            <i class="icon-pencil bigger-130"></i>
                        </a>

                        <a class="red" href="<?php echo $action_delete."?id_operador=".$value['id_operador'] ?>" title="Deletar <?php echo $value['nome'] ?>" onclick="return confirm('Deseja deletar este item?')">
                            <i class="icon-trash bigger-130"></i>
                        </a>
                    </div>
                </td>
            </tr>
            
               <?php } ?>            
            <? }else{ ?>
            <tr>
                <td class="center" colspan="5">
                    Nenhum operador cadastrado ainda
                </td>
            </tr>   
            
            <? } ?>
        </tbody>
    </table>
</div>
<?php

$this->load->view("admin/footer");
?>
