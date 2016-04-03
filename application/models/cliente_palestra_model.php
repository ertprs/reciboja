<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cliente_palestra_model extends My_Model{
  var $id_col = "id_cliente_palestra";
  var $fields= array(
					  "nome"=> array("type" => "text",
										"label" => "Nome",
										"class" => "vObrigatorio",
										"rules" => "max_length[150]",
										),
						"profissao"=> array("type" => "select",
										"label" => "Especialidade",
										"class"=> "vObrigatorio",
										"rules" => "required|max_length[255]",
                    "values" => array(
                      "TPD Ceramista" => "TPD Ceramista",
                      "TPD Resina Foto" => "TPD Resina Foto",
                      "TPD Prótese Total" => "TPD Prótese Total",
                      "Clínico geral" => "Clínico geral",
                      "Cirurgia e Traumatologia Buco Maxilo Faciais" => "Cirurgia e Traumatologia Buco Maxilo Faciais",
                      "Dentística" => "Dentística",
                      "Dentística restauradora" => "Dentística restauradora",
                      "Disfuncao Temporo-Mandibular e Dor-Orofacial" => "Disfuncao Temporo-Mandibular e Dor-Orofacial",
                      "Endodontia" => "Endodontia",
                      "Estomatologia" => "Estomatologia",
                      "Implantodontia" => "Implantodontia",
                      "Odontogeriatria" => "Odontogeriatria",
                      "Odontologia do trabalho" => "Odontologia do trabalho",
                      "Odontologia em saúde coletiva" => "Odontologia em saúde coletiva",
                      "Odontologia legal" => "Odontologia legal",
                      "Odontologia para pacientes com necessidades especiais" => "Odontologia para pacientes com necessidades especiais",
                      "Odontopediatria" => "Odontopediatria",
                      "Ortodontia" => "Ortodontia",
                      "Ortodontia e ortopedia facial" => "Ortodontia e ortopedia facial",
                      "Ortopedia funcional dos maxilares" => "Ortopedia funcional dos maxilares",
                      "Patologia bucal" => "Patologia bucal",
                      "Periodontia" => "Periodontia",
                      "Prótese Buco Maxilo Facial" => "Prótese Buco Maxilo Facial",
                      "Prótese Dentária" => "Prótese Dentária",
                      "Radiologia" => "Radiologia",
                      "Radiologia Odontológica e Imaginologia" => "Radiologia Odontológica e Imaginologia",
                      "Saúde coletiva" => "Saúde coletiva",
                      "Outra" => "Outra"
                    ),
						),
						"telefone"=> array("type" => "text",
										"label" => "Telefone",
										"class" => "vObrigatorio telefone",
										"rules" => "required",
										),
						"celular"=> array("type" => "text",
										"label" => "Celular",
										"class" => "vObrigatorio",
										"rules" => "required",
										),

				    "email"=> array("type" => "text",
										"label" => "Email",
										"class"=> "vObrigatorio vEmail",
										"rules" => "required|valid_email|callback_uniq_email",
						 				),
						"senha"=> array("type" => "password",
										"label" => "Senha",
										"class"=> "vObrigatorio",
										"rules" => "required",
										),
						 "id_palestra"=> array("type" => "select",
										"label" => "Palestra",
										"class"=> "",
										"rules" => "",
										"from" => array("model" => "palestras", "value" => "nome")
										),
					   );
  
  function __construct () {
    parent::My_Model();
  }

}
