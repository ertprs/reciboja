<?php
$this->load->view("admin/header");
?>
<form class="form-horizontal"  method="post" action="<?php echo (isset($action)?$action:"") ?>" />

    <input type="hidden" name="id_operador" id="id_operador" value="<?php echo set_value('id_operador'); ?>"/>
<div class="control-group <?php echo (form_error('nome') != ""?"error":"")?>">  
    <label class="control-label" for="nome">Nome</label>
    <div class="controls">
        <input type="text" name="nome" id="nome" placeholder="Nome do operador" value="<?php echo set_value('nome'); ?>"/>
        <span class="help-inline"><?php echo form_error('nome'); ?></span>    
    </div>
</div>
<div class="control-group <?php echo (form_error('email') != ""?"error":"")?>">
    <label class="control-label" for="email">Email</label>
    <div class="controls">
        <input type="text" name="email" id="email" value="<?php echo set_value('email'); ?>" placeholder="Email para notificação" />
        <span class="help-inline"><?php echo form_error('email'); ?></span>
    </div>
</div>
<hr>
<div class="control-group <?php echo (form_error('login') != ""?"error":"")?>">
    <label class="control-label" for="login">Login</label>
    <div class="controls">
        <input type="text" name="login" id="login" value="<?php echo set_value('login'); ?>" placeholder="Login para acesso do chat" />
        <span class="help-inline"><?php echo form_error('login'); ?></span>
    </div>
</div>
<div class="control-group <?php echo (form_error('senha') != ""?"error":"")?>">
    <label class="control-label" for="senha">Senha</label>
    <div class="controls">
        <input type="password" name="senha" id="senha" value="<?php echo set_value('senha'); ?>" placeholder="Senha de acesso do chat" />
        <span class="help-inline"><?php echo form_error('senha'); ?></span>
    </div>
</div>
<hr>
<div class="control-group">
    <label class="control-label" for="sexo">Sexo</label>
    <div class="controls">
        <label>
            <input type="radio" value="Masculino" <?php echo set_checkbox('sexo',"Masculino"); ?> name="sexo">
            <span class="lbl"> Masculino</span>
        </label>
        <label>
            <input type="radio" name="sexo" value="Feminino" <?php echo set_checkbox('sexo',"Feminino"); ?> >
            <span class="lbl"> Feminino</span>
        </label>
    </div>
</div>
<div class="control-group">
    <label class="control-label">Ativo</label>
    <div class="controls">
        <div class="row-fluid">
            <div class="span3">
                <label>
                    <input type="checkbox" name="ativo" value="1"<?php echo set_checkbox('ativo',1); ?> class="ace-switch">
                    <span class="lbl"></span>
                </label>
            </div>
        </div>
    </div>
</div>
<div class="control-group">
    <label class="control-label">Administrativo</label>
    <div class="controls">
        <div class="row-fluid">
            <div class="span3">
                <label>
                    <input type="checkbox" name="admin" value="1"<?php echo set_checkbox('admin',1); ?> class="ace-switch">
                    <span class="lbl"></span>
                </label>
            </div>
        </div>
    </div>
</div>
<h3 class="header smaller lighter blue">
    Departamentos Relacionados
    <small>Selecione o departamento e defina se o usuário é responsavel pelo mesmo</small>
</h3>

<div class="row-fluid">
    <div class="span12">
        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="center">
                        <label>
                            <span class="lbl"></span>
                        </label>
                    </th>
                    <th>Departamento</th>
                    <th>Sou responsavel</th>
                </tr>
            </thead>

            <tbody>
                <?php if(isset($lista_departamentos) && $lista_departamentos->num_rows > 0){
                        foreach($lista_departamentos->result_array() as $key => $value){  
                          
                ?>
                <tr>
                    <td class="center">
                        <label>
                            <input type="checkbox" name="id_departamento[<?php echo $value['id_departamento']; ?>]" <?php echo set_checkbox("id_departamento[". $value['id_departamento']."]", 1); ?> value="1" />
                            <span class="lbl"></span>
                        </label>
                    </td>
                    <td><?php echo $value['name']; ?></td>
                    <td><div class="row-fluid">
                            <div class="span1">
                                <label>
                                    <input name="departamento_principal[<?php echo $value['id_departamento']; ?>]" <?php echo set_checkbox("departamento_principal[". $value['id_departamento']."]", 1); ?> value="1" class="ace-switch" type="checkbox" />
                                    <span class="lbl"></span>
                                </label>
                            </div>
                        </div>
                    </td>
                </tr>
                        <?php } 
                }else{
                        ?>
                <tr>
                    <td class="center" colspan="3">
                       Nenhum departamento foi criado ainda
                    </td>
                </tr>
                <?php } ?>
                
                

            </tbody>
        </table>
    </div><!--/span-->
</div><!--/row-->

<div class="form-actions">
    <button type="submit" class="btn btn-info">
        <i class="icon-ok bigger-110"></i>
        Salvar
    </button>

    &nbsp; &nbsp; &nbsp;
    <button type="reset" class="btn">
        <i class="icon-undo bigger-110"></i>
        Restaurar
    </button>
</div>
</form>
<?php
$this->load->view("admin/footer");
?>
