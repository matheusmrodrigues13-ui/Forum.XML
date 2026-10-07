<?php
session_start();
require_once __DIR__ . '/Funcoes.php';

$erro = '';
$mensagemSucesso = (string) ($_SESSION['mensagem_sucesso'] ?? '');
unset($_SESSION['mensagem_sucesso']);

try {
    $topicos = carregarXml(ARQUIVO_TOPICOS, 'topicos');
} catch (RuntimeException $excecao) {
    $erro = $excecao->getMessage();
    $topicos = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><topicos/>');
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            color: #fff;
            padding: 40px 20px;
        }
        header {
            max-width: 900px;
            margin: 0 auto 30px;
            padding: 25px;
            background: #1c1c1c;
            border: 1px solid #333;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
        }
        header h1 {
            color: #f0f0f0;
            font-size: 28px;
            margin-bottom: 15px;
        }
        nav {
            color: #999;
            font-size: 14px;
        }
        nav span {
            color: #bbb;
        }
        a {
            color: #ddd;
            text-decoration: none;
            transition: 0.2s;
        }
        a:hover {
            color: #fff;
            text-decoration: underline;
        }
        main {
            max-width: 900px;
            margin: 0 auto;
        }
        .mensagem {
            padding: 13px 16px;
            margin-bottom: 20px;
            background: #1d5c35;
            border: 1px solid #2b7546;
            color: #d9ffe6;
            border-radius: 8px;
        }
        .erro {
            padding: 13px 16px;
            margin-bottom: 20px;
            background: #5c1d1d;
            border: 1px solid #7a2929;
            color: #ffdada;
            border-radius: 8px;
        }
        .vazio {
            padding: 30px;
            text-align: center;
            background: #1c1c1c;
            border: 1px solid #333;
            border-radius: 12px;
            color: #888;
        }
        article {
            background: #1c1c1c;
            border: 1px solid #333;
            border-radius: 14px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        article h2 {
            color: #f0f0f0;
            font-size: 22px;
            margin-bottom: 10px;
        }
        .autor {
            color: #888;
            font-size: 13px;
            margin-bottom: 18px;
        }
        .texto {
            padding: 15px;
            background: #252525;
            border: 1px solid #333;
            border-radius: 8px;
            color: #ddd;
            line-height: 1.5;
            margin-bottom: 25px;
        }
        article h3 {
            color: #ddd;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .comentario {
            padding: 14px;
            background: #252525;
            border: 1px solid #333;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .comentario p {
            color: #bbb;
            font-size: 14px;
            line-height: 1.5;
        }
        .comentario strong {
            color: #fff;
        }
        .excluir {
            margin-top: 10px;
        }
        .excluir button {
            background: transparent;
            border: 1px solid #633;
            color: #d99;
            padding: 7px 11px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.2s;
        }
        .excluir button:hover {
            background: #5c1d1d;
            color: #fff;
        }
        .sem-comentarios {
            color: #777;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .form-comentario {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #333;
        }
        .form-comentario label {
            display: block;
            color: #ccc;
            font-size: 14px;
            margin-bottom: 7px;
        }
        .form-comentario input,
        .form-comentario textarea {
            width: 100%;
            background: #252525;
            color: white;
            border: 1px solid #444;
            border-radius: 8px;
            outline: none;
            padding: 11px 13px;
            margin-bottom: 15px;
        }
        .form-comentario input {
            height: 40px;
        }
        .form-comentario textarea {
            min-height: 100px;
            resize: vertical;
        }
        .form-comentario input:focus,
        .form-comentario textarea:focus {
            border-color: #777;
            background: #292929;
        }
        .form-comentario button {
            height: 40px;
            padding: 0 20px;
            border: none;
            border-radius: 8px;
            background: #f0f0f0;
            color: #111;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }
        .form-comentario button:hover {
            background: #d6d6d6;
            transform: translateY(-1px);
        }
        .criar {
            display: inline-block;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Tópicos do fórum</h1>
        <nav>
            <?php if (isset($_SESSION['usuario'])): ?>
                <span>Conectado como <?= escapar($_SESSION['usuario']) ?></span>
                <a href="criar_topico.php" class="criar">Criar tópico</a>
            <?php else: ?>
                <a href="login.php">Entrar</a>
                <a href="index.php" class="criar">Cadastrar</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <?php if ($mensagemSucesso !== ''): ?>
            <p class="mensagem"><?= escapar($mensagemSucesso) ?></p>
        <?php endif; ?>

        <?php if ($erro !== ''): ?>
            <p class="erro"><?= escapar($erro) ?></p>
        <?php elseif (count($topicos->topico) === 0): ?>
            <p class="vazio">Nenhum tópico foi criado ainda.</p>
        <?php endif; ?>

        <?php $id = 0; ?>

        <?php foreach ($topicos->topico as $topico): ?>
            <article>
                <h2><?= escapar($topico->titulo) ?></h2>

                <p class="autor">
                    Autor: <?= escapar($topico->autor) ?>
                </p>

                <div class="texto">
                    <?= nl2br(escapar($topico->mensagem)) ?>
                </div>

                <h3>Comentários</h3>

                <?php if (count($topico->comentarios->comentario) === 0): ?>
                    <p class="sem-comentarios">
                        Este tópico ainda não possui comentários.
                    </p>
                <?php endif; ?>

                <?php $comentarioId = 0; ?>

                <?php foreach ($topico->comentarios->comentario as $comentario): ?>
                    <section class="comentario">
                        <p>
                            <strong><?= escapar($comentario->nome) ?>:</strong>
                            <?= nl2br(escapar($comentario->mensagem)) ?>
                        </p>

                        <?php if (isset($_SESSION['usuario']) && (string) $_SESSION['usuario'] === (string) $topico->autor): ?>
                            <form method="post" action="excluir.php" class="excluir">
                                <input type="hidden" name="csrf_token" value="<?= escapar(tokenCsrf()) ?>">
                                <input type="hidden" name="id" value="<?= escapar($id) ?>">
                                <input type="hidden" name="comentario" value="<?= escapar($comentarioId) ?>">
                                <button type="submit">Excluir comentário</button>
                            </form>
                        <?php endif; ?>
                    </section>

                    <?php $comentarioId++; ?>
                <?php endforeach; ?>

                <form method="post" action="comentar.php" class="form-comentario">
                    <input type="hidden" name="csrf_token" value="<?= escapar(tokenCsrf()) ?>">
                    <input type="hidden" name="id" value="<?= escapar($id) ?>">

                    <label>
                        Nome:
                        <input type="text" name="nome" required>
                    </label>

                    <label>
                        Comentário:
                        <textarea name="mensagem" required></textarea>
                    </label>

                    <button type="submit">Comentar</button>
                </form>
            </article>

            <?php $id++; ?>
        <?php endforeach; ?>
    </main>
</body>
</html>