<?php

declare(strict_types=1);

function validarDadosCamisa(array $dados): array
{
    $erros = [];
    $camposObrigatorios = [
        'estado_roupa' => 'O campo estado da roupa e obrigatorio.',
        'cor' => 'O campo cor e obrigatorio.',
        'marca' => 'O campo marca e obrigatorio.',
        'tipo_camisas' => 'O campo tipo de roupa e obrigatorio.',
    ];

    foreach ($camposObrigatorios as $campo => $mensagem) {
        if (trim((string) ($dados[$campo] ?? '')) === '') {
            $erros[] = $mensagem;
        }
    }

    $anoFabricacao = filter_var($dados['ano_fabricacao'] ?? null, FILTER_VALIDATE_INT);
    if ($anoFabricacao === false) {
        $erros[] = 'O ano de fabricacao e obrigatorio.';
    }

    if ($erros !== []) {
        return ['erros' => $erros, 'dados' => []];
    }

    return [
        'erros' => [],
        'dados' => [
            'ano_fabricacao' => $anoFabricacao,
            'estado_roupa' => trim((string) $dados['estado_roupa']),
            'cor' => trim((string) $dados['cor']),
            'marca' => trim((string) $dados['marca']),
            'tipo_camisas' => trim((string) $dados['tipo_camisas']),
        ],
    ];
}
