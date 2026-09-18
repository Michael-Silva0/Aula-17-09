<header class="site-header">
	<nav class="site-nav" aria-label="Navegacao principal">
		<a class="brand" href="index.php">Roupas<span>.</span></a>
		<div class="nav-links">
			<a class="<?= $paginaAtual === 'index.php' ? 'active' : '' ?>" href="index.php">Inicio</a>
			<a class="<?= $paginaAtual === 'addCamisa.php' ? 'active' : '' ?>" href="addCamisa.php">Cadastrar</a>
			<a class="<?= $paginaAtual === 'listarCamisas.php' ? 'active' : '' ?>" href="listarCamisas.php">Catalogo</a>
		</div>
	</nav>
</header>
