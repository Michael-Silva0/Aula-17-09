<?php
session_start();
require __DIR__ . '/config/connect.php';
require __DIR__ . '/src/Camisas/repositorio.php';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/menu.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$pdo = new PDO($dsn, $user, $senha, $options);
$camisa = $id !== false && $id !== null ? buscarCamisa($pdo, $tabela, $id) : null;
$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']);
?>

<main class="container">
	<section class="page-heading">
		<p class="eyebrow">Manutencao</p>
		<h1>Alterar cadastro</h1>
		<p class="hero-text">Atualize os dados da roupa selecionada.</p>
	</section>

	<?= $mensagem ?>

	<?php if ($camisa === null): ?>
		<div class="empty-state">Roupa nao encontrada.</div>
	<?php else: ?>
		<form class="form-card" action="alterarCamisa.act.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="id" value="<?= (int) $camisa['id'] ?>">
			<div class="form-grid">
				<label>Ano de fabricacao<input type="number" name="ano_fabricacao" min="1900" max="2100" value="<?= (int) $camisa['ano_fabricacao'] ?>" required></label>
				<label>Estado da roupa<input type="text" name="estado_roupa" value="<?= htmlspecialchars($camisa['estado_roupa'], ENT_QUOTES, 'UTF-8') ?>" required></label>
				<label>Cor<input type="text" name="cor" value="<?= htmlspecialchars($camisa['cor'], ENT_QUOTES, 'UTF-8') ?>" required></label>
				<label>Marca<input type="text" name="marca" value="<?= htmlspecialchars($camisa['marca'], ENT_QUOTES, 'UTF-8') ?>" required></label>
				<label>Tipo de roupa<input type="text" name="tipo_camisas" value="<?= htmlspecialchars($camisa['tipo_camisas'], ENT_QUOTES, 'UTF-8') ?>" required></label>
				<label class="full-width">Nova foto<input type="file" name="foto" accept="image/jpeg,image/png,image/webp,image/gif,.jfif"></label>
			</div>
			<button class="button button-primary" type="submit">Salvar alteracoes</button>
		</form>
	<?php endif; ?>
</main>

</body>
</html>
