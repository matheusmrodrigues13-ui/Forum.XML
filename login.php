<?php

session_start();

require_once __DIR__ . '/funcoes.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = strtolower(
        trim((string) ($_POST['email'] ?? ''))
    );

    $senha = (string) ($_POST['senha'] ?? '');

    try {

        $usuarios = carregarXml(
            ARQUIVO_USUARIOS,
            'usuarios'
        );

        foreach ($usuarios->usuario as $usuario) {

            if (
                strtolower(trim((string) $usuario->email))
                === $email
                &&
                password_verify(
                    $senha,
                    (string) $usuario->senha
                )
            ) {

                $_SESSION['usuario'] =
                    (string) $usuario->email;

                header(
                    'Location: criar_topico.php'
                );

                exit;
            }
        }

        $erro = 'Login inválido!';

    } catch (RuntimeException $excecao) {

        $erro = $excecao->getMessage();
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

    <title>Login</title>

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

        .Login {
            width: 400px;
            padding: 40px;
            background: #1c1c1c;
            border: 1px solid #333;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.45);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #f0f0f0;
        }

        label {
            display: block;
            color: #d8d8d8;
            font-size: 14px;
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            height: 42px;
            padding: 10px 12px;
            margin-top: 6px;
            background: #252525;
            color: white;
            border: 1px solid #444;
            border-radius: 8px;
            outline: none;
        }

        input:focus {
            border-color: #777;
            background: #292929;
        }

        button {
            width: 100%;
            height: 44px;
            margin-top: 5px;
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

        .cadastro {
            margin-top: 20px;
            text-align: center;
        }

        a {
            color: #f0f0f0;
        }

    </style>

</head>

<body>

    <form method="POST" class="Login">

        <h2>
            Login
        </h2>

        <?php if ($erro !== ''): ?>

            <div class="erro">
                <?= escapar($erro) ?>
            </div>

        <?php endif; ?>

        <label>
            E-mail:

            <input
                type="email"
                name="email"
                required
            >
        </label>

        <label>
            Senha:

            <input
                type="password"
                name="senha"
                required
            >
        </label>

        <button type="submit">
            Entrar
        </button>

        <div class="cadastro">

            <a href="index.php">
                Criar conta
            </a>

        </div>

    </form>

</body>

</html>