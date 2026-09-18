<?php
session_start();
require __DIR__ . '/config/connect.php';
require __DIR__ . '/src/Camisas/repositorio.php';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/menu.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$pdo = new PDO($dsn, $user, $senha, $options);
$camisa = $id !== false && $id !== null ? buscarCamisa($pdo, $tabela, $id) : null;
?>

<main class="container">
	<section class="page-heading">
		<p class="eyebrow">Manutencao</p>
		<h1>Excluir cadastro</h1>
		<p class="hero-text">Confirme a exclusao da roupa selecionada.</p>
	</section>

	<?php if ($camisa === null): ?>
		<div class="empty-state">Roupa nao encontrada.</div>
	<?php else: ?>
		<div class="form-card confirmation-card">
			<p>Voce esta prestes a excluir <strong><?= htmlspecialchars($camisa['tipo_camisas'], ENT_QUOTES, 'UTF-8') ?></strong> da marca <strong><?= htmlspecialchars($camisa['marca'], ENT_QUOTES, 'UTF-8') ?></strong>.</p>
			<form action="excluirCamisa.act.php" method="post" class="actions">
				<input type="hidden" name="id" value="<?= (int) $camisa['id'] ?>">
				<a class="button button-secondary" href="listarCamisas.php">Cancelar</a>
				<button class="button button-danger" type="submit">Confirmar exclusao</button>
			</form>
		</div>
	<?php endif; ?>
</main>

</body>
</html>
