<?php
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/menu.php';
?>

<main class="container home">
    <section class="hero">
        <p class="eyebrow">Painel de catalogo</p>
        <h1>Gerencie suas roupas com clareza.</h1>
        <p class="hero-text">Cadastre, consulte e mantenha seu inventario organizado em um unico lugar.</p>
        <div class="actions">
            <a class="button button-primary" href="addCamisa.php">Cadastrar roupa</a>
            <a class="button button-secondary" href="listarCamisas.php">Ver catalogo</a>
        </div>
    </section>

    <section class="feature-grid" aria-label="Acoes principais">
        <a class="feature" href="addCamisa.php">
            <span class="feature-number">01</span>
            <h2>Novo cadastro</h2>
                <p>Adicione ano, estado, cor, foto, marca e tipo de roupa.</p>
        </a>
        <a class="feature" href="listarCamisas.php">
            <span class="feature-number">02</span>
            <h2>Catalogo</h2>
            <p>Consulte os registros cadastrados no banco de dados.</p>
        </a>
    </section>
</main>

</body>
</html>
