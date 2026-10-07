<?php
$sucesso = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = simplexml_load_file("usuarios.xml");
    $novo = $usuario->addChild("usuario");
    $novo->addChild("nome", $_POST["nome"]);
    $novo->addChild("celular", $_POST["celular"]);
    $novo->addChild("email", $_POST["email"]);
    $novo->addChild("senha", md5($_POST["senha"]));
    $usuario->asXML("usuarios.xml");
    $sucesso = true;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
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
            color: #fff;
        }
        .Login {
            width: 420px;
            padding: 40px;
            background: #1c1c1c;
            border: 1px solid #333;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.45);
        }
        h2 {
            margin-bottom: 30px;
            text-align: center;
            color: #f0f0f0;
            font-size: 24px;
        }
        label {
            display: block;
            color: #d8d8d8;
            font-size: 14px;
            margin-bottom: 18px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            height: 42px;
            margin-top: 7px;
            padding: 0 13px;
            background: #252525;
            color: white;
            border: 1px solid #444;
            border-radius: 8px;
            outline: none;
            transition: 0.2s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #777;
            background: #292929;
        }
        .cadastrar {
            width: 100%;
            height: 44px;
            margin-top: 8px;
            border: none;
            border-radius: 8px;
            background: #f0f0f0;
            color: #111;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }
        .cadastrar:hover {
            background: #d6d6d6;
            transform: translateY(-1px);
        }
        .sucesso {
            text-align: center;
        }
        .sucesso p {
            margin-bottom: 15px;
            color: #d9ffe6;
        }
        .sucesso a {
            color: #fff;
            text-decoration: none;
        }
        .sucesso a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php if ($sucesso) { ?>
        <div class="Login sucesso">
            <p>Usuário cadastrado com sucesso!</p>
            <a href="login.php">Fazer login</a>
        </div>
    <?php } else { ?>
        <form method="POST" class="Login">
            <h2>Criar conta</h2>
            <label>Nome:
                <input type="text" name="nome" required>
            </label>
            <label>Celular:
                <input type="text" name="celular" required>
            </label>
            <label>Email:
                <input type="email" name="email" required>
            </label>
            <label>Senha:
                <input type="password" name="senha" required>
            </label>
            <input type="submit" value="Cadastrar" class="cadastrar">
        </form>
    <?php } ?>
</body>
</html>