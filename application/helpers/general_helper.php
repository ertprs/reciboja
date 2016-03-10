<?php
function formata_data($data){
  if (strstr($data, "/")){
    $A = explode ("/", $data);
    $V_data = $A[2] . "-". $A[1] . "-" . $A[0];
  }else{
    $A = explode ("-", $data);
    $V_data = $A[2] . "/". $A[1] . "/" . $A[0];	
  }
  return $V_data;
}

function formata_time($time, $separar=" "){
  $data = explode(" ", $time);
  if (strstr($data[0], "-")){
    $A = explode ("-", $data[0]);
    $V_data = $A[2] . "/". $A[1] . "/" . $A[0];	
  }else{
    $A = explode ("/", $data[0]);
    $V_data = $A[2] . "-". $A[1] . "-" . $A[0];	
  }
  if(count($data) < 2){
    $data[1] = "00:00:00";
  }
  return $V_data.$separar.$data[1];
}
