$(document).ready(function(){
  $('.desinstalar a').on('click', function(e){
    if(!confirm('Você deseja realmente desinstalar o Helpdesk de sua loja?')){
      e.preventDefault()
    }
  })
})
