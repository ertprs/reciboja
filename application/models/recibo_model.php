<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recibo_model extends MY_Model
{
  var $id_col = 'idRecibo';

    var $fields = array(
     'nomeEmissor' => array(
            'type' => 'text',
            'label' => 'Nome do Emissor',
            'class' => 'vObrigatorio',
            'rules' => 'required',
            'extra' => array('required' => 'required'),
        ),
        'enderecoEmissor' => array(
            'type' => 'text',
            'label' => 'Endereço do Emissor',
            'class' => 'vObrigatorio',
            'rules' => 'required',
            'extra' => array('required' => 'required'),
    ),
        'estadoEmissor' => array(
            'type' => 'text',
            'label' => 'Estado do Emissor',
            'class' => 'vObrigatorio',
            'rules' => 'required',
            'extra' => array('required' => 'required'),
    ),
         'cidadeEmissor' => array(
            'type' => 'text',
            'label' => 'Cidade do Emissor',
            'class' => 'vObrigatorio',
            'rules' => 'required',
            'extra' => array('required' => 'required'),
    ),
        'cepEmissor' => array(
            'type' => 'text',
            'label' => 'CEP do Emissor',
            'class' => 'vObrigatorio',
            'rules' => 'required',
            'extra' => array('required' => 'required'),
    ),
        'telefoneEmissor' => array(
            'type' => 'text',
            'label' => 'Telefone do Emissor',
            'class' => 'vObrigatorio',
            'rules' => 'required',
            'extra' => array('required' => 'required'),
            ),
        'cpfcnpfEmissor' => array(
            'type' => 'text',
            'label' => 'CPF/CNPJ do Emissor',
            'class' => 'vObrigatorio',
            'rules' => 'required',
            'extra' => array('required' => 'required'),
    ),
        'inscricaoEmissor' => array(
            'type' => 'text',
            'label' => 'Inscrição Estadual do Emissor',
            'class' => 'vObrigatorio',
            'rules' => 'required',
            'extra' => array('required' => 'required'),
    ),
        'emailEmissor' => array(
            'type' => 'text',
            'label' => 'Email do Emissor',
            'class' => 'vObrigatorio',
            'rules' => 'required',
            'extra' => array('required' => 'required'),
    ),
        'nomeCliente' => array(
            'type' => 'text',
            'label' => 'Nome do Cliente',
            'class' => 'vObrigatorio',
            'rules' => 'required',
            'extra' => array('required' => 'required'),
    ),
        'cpfcnpfCliente' => array(
            'type' => 'text',
            'label' => 'CPF/CNPJ do Cliente',
            'class' => 'vObrigatorio',
            'rules' => 'required',
            'extra' => array('required' => 'required'),
    ),
     'numeroRecibo' => array(
            'type' => 'text',
            'label' => 'Número do Recibo',
            'class' => 'vObrigatorio',
            'rules' => 'required',
            'extra' => array('required' => 'required'),
    ),
        'data' => array(
            'type' => 'text',
            'label' => 'Data',
            'class' => 'vObrigatorio',
            'rules' => 'required',
            'extra' => array('required' => 'required'),
    ),
        'valor' => array(
            'type' => 'text',
            'label' => 'Valor',
            'class' => 'vObrigatorio',
            'rules' => 'required',
            'extra' => array('required' => 'required'),
    ),
        'descricao' => array(
            'type' => 'text',
            'label' => 'Descrição',
            'class' => 'vObrigatorio',
            'rules' => 'required',
            'extra' => array('required' => 'required'),
    ),

    );
}
