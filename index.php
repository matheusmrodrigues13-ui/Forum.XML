<?php

require_once __DIR__ . '/funcoes.php';

$nome = '';
$celular = '';
$email = '';
$erro = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim((string) ($_POST['nome'] ?? ''));
    $celular = trim((string) ($_POST['celular'] ?? ''));
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $senha = (string) ($_POST['senha'] ?? '');

    if (
        $nome === '' ||
        $celular === '' ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $erro = 'Preencha nome, celular e um e-mail válido.';

    } elseif (strlen($senha) < 6) {

        $erro = 'A senha deve possuir pelo menos 6 caracteres.';

    } else {

        try {

            $usuarios = carregarXml(
                ARQUIVO_USUARIOS,
                'usuarios'
            );

            foreach ($usuarios->usuario as $usuario) {

                if (
                    strcasecmp(
                        (string) $usuario->email,
                        $email
                    ) === 0
                ) {
                    $erro = 'Este e-mail já está cadastrado.';
                    break;
                }
            }

            if ($erro === '') {

                $novo = $usuarios->addChild('usuario');

                adicionarTextoXml(
                    $novo,
                    'nome',
                    $nome
                );

                adicionarTextoXml(
                    $novo,
                    'celular',
                    $celular
                );

                adicionarTextoXml(
                    $novo,
                    'email',
                    $email
                );

                adicionarTextoXml(
                    $novo,
                    'senha',
                    password_hash(
                        $senha,
                        PASSWORD_DEFAULT
                    )
                );

                salvarXml(
                    $usuarios,
                    ARQUIVO_USUARIOS
                );

                $sucesso = true;
            }

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

        .Login h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #f0f0f0;
        }

        .Login label {
            display: block;
            color: #d8d8d8;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .Login input {
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

        .Login input:focus {
            border-color: #777;
            background: #292929;
        }

        .cadastrar {
            margin-top: 10px;
            border: none !important;
            background: #f0f0f0 !important;
            color: #111 !important;
            font-weight: bold;
            cursor: pointer;
        }

        .cadastrar:hover {
            background: #d6d6d6 !important;
        }

        .mensagem {
            width: 400px;
            padding: 35px;
            background: #1c1c1c;
            border: 1px solid #333;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.45);
            text-align: center;
        }

        .mensagem p {
            margin-bottom: 20px;
        }

        a {
            color: #f0f0f0;
        }

        .erro {
            width: 400px;
            margin: 0 auto 15px;
            padding: 12px;
            background: #252525;
            border: 1px solid #444;
            border-radius: 8px;
            color: #f0f0f0;
            text-align: center;
        }

    </style>

</head>

<body>

<?php if ($sucesso): ?>

    <div class="mensagem">

        <p>
            Usuário cadastrado com sucesso!
        </p>

        <a href="login.php">
            Fazer login
        </a>

    </div>

<?php else: ?>

    <form method="POST" class="Login">

        <h2>
            Criar conta
        </h2>

        <?php if ($erro !== ''): ?>

            <div class="erro">
                <?= escapar($erro) ?>
            </div>

        <?php endif; ?>

        <label>
            Nome:

            <input
                type="text"
                name="nome"
                value="<?= escapar($nome) ?>"
                required
            >
        </label>

        <label>
            Celular:

            <input
                type="text"
                name="celular"
                value="<?= escapar($celular) ?>"
                required
            >
        </label>

        <label>
            Email:

            <input
                type="email"
                name="email"
                value="<?= escapar($email) ?>"
                required
            >
        </label>

        <label>
            Senha:

            <input
                type="password"
                name="senha"
                minlength="6"
                required
            >
        </label>

        <input
            type="submit"
            value="Cadastrar"
            class="cadastrar"
        >

    </form>

<?php endif; ?>

</body>

</html>