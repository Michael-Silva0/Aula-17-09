<?php
session_start();
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/menu.php';

$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']);
?>

<main class="container">
	<section class="page-heading">
		<p class="eyebrow">Novo registro</p>
		<h1>Cadastre uma roupa</h1>
		<p class="hero-text">Preencha os dados abaixo para adicionar um item ao catalogo.</p>
	</section>

	<?= $mensagem ?>

	<form class="form-card" action="addCamisas.act.php" method="post" enctype="multipart/form-data">
		<div class="form-grid">
			<label>Ano de fabricacao<input type="number" name="ano_fabricacao" min="1900" max="2100" required></label>
			<label>Estado da roupa<input type="text" name="estado_roupa" required></label>
			<label>Cor<input type="text" name="cor" required></label>
			<label>Marca<input type="text" name="marca" required></label>
			<label>Tipo de roupa<input type="text" name="tipo_camisas" required></label>
			<label class="full-width">Foto<input type="file" name="foto" accept="image/jpeg,image/png,image/webp,image/gif,.jfif" required></label>
		</div>
		<button class="button button-primary" type="submit">Salvar cadastro</button>
	</form>
</main>

</body>
</html>
