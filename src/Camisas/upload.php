<?php

declare(strict_types=1);

function salvarFotoCamisa(array $arquivo, string $diretorio): array
{
    if (($arquivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Nao foi possivel enviar a foto.');
    }

    if (!is_dir($diretorio) && !mkdir($diretorio, 0755, true)) {
        throw new RuntimeException('Nao foi possivel preparar o diretorio de uploads.');
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = $finfo === false ? false : finfo_file($finfo, $arquivo['tmp_name']);
    if ($finfo !== false) {
        finfo_close($finfo);
    }

    $formatosPermitidos = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    if ($mime === false || !isset($formatosPermitidos[$mime])) {
        $extensaoRecebida = strtolower(pathinfo((string) ($arquivo['name'] ?? ''), PATHINFO_EXTENSION));
        throw new RuntimeException("Formato de imagem invalido: extensao .$extensaoRecebida, MIME $mime.");
    }

    $extensao = $formatosPermitidos[$mime];
    $nomeArquivo = bin2hex(random_bytes(16)) . '.' . $extensao;
    $caminhoAbsoluto = rtrim($diretorio, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $nomeArquivo;

    if (!move_uploaded_file($arquivo['tmp_name'], $caminhoAbsoluto)) {
        throw new RuntimeException('Nao foi possivel salvar a foto.');
    }

    $conteudo = file_get_contents($caminhoAbsoluto);
    if ($conteudo === false) {
        throw new RuntimeException('Nao foi possivel ler a foto salva.');
    }

    return [
        'caminho' => 'storage/uploads/' . $nomeArquivo,
        'conteudo' => $conteudo,
    ];
}
