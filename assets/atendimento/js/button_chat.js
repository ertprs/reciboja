$(function(){
  $('.chamado-chat').fancybox({
    type: 'iframe',
    wrapCSS: 'container-open-call',
    width:500,
    height: '100%',
    autoSize: false,
    helpers   : { 
       overlay : {closeClick: false} 
    },
    beforeClose: function(){
      $.get('/atendimento/chat/cancelar')
    }
  });
});
