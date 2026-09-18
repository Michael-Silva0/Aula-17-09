<?php

declare(strict_types=1);

require __DIR__ . '/config/connect.php';
require __DIR__ . '/src/Camisas/repositorio.php';
require __DIR__ . '/src/Camisas/logger.php';

session_start();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1) {
    $_SESSION['mensagem'] = '<p class="erro">Registro invalido para exclusao.</p>';
    header('Location: listarCamisas.php');
    exit;
}

try {
    $pdo = new PDO($dsn, $user, $senha, $options);
    if (buscarCamisa($pdo, $tabela, $id) === null) {
        throw new RuntimeException('Roupa nao encontrada para exclusao.');
    }

    excluirCamisa($pdo, $tabela, $id);
    $_SESSION['mensagem'] = '<p class="sucesso">Registro excluido.</p>';
} catch (Throwable $erro) {
    registrarErroCadastro($erro, __DIR__ . '/logs/cadastro.log');
    $_SESSION['mensagem'] = '<p class="erro">Erro ao excluir registro.</p>';
}

header('Location: listarCamisas.php');
exit;
