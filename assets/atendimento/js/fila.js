function getStatus(){
  $.getJSON(base_url+'atendimento/chat/atendido', function(data){
    if(data.status == 'aberta'){
    	$('.fancybox-wrap').removeClass('container-open-call');
      	location.href = base_url+'atendimento/chat/sala/';
    }
    else{
      setTimeout(getStatus, 2000)
    }
  })
}
$(document).ready(function(){
  setTimeout(getStatus, 2000)
})
