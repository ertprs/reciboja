<?php
$this->load->view("admin/header");
?>
<form class="form-horizontal"  method="post" action="<?php echo (isset($action)?$action:"") ?>" />

<div class="control-group <?php echo (form_error('nome') != "" ? "error" : "") ?>">
    <input type="hidden" name="id_departamento" id="id_departamento" value="<?php echo set_value('id_departamento'); ?>"/>
    <label class="control-label" for="nome">Nome</label>
    <div class="controls">
        <input type="text" name="nome" id="nome" placeholder="nome do departamento" value="<?php echo set_value('nome'); ?>"/>
        <span class="help-inline"><?php echo form_error('nome'); ?></span>
    </div>
</div>

<div class="control-group <?php echo (form_error('mensagem_padrao') != "" ? "error" : "") ?>">
    <label class="control-label" for="mensagem_padrao">Mensagem padrão</label>
    <div class="controls">
        <span class="input-icon input-icon-right span6">
            <textarea  name="mensagem_padrao" class="limited span12" title="Ferrou" data-trigger="focus" data-maxlength="255" id="mensagem_padrao"><?php echo set_value('mensagem_padrao'); ?></textarea>
             <!--<i class="icon-remove-sign"></i>-->
        </span>

        <span class="help-inline"><?php echo form_error('mensagem_padrao'); ?></span><br>

        <span class="help-inline">Tags: <strong>{cliente_nome}</strong>, <strong>{cliente_email}</strong></span>

    </div>
</div>
<div class="control-group">
    <label class="control-label">Ativo</label>

    <div class="controls">
        <div class="row-fluid">
            <div class="span3">
                <label>
                    <input type="checkbox" name="ativo" value="1" <?php echo set_checkbox('ativo', 1); ?> class="ace-switch">
                    <span class="lbl"></span>
                </label>
            </div>
        </div>
    </div>
</div>
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
