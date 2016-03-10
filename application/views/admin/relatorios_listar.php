<?php $this->load->view("admin/header"); ?>
<form class="form-inline"  method="post" action="<?php echo (isset($action)?$action:"") ?>">
    <div class="row-fluid">
        <div class="widget-box">
            <div class="widget-header widget-header-small">
                <h5 class="lighter">Busca</h5>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <fieldset>
                        <div class="input-prepend">
                            <input type="text" name="inicio" class="input-small date-picker" id="inicial" placeholder="Data Inicial" data-date-format="dd-mm-yyyy" value="<?php echo $this->input->post('inicio') ?>" />
                            <span class="add-on"><i class="icon-calendar"></i></span>
                        </div>
                        <div class="input-prepend">
                            <input type="text" name="final" class="input-small date-picker" id="final" placeholder="Data Final" data-date-format="dd-mm-yyyy" value="<?php echo $this->input->post('final') ?>" />
                            <span class="add-on"><i class="icon-calendar"></i></span>
                        </div>
                        <input type="text" name="cliente" class="input-medium" value="<?php echo $this->input->post('cliente') ?>" placeholder="Nome do Cliente" />
                        <select name="operador">
                            <option value="">Selecione Um Operador</option>
                            <?php foreach($operadores as $operador):?>
                            <option value="<?=$operador->id_operador?>" <?php echo ($this->input->post('operador') == $operador->id_operador ? 'selected' : '') ?>><?=$operador->nome?></option>
                            <?php endforeach;?>
                        </select>
                        <select name="departamento">
                            <option value="">Selecione Um Departamento</option>
                            <?php foreach($departamentos as $departamento):?>
                            <option value="<?=$departamento->id_departamento?>" <?php echo ($this->input->post('departamento') == $departamento->id_departamento ? 'selected' : '') ?>><?=$departamento->nome?></option>
                            <?php endforeach;?>
                        </select>
                        <button type="submit" class="btn btn-purple btn-small">
                            Buscar
                            <i class="icon-search icon-on-right bigger-110"></i>
                        </button>
                         <!--<button type="submit" class="btn btn-success btn-small">
                            Limpar
                        </button>-->
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="space-6"></div>
<div class="row-fluid">
    <table id="sample-table-2" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>Data</th>
                <th>Cliente</th>
                <th>Operador</th>
                <th>Departamento</th>
                <th>Status</th>
                <th></th>

            </tr>
        </thead>

        <tbody>
            <? if(isset($relatorios) && isset($relatorios->num_rows) && $relatorios->num_rows>0){ ?>
               <?php foreach($relatorios->result_array() as $key => $value){ ?>
            <tr>
                <td>
                   <?php $data_explode = explode(" ", $value['inicio']);echo formata_data($data_explode[0]);?>
                </td>
                <td>
                   <?php echo $value['cliente'] ?>
                </td>
                <td>
                   <?php echo $value['operador'] ?>
                </td>
                 <td>
                   <?php echo $value['departamento'] ?>
                </td>
                <td>
                   <?php echo $value['status'] ?>
                </td>
                <td class="td-actions">
                    <div class="hidden-phone visible-desktop action-buttons">
                        <a class="green" href="<?php echo $action_detalhe."".$value['id_atendimento'] ?>" title="Detalhar <?php echo $value['id_atendimento'] ?>">
                            <i class="icon-zoom-in bigger-130"></i>
                        </a>
                    </div>
            </tr>
            
               <?php } ?>            
            <? }else{ ?>            
            <tr>
                <td class="center" colspan="6">
                    Nenhum atendimento localizado
                </td>
            </tr>
            <? } ?>
        </tbody>
    </table>
</div>
<div class="space-6"></div>
<div class="row-fluid">
<?=$paginacao?>
</div>
<?php $this->load->view("admin/footer"); ?>
