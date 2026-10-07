<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    echo "Você precisa estar logado para criar um tópico.";
    exit;
}

$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $topicos = simplexml_load_file("topicos.xml");
    $novo = $topicos->addChild("topico");
    $novo->addChild("autor", $_SESSION['usuario']);
    $novo->addChild("titulo", $_POST['titulo']);
    $novo->addChild("mensagem", $_POST['mensagem']);
    $novo->addChild("comentarios");
    $topicos->asXML("topicos.xml");
    $sucesso = true;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            width: 520px;
            padding: 40px;
            background: #1c1c1c;
            border: 1px solid #333;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.45);
        }
        h1 {
            text-align: center;
            color: #f0f0f0;
            font-size: 25px;
            margin-bottom: 30px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            color: #d8d8d8;
            font-size: 14px;
            margin-bottom: 7px;
        }
        input,
        textarea {
            width: 100%;
            background: #252525;
            color: white;
            border: 1px solid #444;
            border-radius: 8px;
            outline: none;
            transition: 0.2s;
        }
        input {
            height: 44px;
            padding: 0 13px;
            margin-bottom: 20px;
        }
        textarea {
            min-height: 160px;
            padding: 13px;
            resize: vertical;
            margin-bottom: 20px;
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
            transition: 0.2s;
        }
        button:hover {
            background: #d6d6d6;
            transform: translateY(-1px);
        }
        .sucesso {
            text-align: center;
        }
        .sucesso p {
            color: #d9ffe6;
            background: #1d5c35;
            border: 1px solid #2b7546;
            padding: 13px;
            border-radius: 8px;
            margin-bottom: 18px;
        }
        .sucesso a {
            color: #ddd;
            text-decoration: none;
            font-size: 14px;
        }
        .sucesso a:hover {
            color: #fff;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($sucesso) { ?>
            <div class="sucesso">
                <h1>Tópico criado</h1>
                <p>Tópico criado com sucesso!</p>
                <a href="lista.php">Ver tópicos</a>
            </div>
        <?php } else { ?>
            <h1>Criar novo tópico</h1>
            <form method="POST">
                <label for="titulo">Título:</label>
                <input type="text" name="titulo" placeholder="Digite o título do tópico" required>

                <label for="mensagem">Mensagem:</label>
                <textarea name="mensagem" placeholder="Escreva sua mensagem..." required></textarea>

                <button type="submit">Criar tópico</button>
            </form>
        <?php } ?>
    </div>
</body>
</html>