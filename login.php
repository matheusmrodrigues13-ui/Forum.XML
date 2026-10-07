<?php
session_start();

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $usuarios = simplexml_load_file("usuarios.xml");
    $email = trim($_POST['email']);
    $senha = md5($_POST['senha']);

    foreach ($usuarios->usuario as $u) {
        if (trim((string) $u->email) === $email && trim((string) $u->senha) === $senha) {
            $_SESSION['usuario'] = (string) $u->email;
            header("Location: criar_topico.php");
            exit;
        }
    }

    $erro = "Login inválido!";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * {
            padding: 0;
            margin: 0;
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
        main {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        section {
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
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            color: #d8d8d8;
            font-size: 14px;
            margin-bottom: 7px;
        }
        input {
            width: 100%;
            height: 42px;
            padding: 0 13px;
            margin-bottom: 20px;
            background: #252525;
            color: white;
            border: 1px solid #444;
            border-radius: 8px;
            outline: none;
            transition: 0.2s;
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
            transition: 0.2s;
        }
        button:hover {
            background: #d6d6d6;
            transform: translateY(-1px);
        }
        .error {
            margin-bottom: 20px;
            padding: 12px;
            text-align: center;
            background: #5c1d1d;
            color: #ffdada;
            border: 1px solid #7a2929;
            border-radius: 8px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <main>
        <section>
            <h2>Login</h2>
            <?php if ($erro !== "") { ?>
                <div class="error"><?php echo $erro; ?></div>
            <?php } ?>
            <form method="POST">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
                <label for="senha">Senha:</label>
                <input type="password" name="senha" required>
                <button type="submit">Entrar</button>
            </form>
        </section>
    </main>
</body>
</html>