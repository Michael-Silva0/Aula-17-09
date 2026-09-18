<?php

declare(strict_types=1);

function registrarErroCadastro(Throwable $erro, string $arquivoLog): void
{
    $diretorio = dirname($arquivoLog);
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0755, true);
    }

    $mensagem = sprintf(
        "[%s] tipo=\"%s\" erro=\"%s\" arquivo=\"%s\" linha=%d uri=\"%s\"%s",
        date('Y-m-d H:i:s'),
        $erro::class,
        str_replace('"', '\\"', $erro->getMessage()),
        $erro->getFile(),
        $erro->getLine(),
        $_SERVER['REQUEST_URI'] ?? '-',
        PHP_EOL
    );

    $gravado = file_put_contents($arquivoLog, $mensagem, FILE_APPEND | LOCK_EX);
    if ($gravado === false) {
        error_log($mensagem);
    }
}
