<?php

declare(strict_types=1);

function inserirCamisa(PDO $pdo, string $tabela, array $dados, string $foto): void
{
    $sql = "INSERT INTO {$tabela} (ano_fabricacao, estado_roupa, cor, foto, marca, tipo_camisas)\n"
        . 'VALUES (:ano_fabricacao, :estado_roupa, :cor, :foto, :marca, :tipo_camisas)';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ano_fabricacao', $dados['ano_fabricacao'], PDO::PARAM_INT);
    $stmt->bindValue(':estado_roupa', $dados['estado_roupa']);
    $stmt->bindValue(':cor', $dados['cor']);
    $stmt->bindValue(':foto', $foto, PDO::PARAM_LOB);
    $stmt->bindValue(':marca', $dados['marca']);
    $stmt->bindValue(':tipo_camisas', $dados['tipo_camisas']);
    $stmt->execute();
}

function buscarCamisa(PDO $pdo, string $tabela, int $id): ?array
{
    $stmt = $pdo->prepare("SELECT id, ano_fabricacao, estado_roupa, cor, foto, marca, tipo_camisas FROM `{$tabela}` WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $camisa = $stmt->fetch();

    return $camisa === false ? null : $camisa;
}

function atualizarCamisa(PDO $pdo, string $tabela, int $id, array $dados, ?string $foto = null): void
{
    $campos = [
        'ano_fabricacao = :ano_fabricacao',
        'estado_roupa = :estado_roupa',
        'cor = :cor',
        'marca = :marca',
        'tipo_camisas = :tipo_camisas',
    ];

    if ($foto !== null) {
        $campos[] = 'foto = :foto';
    }

    $stmt = $pdo->prepare(
        "UPDATE `{$tabela}` SET " . implode(', ', $campos) . ' WHERE id = :id'
    );
    $stmt->bindValue(':ano_fabricacao', $dados['ano_fabricacao'], PDO::PARAM_INT);
    $stmt->bindValue(':estado_roupa', $dados['estado_roupa']);
    $stmt->bindValue(':cor', $dados['cor']);
    $stmt->bindValue(':marca', $dados['marca']);
    $stmt->bindValue(':tipo_camisas', $dados['tipo_camisas']);
    if ($foto !== null) {
        $stmt->bindValue(':foto', $foto, PDO::PARAM_LOB);
    }
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

function excluirCamisa(PDO $pdo, string $tabela, int $id): void
{
    $stmt = $pdo->prepare("DELETE FROM `{$tabela}` WHERE id = :id");
    $stmt->execute([':id' => $id]);
}
