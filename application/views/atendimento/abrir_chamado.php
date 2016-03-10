<?php include_once(dirname(__FILE__).'/header.php'); ?>
<?php if (isset($contato)): ?>
  <h1>Contato efetuado com sucesso</h1>
  <p>Obrigado por entrar em contato conosco, em breve responderemos sua dúvida.</p>
<?php else: ?>
  <?php if (isset($erro)): ?>
  <div class="alert alert-danger"><?php echo $erro ?></div>
  <?php endif; ?>
	<section class="content-open-call">
		<form action="<?php echo current_url() ?>" method="Post" name="frm-cad-cliente">
			<div class="form-control">
				<input type="text" name="nome" class="style-form" placeholder="Nome" required/>
			</div>
			<div class="form-control">
				<input type="email" name="email" class="style-form" placeholder="Email" required/>
			</div>
			<div class="form-control">
				<input type="tel" name="telefone" class="style-form" placeholder="Telefone" required/>
			</div>
			<div class="form-control">
				<select name="id_departamento" class="style-form" required>
				  <option value="">Departamento:</option>
					<?php foreach ($departamentos as $value): ?>
						<option value="<?=$value->id_departamento?>"><?=$value->nome?></option> 
					<?php endforeach ?>
				</select>
			</div>
			<div class="form-control">
				<textarea name="mensagem" rows="4" cols="48" class="style-form" placeholder='Qual sua dúvida?' required></textarea>
			</div>
			<div class="form-control">
				<input type="submit" value="Iniciar chat" class="button-init-chat" />
			</div>
		</form>
	</section>
<?php endif; ?>
<?php include_once(dirname(__FILE__).'/footer.php'); ?>
