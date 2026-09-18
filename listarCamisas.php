<?php
session_start();
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/menu.php';
require __DIR__ . '/config/connect.php';

$pdo = new PDO($dsn, $user, $senha, $options);
$stmt = $pdo->query("SELECT id, ano_fabricacao, estado_roupa, cor, foto, marca, tipo_camisas FROM `{$tabela}` ORDER BY ano_fabricacao DESC");
$roupas = $stmt->fetchAll();
$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']);
?>

<main class="container">
	<section class="page-heading">
		<p class="eyebrow">Catalogo</p>
		<h1>Roupas cadastradas</h1>
		<p class="hero-text">Consulte os itens registrados no banco de dados.</p>
	</section>

	<?= $mensagem ?>

	<?php if ($roupas === []): ?>
		<div class="empty-state">Nenhuma roupa cadastrada ainda.</div>
	<?php else: ?>
		<div class="catalog-grid">
			<?php foreach ($roupas as $roupa): ?>
				<article class="catalog-item">
					<?php if (!empty($roupa['foto'])): ?>
						<img src="data:<?= htmlspecialchars((new finfo(FILEINFO_MIME_TYPE))->buffer($roupa['foto']), ENT_QUOTES, 'UTF-8') ?>;base64,<?= base64_encode($roupa['foto']) ?>" alt="<?= htmlspecialchars($roupa['tipo_camisas'], ENT_QUOTES, 'UTF-8') ?>">
					<?php endif; ?>
					<div class="catalog-item-content">
						<p class="eyebrow"><?= htmlspecialchars($roupa['marca'], ENT_QUOTES, 'UTF-8') ?></p>
						<h2><?= htmlspecialchars($roupa['tipo_camisas'], ENT_QUOTES, 'UTF-8') ?></h2>
						<p><?= htmlspecialchars($roupa['cor'], ENT_QUOTES, 'UTF-8') ?> · <?= htmlspecialchars($roupa['estado_roupa'], ENT_QUOTES, 'UTF-8') ?></p>
						<small><?= (int) $roupa['ano_fabricacao'] ?></small>
						<div class="card-actions">
							<a class="button button-secondary" href="alterarCamisa.php?id=<?= (int) $roupa['id'] ?>">Alterar</a>
							<a class="button button-danger" href="excluirCamisa.php?id=<?= (int) $roupa['id'] ?>">Excluir</a>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</main>

</body>
</html>
