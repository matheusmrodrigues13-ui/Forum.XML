<?php

session_start();

require_once __DIR__ . '/funcoes.php';

$erro = '';

$mensagemSucesso =
    (string) ($_SESSION['mensagem_sucesso'] ?? '');

unset($_SESSION['mensagem_sucesso']);

try {

    $topicos = carregarXml(
        ARQUIVO_TOPICOS,
        'topicos'
    );

} catch (RuntimeException $excecao) {

    $erro = $excecao->getMessage();

    $topicos = new SimpleXMLElement(
        '<?xml version="1.0" encoding="UTF-8"?><topicos/>'
    );
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

    <title>Fórum</title>

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
            padding: 30px;
        }

        header {
            max-width: 1000px;
            margin: 0 auto 30px;
            padding: 25px;
            background: #1c1c1c;
            border: 1px solid #333;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.35);
        }

        header h1 {
            margin-bottom: 15px;
            color: #f0f0f0;
        }

        nav {
            color: #aaa;
        }

        nav a,
        a {
            color: #f0f0f0;
            text-decoration: none;
        }

        nav a:hover,
        a:hover {
            text-decoration: underline;
        }

        main {
            max-width: 1000px;
            margin: auto;
        }

        .aviso {
            margin-bottom: 20px;
            padding: 15px;
            background: #1c1c1c;
            border: 1px solid #333;
            border-radius: 10px;
        }

        article {
            margin-bottom: 25px;
            padding: 25px;
            background: #1c1c1c;
            border: 1px solid #333;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.30);
        }

        article h2 {
            margin-bottom: 15px;
            color: #f0f0f0;
        }

        article > p {
            margin-bottom: 12px;
            color: #d8d8d8;
            line-height: 1.5;
        }

        article h3 {
            margin: 25px 0 15px;
            color: #f0f0f0;
        }

        section {
            margin-bottom: 15px;
            padding: 15px;
            background: #252525;
            border: 1px solid #333;
            border-radius: 10px;
        }

        section p {
            color: #d8d8d8;
            line-height: 1.5;
        }

        form {
            margin-top: 20px;
        }

        input,
        textarea {
            width: 100%;
            padding: 11px 12px;
            margin-top: 6px;
            background: #252525;
            color: white;
            border: 1px solid #444;
            border-radius: 8px;
            outline: none;
        }

        input:focus,
        textarea:focus {
            border-color: #777;
            background: #292929;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        button {
            margin-top: 10px;
            padding: 11px 18px;
            border: none;
            border-radius: 8px;
            background: #f0f0f0;
            color: #111;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #d6d6d6;
        }

        .excluir {
            background: #333;
            color: white;
            border: 1px solid #444;
        }

        .excluir:hover {
            background: #444;
        }

        hr {
            display: none;
        }

        .vazio {
            padding: 30px;
            text-align: center;
            background: #1c1c1c;
            border: 1px solid #333;
            border-radius: 16px;
        }

    </style>

</head>

<body>

<header>

    <h1>
        Tópicos do fórum
    </h1>

    <nav>

        <?php if (isset($_SESSION['usuario'])): ?>

            <span>
                Conectado como
                <?= escapar($_SESSION['usuario']) ?>
            </span>

            &nbsp; | &nbsp;

            <a href="criar_topico.php">
                Criar tópico
            </a>

        <?php else: ?>

            <a href="login.php">
                Entrar
            </a>

            &nbsp; | &nbsp;

            <a href="index.php">
                Cadastrar
            </a>

        <?php endif; ?>

    </nav>

</header>

<main>

    <?php if ($mensagemSucesso !== ''): ?>

        <div class="aviso">
            <?= escapar($mensagemSucesso) ?>
        </div>

    <?php endif; ?>

    <?php if ($erro !== ''): ?>

        <div class="aviso">
            <?= escapar($erro) ?>
        </div>

    <?php elseif (count($topicos->topico) === 0): ?>

        <div class="vazio">
            Nenhum tópico foi criado ainda.
        </div>

    <?php endif; ?>

    <?php $id = 0; ?>

    <?php foreach ($topicos->topico as $topico): ?>

        <article>

            <h2>
                <?= escapar($topico->titulo) ?>
            </h2>

            <p>
                <?= nl2br(
                    escapar($topico->mensagem)
                ) ?>
            </p>

            <p>
                <small>
                    Autor:
                    <?= escapar($topico->autor) ?>
                </small>
            </p>

            <h3>
                Comentários
            </h3>

            <?php if (
                count(
                    $topico->comentarios->comentario
                ) === 0
            ): ?>

                <p>
                    Este tópico ainda não possui comentários.
                </p>

            <?php endif; ?>

            <?php $comentarioId = 0; ?>

            <?php foreach (
                $topico->comentarios->comentario
                as $comentario
            ): ?>

                <section>

                    <p>

                        <strong>
                            <?= escapar(
                                $comentario->nome
                            ) ?>:
                        </strong>

                        <?= nl2br(
                            escapar(
                                $comentario->mensagem
                            )
                        ) ?>

                    </p>

                    <?php if (
                        isset($_SESSION['usuario'])
                        &&
                        (string) $_SESSION['usuario']
                        ===
                        (string) $topico->autor
                    ): ?>

                        <form
                            method="post"
                            action="excluir.php"
                        >

                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= escapar(
                                    tokenCsrf()
                                ) ?>"
                            >

                            <input
                                type="hidden"
                                name="id"
                                value="<?= $id ?>"
                            >

                            <input
                                type="hidden"
                                name="comentario"
                                value="<?= $comentarioId ?>"
                            >

                            <button
                                type="submit"
                                class="excluir"
                            >
                                Excluir comentário
                            </button>

                        </form>

                    <?php endif; ?>

                </section>

                <?php $comentarioId++; ?>

            <?php endforeach; ?>

            <?php if (isset($_SESSION['usuario'])): ?>

                <form
                    method="post"
                    action="comentar.php"
                >

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= escapar(
                            tokenCsrf()
                        ) ?>"
                    >

                    <input
                        type="hidden"
                        name="id"
                        value="<?= $id ?>"
                    >

                    <label>
                        Nome:

                        <input
                            type="text"
                            name="nome"
                            required
                        >
                    </label>

                    <br>

                    <label>
                        Comentário:
                    </label>

                    <textarea
                        name="mensagem"
                        required
                    ></textarea>

                    <button type="submit">
                        Comentar
                    </button>

                </form>

            <?php endif; ?>

        </article>

        <?php $id++; ?>

    <?php endforeach; ?>

</main>

</body>

</html>