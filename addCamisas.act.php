<?php

declare(strict_types=1);

require __DIR__ . '/config/connect.php';
require __DIR__ . '/src/Camisas/validacao.php';
require __DIR__ . '/src/Camisas/upload.php';
require __DIR__ . '/src/Camisas/repositorio.php';
require __DIR__ . '/src/Camisas/logger.php';

session_start();

$resultado = validarDadosCamisa($_POST);

if ($resultado['erros'] !== []) {
    $_SESSION['mensagem'] = '<p class="erro">'
        . implode('</p><p class="erro">', $resultado['erros'])
        . '</p>';
    header('Location: addCamisa.php');
    exit;
}

try {
    $foto = salvarFotoCamisa(
        $_FILES['foto'] ?? [],
        __DIR__ . '/storage/uploads'
    );

    $pdo = new PDO($dsn, $user, $senha, $options);
    inserirCamisa($pdo, $tabela, $resultado['dados'], $foto['conteudo']);

    $_SESSION['mensagem'] = '<p class="sucesso">Registro criado.</p>';
} catch (Throwable $erro) {
    registrarErroCadastro($erro, __DIR__ . '/logs/cadastro.log');
    $_SESSION['mensagem'] = '<p class="erro">Erro ao criar registro.</p>';
}

header('Location: addCamisa.php');
exit;
