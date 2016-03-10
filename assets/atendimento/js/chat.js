var tmplt_msg = [
  '<p class="text-message">',
    '<strong>${nome}</strong>',
    '<span>${message}</span>',
    '<span class="time">às ${hora}</div>',
  '</p>'
].join("")

var tmplt_log = [
  '<div>',
    '<p><i>(${tipo})</i> <strong>${nome}:</strong> ${message}</p>',
  '</div>'
].join("")

var historico = new Object()
historico[cliente.id_atendimento] = $.tmpl(tmplt_log, {tipo:'cliente', nome: cliente.nome, message: cliente.msg}).html()

var socket = io.connect('http://chat.facileme.com.br:8000')
socket.emit('setSocketConn', cliente)

socket.on('ready', function (data) {
  socket.emit('subscribe', cliente) 
});

socket.on('presence', function(data){
  if(data.state == 'offline'){
  	removeClient(data.client);
  }
});

socket.on('enviarmensagem', function(data){
  $.tmpl(tmplt_msg, {nome: data.client.nome, message: data.message, hora: data.hora}).appendTo('.dialogs')
  historico[cliente.id_atendimento] += $.tmpl(tmplt_log, {tipo:'operador', nome: data.client.nome, message: data.message}).html()
  $('.content-message').animate({scrollTop: $('.dialogs').height()}, 1000)
});
  
function removeClient(client){
  var date = new Date()
  var hora = date.getHours()
  var min = (date.getMinutes() < 10 ? "0" : '') + date.getMinutes()
  $.tmpl(tmplt_msg, {nome: client.nome, message: 'saiu da conversa', hora: hora+':'+min}).appendTo('.dialogs')
  historico[cliente.id_atendimento] += $.tmpl(tmplt_log, {tipo:'sistema', nome: client.nome, message: 'saiu da conversa'}).html()

  var post = {'id_atendimento': cliente.id_atendimento, 'historico': historico[cliente.id_atendimento]}
  socket.emit('gravarLog', post)

  $('.content-message').animate({scrollTop: $('.dialogs').height()}, 1000)
  $('textarea').attr('disabled', 'disabled')
}

$(document).ready(function(){
  $('[name=mensagem]').on('keydown', function(e){
    if (e.keyCode == 13) {
      $(this).parents('form').trigger('submit')
      e.preventDefault()
    }
  })
  $('.sends-message form').on('submit', function(e){
    e.preventDefault()
    var msg = $('[name=mensagem]').val()
    if(msg != ''){
    $('[name=mensagem]').val('')
      var date = new Date()
      var hora = date.getHours()
      var min = (date.getMinutes() < 10 ? "0" : "") + date.getMinutes() 
      $.tmpl(tmplt_msg, {nome: cliente.nome, message: msg, hora: hora+':'+min}).appendTo('.dialogs')

      historico[cliente.id_atendimento] += $.tmpl(tmplt_log, {tipo: 'cliente', nome: cliente.nome, message: msg}).html()

      socket.emit('enviarmensagem', { message: msg, room: cliente.id_atendimento})
      $('.content-message').animate({scrollTop: $('.dialogs').height()}, 1000)
    }

  })
})


$( document ).ready(function() {
	$('.container-open-call',parent.document.body).removeClass('container-open-call');
    $('.encerrar_chamado').on('click', function(e){
      e.preventDefault()
      parent.$.fancybox.close()
    })
});
