<?php
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $topicos = simplexml_load_file("topicos.xml");
    $id = intval($_POST['id']);
    $comentario = $topicos->topico[$id]->comentarios->addChild("comentario", $_POST['comentario']);
    $comentario->addChild("nome", $_POST['nome']);
    $comentario->addChild("mensagem", $_POST['mensagem']);
    $topicos->asXML("topicos.xml");
    header("Location: lista.php");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentar</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }
        form {
            width: 500px;
            padding: 40px;
            background: #1c1c1c;
            border: 1px solid #333;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.45);
            display: flex;
            flex-direction: column;
        }
        h2 {
            margin-bottom: 30px;
            color: #f0f0f0;
            font-size: 24px;
            text-align: center;
        }
        label {
            color: #d8d8d8;
            font-size: 14px;
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
            transition: 0.2s;
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
            transition: 0.2s;
        }
        button:hover {
            background: #d6d6d6;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Adicionar comentário</h2>
        <input type="hidden" name="id" value="<?php echo $_GET['id'] ?? ''; ?>">
        <label>Nome:</label>
        <input type="text" name="nome" required>
        <label>Comentário:</label>
        <textarea name="mensagem" required></textarea>
        <input type="hidden" name="comentario" value="comentario">
        <button type="submit">Enviar comentário</button>
    </form>
</body>
</html>