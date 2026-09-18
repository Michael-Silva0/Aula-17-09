<?php

declare(strict_types=1);

require __DIR__ . '/config/connect.php';
require __DIR__ . '/src/Camisas/validacao.php';
require __DIR__ . '/src/Camisas/upload.php';
require __DIR__ . '/src/Camisas/repositorio.php';
require __DIR__ . '/src/Camisas/logger.php';

session_start();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$resultado = validarDadosCamisa($_POST);

if ($id === false || $id === null || $id < 1 || $resultado['erros'] !== []) {
	$_SESSION['mensagem'] = '<p class="erro">Dados invalidos para alteracao.</p>';
	header('Location: listarCamisas.php');
	exit;
}

try {
	$pdo = new PDO($dsn, $user, $senha, $options);
	$camisa = buscarCamisa($pdo, $tabela, $id);
	if ($camisa === null) {
		throw new RuntimeException('Roupa nao encontrada para alteracao.');
	}

	$foto = null;
	if (isset($_FILES['foto']) && ($_FILES['foto']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
		$foto = salvarFotoCamisa($_FILES['foto'], __DIR__ . '/storage/uploads');
		$foto = $foto['conteudo'];
	}

	atualizarCamisa($pdo, $tabela, $id, $resultado['dados'], $foto);
	$_SESSION['mensagem'] = '<p class="sucesso">Registro alterado.</p>';
} catch (Throwable $erro) {
	registrarErroCadastro($erro, __DIR__ . '/logs/cadastro.log');
	$_SESSION['mensagem'] = '<p class="erro">Erro ao alterar registro.</p>';
}

header('Location: listarCamisas.php');
exit;
