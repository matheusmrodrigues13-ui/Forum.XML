<?php

const ARQUIVO_USUARIOS = __DIR__ . DIRECTORY_SEPARATOR . 'usuarios.xml';
const ARQUIVO_TOPICOS = __DIR__ . DIRECTORY_SEPARATOR . 'topicos.xml';

function carregarXml(string $arquivo, string $raiz): SimpleXMLElement
{
    $conteudo = is_file($arquivo) ? file_get_contents($arquivo) : false;

    if ($conteudo === false || trim($conteudo) === '') {
        return new SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8"?><' . $raiz . '/>'
        );
    }

    libxml_use_internal_errors(true);

    $xml = simplexml_load_string(
        $conteudo,
        SimpleXMLElement::class,
        LIBXML_NONET
    );

    libxml_clear_errors();

    if ($xml === false || $xml->getName() !== $raiz) {
        throw new RuntimeException(
            'O arquivo de dados está corrompido ou possui uma estrutura inválida.'
        );
    }

    return $xml;
}

function salvarXml(SimpleXMLElement $xml, string $arquivo): void
{
    $conteudo = $xml->asXML();

    if (
        $conteudo === false ||
        file_put_contents($arquivo, $conteudo, LOCK_EX) === false
    ) {
        throw new RuntimeException('Não foi possível salvar os dados.');
    }
}

function adicionarTextoXml(
    SimpleXMLElement $pai,
    string $nome,
    string $valor
): SimpleXMLElement {
    $filho = $pai->addChild($nome);
    $filho[0] = $valor;

    return $filho;
}

function escapar(mixed $valor): string
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

function obterIndice(mixed $valor): ?int
{
    if (!is_string($valor) && !is_int($valor)) {
        return null;
    }

    $indice = filter_var(
        $valor,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 0]]
    );

    return $indice === false ? null : $indice;
}

function tokenCsrf(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrfValido(mixed $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function encerrarComErro(
    string $mensagem,
    int $status = 400
): never {
    http_response_code($status);

    echo '<!doctype html>';
    echo '<html lang="pt-BR">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<title>Erro</title>';

    echo '<style>
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #121212;
            color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .erro {
            width: 450px;
            padding: 30px;
            background: #1c1c1c;
            border: 1px solid #333;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 15px 40px rgba(0,0,0,0.45);
        }

        a {
            color: #f0f0f0;
        }
    </style>';

    echo '</head>';
    echo '<body>';

    echo '<div class="erro">';
    echo '<p>' . escapar($mensagem) . '</p>';
    echo '<p><a href="lista.php">Voltar aos tópicos</a></p>';
    echo '</div>';

    echo '</body>';
    echo '</html>';

    exit;
}