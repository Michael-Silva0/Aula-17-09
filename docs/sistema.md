# Documentacao do sistema

## 1. Visao geral

O projeto e um catalogo de roupas desenvolvido em PHP procedural modularizado. Ele permite cadastrar roupas com ano de fabricacao, estado, cor, foto, marca e tipo. Os dados sao armazenados no MySQL e exibidos em uma pagina de catalogo.

A aplicacao roda no Apache do XAMPP e usa PDO para acesso ao banco.

## 2. Arquitetura

O sistema esta organizado em quatro partes principais:

```text
Navegacao e telas
        |
        v
Controlador HTTP: addCamisas.act.php
        |
        +--> Validacao: src/Camisas/validacao.php
        +--> Upload: src/Camisas/upload.php
        +--> Persistencia: src/Camisas/repositorio.php
        |
        v
Banco MySQL: bd_roupas.tb_roupas
```

### Camada de apresentacao

- `index.php`: pagina inicial e atalhos do sistema.
- `addCamisa.php`: formulario de cadastro.
- `listarCamisas.php`: consulta e exibe os registros.
- `alterarCamisa.php`: tela reservada para futura alteracao.
- `excluirCamisa.php`: tela reservada para futura exclusao.
- `includes/head.php`: estrutura inicial do HTML e carregamento do CSS.
- `includes/menu.php`: menu fixo compartilhado entre as paginas.
- `assets/css/style.css`: layout, formulario, catalogo e responsividade.

Todas as telas principais carregam `head.php` e `menu.php`. Isso evita duplicacao do cabecalho e garante que a navegacao seja consistente.

### Controlador

`addCamisas.act.php` recebe a requisicao do formulario e coordena o cadastro. Ele nao deve conter regras detalhadas de validacao, upload ou SQL.

A ordem do fluxo e:

1. Carregar configuracao e modulos.
2. Iniciar a sessao.
3. Validar os dados recebidos em `$_POST`.
4. Redirecionar de volta ao formulario se houver erros.
5. Salvar a foto enviada.
6. Abrir a conexao PDO.
7. Inserir o registro no banco.
8. Gravar uma mensagem de sucesso ou erro na sessao.
9. Redirecionar para `addCamisa.php`.

### Modulo de validacao

`src/Camisas/validacao.php` possui a funcao `validarDadosCamisa()`.

Responsabilidades:

- Verificar campos obrigatorios.
- Converter `ano_fabricacao` para inteiro.
- Limpar espacos nas strings.
- Retornar uma estrutura com `erros` e `dados` normalizados.

Campos esperados:

- `ano_fabricacao`
- `estado_roupa`
- `cor`
- `marca`
- `tipo_camisas`

### Modulo de upload

`src/Camisas/upload.php` possui a funcao `salvarFotoCamisa()`.

Responsabilidades:

- Confirmar que um arquivo foi enviado.
- Criar o diretorio de destino quando necessario.
- Permitir apenas imagens JPEG, PNG, WEBP e GIF, identificadas pelo MIME real.
- Aceitar JPEG com extensao `.jpg`, `.jpeg` ou `.jfif`.
- Gerar um nome aleatorio para evitar colisao.
- Mover a imagem para `storage/uploads/`.
- Retornar o caminho do arquivo salvo e o conteudo binario da imagem.

### Modulo de persistencia

`src/Camisas/repositorio.php` possui a funcao `inserirCamisa()`.

Responsabilidades:

- Receber uma conexao PDO.
- Montar o `INSERT` da tabela configurada.
- Usar parametros nomeados para evitar concatenacao de valores do usuario.
- Persistir as seis colunas do cadastro.

O modulo nao deve receber dados diretamente de `$_POST`. O controlador deve enviar dados ja validados.

## 3. Banco de dados

A conexao fica em `config/connect.php` e atualmente usa:

- Banco: `bd_roupas`
- Tabela: `tb_roupas`
- Usuario local: `root`
- Senha local: vazia
- Charset: `utf8mb4`

A tabela deve possuir estas colunas:

| Coluna | Tipo sugerido | Obrigatoria | Uso |
| --- | --- | --- | --- |
| `ano_fabricacao` | `SMALLINT` | Sim | Ano de fabricacao da roupa |
| `estado_roupa` | `VARCHAR(60)` | Sim | Estado de conservacao |
| `cor` | `VARCHAR(60)` | Sim | Cor predominante |
| `foto` | `BLOB` | Sim | Conteudo binario da imagem enviada |
| `marca` | `VARCHAR(120)` | Sim | Marca da roupa |
| `tipo_camisas` | `VARCHAR(120)` | Sim | Tipo ou categoria da roupa |

SQL de referencia:

```sql
CREATE DATABASE IF NOT EXISTS bd_roupas
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE bd_roupas;

CREATE TABLE IF NOT EXISTS tb_roupas (
    ano_fabricacao SMALLINT NOT NULL,
    estado_roupa VARCHAR(60) NOT NULL,
    cor VARCHAR(60) NOT NULL,
    foto BLOB NOT NULL,
    marca VARCHAR(120) NOT NULL,
    tipo_camisas VARCHAR(120) NOT NULL
);
```

## 4. Caminho de uma imagem

1. O usuario seleciona uma imagem no formulario.
2. O navegador envia o arquivo no campo `foto`.
3. `salvarFotoCamisa()` verifica o erro e a extensao.
4. O arquivo recebe um nome aleatorio.
5. A imagem e salva em `storage/uploads/`.
6. O conteudo binario e salvo na coluna `foto` como BLOB.
7. `listarCamisas.php` converte o BLOB para Base64 e renderiza a imagem com data URI.

O diretorio de uploads precisa ter permissao de escrita para o usuario do Apache.

## 5. Mensagens e redirecionamentos

As mensagens do cadastro sao armazenadas em `$_SESSION['mensagem']`. A pagina `addCamisa.php` le a mensagem depois do redirecionamento e a remove da sessao.

Esse padrao evita reenviar o formulario quando a pagina e atualizada e separa o processamento da exibicao.

Quando ocorre uma excecao durante o cadastro, `src/Camisas/logger.php` registra os detalhes tecnicos em `logs/cadastro.log`. O registro inclui data, mensagem, arquivo, linha e rota, mas nao inclui os valores enviados pelo usuario.

O usuario continua recebendo uma mensagem generica na tela. O arquivo possui uma regra `.htaccess` para impedir acesso direto pelo Apache. Se o arquivo nao puder ser escrito, o sistema usa o log padrao do PHP como alternativa.

## 6. Seguranca e manutencao

O projeto ja usa consultas PDO parametrizadas e escape de valores na listagem com `htmlspecialchars()`.

Antes de usar em producao, ainda e necessario:

- Mover credenciais para variaveis de ambiente.
- Validar o tamanho e o conteudo real das imagens.
- Adicionar protecao CSRF aos formularios.
- Implementar alteracao e exclusao com identificador seguro.
- Tratar arquivos enviados sem deixar uploads orfaos se o `INSERT` falhar.
- Adicionar uma chave primaria para identificar registros ao editar ou excluir.

## 7. Fluxos de alteracao e exclusao

As paginas `alterarCamisa.php`, `alterarCamisa.act.php`, `excluirCamisa.php` e `excluirCamisa.act.php` implementam a manutencao dos registros. O catalogo envia o `id` da roupa para a acao correspondente.

Na alteracao, a foto e opcional. Quando nenhuma nova imagem e enviada, o BLOB atual e preservado. Na exclusao, a tela pede confirmacao e o controlador aceita somente requisicoes POST.

Os fluxos reutilizam a mesma separacao:

- Tela para receber dados.
- Controlador para coordenar a requisicao.
- Validacao em modulo proprio.
- Operacao SQL parametrizada no repositorio.
- Redirecionamento com mensagem na sessao.
