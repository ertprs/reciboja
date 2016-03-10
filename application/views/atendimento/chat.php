<?php include_once(dirname(__FILE__).'/header.php'); ?>
	<section class="content-chat">
		<div class="wrapper-message">
			<div class="content-message">
              <div class="dialogs">
				<p class="text-message">
					<strong><?php echo $nome ?></strong>
					<span><?php echo $mensagem_padrao ?> </span>
					<span class="time">às <?php echo $hora ?></span>
				</p>
              </div>
			</div>
            <footer id="footer_chat"></footer>
		</div>
		<div class="sends-message">
			<form action="" method="post">
				<div class="message">
					<img src="<?php echo $img ?>" width="40" height="40" alt="" />
					<textarea placeholder="Escreva o sua mensagem aqui" name="mensagem"></textarea>
				</div>
				<div class="button-sends-message">
					<button class="button btn-submit">Enviar</button>
				</div>
			</form>
		</div>
		<div class="close-call">
			<a class="button btn-close encerrar_chamado" href="#">Encerrar chamado</a>
		</div>
	</section>
<?php include_once(dirname(__FILE__).'/footer.php'); ?>
