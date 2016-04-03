<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Palestras_model extends My_Model{
  var $id_col = "id_palestra";
  var $fields= array(
					  "nome"=> array("type" => "text",
										"label" => "Nome",
										"class" => "vObrigatorio",
										"rules" => "max_length[150]",
										),
						"logo"=> array("type" => "text",
										"label" => "Logo",
										"class" => "vObrigatorio",
										"rules" => "max_length[150]",
										),
						"mediasite"=> array("type" => "text",
										"label" => "Link Mediasite",
										"class"=> "vObrigatorio",
										"rules" => "required|max_length[255]",
										),
						"inicio"=> array("type" => "text",
										"label" => "Inicio",
										"class"=> "vObrigatorio data",
										"rules" => "required",
										),
						"fim"=> array("type" => "text",
										"label" => "Fim",
										"class"=> "vObrigatorio data",
										"rules" => "required",
										),
						"permalink"=> array("type" => "text",
										"label" => "Link Permanente",
										"class"=> "vObrigatorio",
										"rules" => "required",
										),
						"tipo"=> array("type" => "select",
										"label" => "Tipo de Palestra",
										"class"=> "vObrigatorio",
										"values" => array("Requer Senha" => "Requer Senha", "Palestra Aberta" => "Palestra Aberta"),
										"rules" => "required",
										),
						"certificado"=> array("type" => "text",
										"label" => "Certificado",
										"class"=> "",
										"rules" => "",
										),
						"email_palestrante"=> array("type" => "text",
										"label" => "Email do Palestrante",
										"class"=> "",
										"rules" => "",
										),
						"conteudo"=> array("type" => "textarea",
										"label" => "Conteúdo Programático",
										"class"=> "vObrigatorio",
										"rules" => "required",
										),
						"mini_curriculo"=> array("type" => "textarea",
										"label" => "Mini Currículo",
										"class"=> "vObrigatorio",
										"rules" => "required",
										),
						"extra"=> array("type" => "textarea",
										"label" => "Extra",
										"class"=> "",
										"rules" => "",
										),
						"agradecimento"=> array("type" => "textarea",
										"label" => "Cadastro concluido",
										"class"=> "vObrigatorio",
										"rules" => "required",
										),
						"msg_email"=> array("type" => "textarea",
										"label" => "Mensagem do Email",
										"class"=> "vObrigatorio",
										"rules" => "required",
										"value" => "Caro(a) %NOME%, <br /> 
Você se inscreveu na palestra %TITULO% que será realizada no dia %INICIO% até %FIM% ."
										),
					   );
  
  function __construct () {
    parent::My_Model();
  }
  
  function _filter_pre_save(&$data){
		if(array_key_exists('inicio',$data)){
			$d = formatar_time($data['inicio']);
			$data['inicio'] = $d;
		}
		
		if(array_key_exists('fim',$data)){
			$d = formatar_time($data['fim']);
			$data['fim'] = $d;
		}
	}

}