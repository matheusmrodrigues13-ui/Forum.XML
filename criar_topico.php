<?php

session_start();

require_once __DIR__ . '/funcoes.php';

if (!isset($_SESSION['usuario'])) {

    echo "Você precisa estar logado para criar um tópico.";
    exit;
}

$sucesso = false;
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = trim(
        (string) ($_POST['titulo'] ?? '')
    );

    $mensagem = trim(
        (string) ($_POST['mensagem'] ?? '')
    );

    if ($titulo === '' || $mensagem === '') {

        $erro = 'Preencha o título e a mensagem.';

    } else {

        try {

            $topicos = carregarXml(
                ARQUIVO_TOPICOS,
                'topicos'
            );

            $novo = $topicos->addChild('topico');

            adicionarTextoXml(
                $novo,
                'autor',
                $_SESSION['usuario']
            );

            adicionarTextoXml(
                $novo,
                'titulo',
                $titulo
            );

            adicionarTextoXml(
                $novo,
                'mensagem',
                $mensagem
            );

            $novo->addChild('comentarios');

            salvarXml(
                $topicos,
                ARQUIVO_TOPICOS
            );

            $sucesso = true;

        } catch (RuntimeException $excecao) {

            $erro = $excecao->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Criar Tópico</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #121212;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 500px;
            padding: 40px;
            background: #1c1c1c;
            border: 1px solid #333;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.45);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #f0f0f0;
        }

        label {
            display: block;
            color: #d8d8d8;
            margin-bottom: 8px;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px 13px;
            margin-bottom: 20px;
            background: #252525;
            color: white;
            border: 1px solid #444;
            border-radius: 8px;
            outline: none;
        }

        input {
            height: 42px;
        }

        textarea {
            height: 150px;
            resize: vertical;
        }

        input:focus,
        textarea:focus {
            border-color: #777;
            background: #292929;
        }

        button {
            width: 100%;
            height: 44px;
            border: none;
            border-radius: 8px;
            background: #f0f0f0;
            color: #111;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #d6d6d6;
        }

        .erro {
            margin-bottom: 20px;
            padding: 12px;
            background: #252525;
            border: 1px solid #444;
            border-radius: 8px;
            text-align: center;
        }

        .sucesso {
            text-align: center;
        }

        .sucesso p {
            margin: 20px 0;
        }

        a {
            color: #f0f0f0;
        }

    </style>

</head>

<body>

    <div class="container">

        <?php if ($sucesso): ?>

            <div class="sucesso">

                <h1>
                    Tópico criado
                </h1>

                <p>
                    Tópico criado com sucesso!
                </p>

                <a href="lista.php">
                    Ver tópicos
                </a>

            </div>

        <?php else: ?>

            <h1>
                Criar novo tópico
            </h1>

            <?php if ($erro !== ''): ?>

                <div class="erro">
                    <?= escapar($erro) ?>
                </div>

            <?php endif; ?>

            <form method="POST">

                <label>
                    Título:
                </label>

                <input
                    type="text"
                    name="titulo"
                    placeholder="Digite o título do tópico"
                    required
                >

                <label>
                    Mensagem:
                </label>

                <textarea
                    name="mensagem"
                    placeholder="Escreva sua mensagem..."
                    required
                ></textarea>

                <button type="submit">
                    Criar tópico
                </button>

            </form>

        <?php endif; ?>

    </div>

</body>

</html>