# Catalogo de roupas

Aplicacao PHP para cadastro e consulta de roupas. O projeto usa PHP, MySQL e HTML/CSS, executado localmente pelo XAMPP.

Para uma explicacao detalhada da arquitetura, do fluxo de cadastro e das responsabilidades de cada arquivo, consulte [docs/sistema.md](docs/sistema.md).

## Requisitos

- XAMPP com Apache e MySQL ativos.
- PHP 8 ou superior.
- Extensao PDO MySQL habilitada.
- Banco de dados MySQL chamado `bd_roupas`.

## Como executar

1. Coloque o projeto em `C:\xampp\htdocs\Aula 17-09`.
2. Inicie Apache e MySQL no XAMPP.
3. Crie o banco `bd_roupas`.
4. Crie a tabela `tb_roupas` com as colunas indicadas abaixo.
5. Confira as credenciais em `config/connect.php`.
6. Acesse `http://localhost/Aula%2017-09/index.php`.

A configuracao atual usa o usuario local `root` sem senha. Em outros ambientes, altere os dados de conexao e nunca publique credenciais reais no repositorio.

## Estrutura do projeto

```text
Aula 17-09/
├── assets/
│   ├── css/style.css              # Estilos globais e layout responsivo
│   └── js/jquery-4.0.0.min.js     # Biblioteca JavaScript disponivel
├── config/
│   └── connect.php                # Configuracao da conexao PDO
├── includes/
│   ├── head.php                   # HTML inicial e carregamento do CSS
│   └── menu.php                   # Menu fixo compartilhado
├── src/Camisas/
│   ├── logger.php                 # Registro de erros do cadastro
│   ├── repositorio.php            # Operacoes de persistencia
│   ├── upload.php                 # Validacao e armazenamento de fotos
│   └── validacao.php              # Validacao dos dados do formulario
├── storage/uploads/               # Fotos enviadas pelos usuarios
├── logs/cadastro.log              # Log tecnico do cadastro
├── addCamisa.php                  # Formulario de cadastro
├── addCamisas.act.php             # Controlador do cadastro
├── alterarCamisa.php              # Tela reservada para alteracao
├── alterarCamisa.act.php          # Acao reservada para alteracao
├── excluirCamisa.php              # Tela reservada para exclusao
├── index.php                      # Pagina inicial
├── listarCamisas.php              # Catalogo de registros
└── README.md                      # Documentacao do projeto
```

## Colunas da tabela

O sistema usa exatamente estas colunas de dados:

- `ano_fabricacao`
- `estado_roupa`
- `cor`
- `foto`
- `marca`
- `tipo_camisas`

Exemplo de estrutura SQL:

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

## Fluxo de cadastro

1. `addCamisa.php` exibe o formulario.
2. O formulario envia dados e foto para `addCamisas.act.php`.
3. `src/Camisas/validacao.php` valida os campos e converte `ano_fabricacao` para inteiro.
4. `src/Camisas/upload.php` verifica a extensao e salva a imagem em `storage/uploads/`.
5. `config/connect.php` fornece os dados da conexao PDO.
6. `src/Camisas/repositorio.php` executa o `INSERT` com parametros nomeados.
7. O controlador grava uma mensagem na sessao e retorna para `addCamisa.php`.

Cada etapa tem uma responsabilidade propria para facilitar manutencao e testes.

## Navegacao

O menu fixo e carregado por `includes/menu.php` nas paginas principais:

- **Inicio:** `index.php`
- **Cadastrar:** `addCamisa.php`
- **Catalogo:** `listarCamisas.php`

O HTML inicial e o CSS comum sao carregados por `includes/head.php`.

## Estado atual

- Cadastro: implementado com validacao, upload e persistencia PDO.
- Listagem: implementada com consulta e exibicao dos registros.
- Alteracao: implementada por formulario, com foto opcional.
- Exclusao: implementada com tela de confirmacao e requisicao POST.
- Autenticacao de usuarios: nao implementada.
- Protecao CSRF: ainda nao implementada.

## Boas praticas para evolucao

- Manter valores recebidos do usuario fora de SQL concatenado.
- Validar extensao, tamanho e conteudo real das imagens antes do upload.
- Configurar credenciais por variaveis de ambiente em producao.
- Adicionar confirmacao e protecao CSRF antes de implementar alteracao e exclusao.
- Manter o menu e o cabecalho em `includes/` para evitar duplicacao entre paginas.
