<?php

session_start();

require_once __DIR__ . '/funcoes.php';

if (!isset($_SESSION['usuario'])) {

    encerrarComErro(
        'Você precisa estar logado.',
        403
    );
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: lista.php');
    exit;
}

if (!csrfValido($_POST['csrf_token'] ?? null)) {

    encerrarComErro(
        'Token de segurança inválido.',
        403
    );
}

$id = obterIndice(
    $_POST['id'] ?? null
);

$comentarioId = obterIndice(
    $_POST['comentario'] ?? null
);

if ($id === null || $comentarioId === null) {

    encerrarComErro(
        'Dados inválidos.'
    );
}

try {

    $topicos = carregarXml(
        ARQUIVO_TOPICOS,
        'topicos'
    );

    if (!isset($topicos->topico[$id])) {

        encerrarComErro(
            'Tópico não encontrado.',
            404
        );
    }

    $topico = $topicos->topico[$id];

    if (
        (string) $_SESSION['usuario']
        !==
        (string) $topico->autor
    ) {

        encerrarComErro(
            'Você não pode excluir comentários deste tópico.',
            403
        );
    }

    if (
        !isset(
            $topico
            ->comentarios
            ->comentario[$comentarioId]
        )
    ) {

        encerrarComErro(
            'Comentário não encontrado.',
            404
        );
    }

    unset(
        $topico
        ->comentarios
        ->comentario[$comentarioId]
    );

    salvarXml(
        $topicos,
        ARQUIVO_TOPICOS
    );

    $_SESSION['mensagem_sucesso'] =
        'Comentário excluído com sucesso!';

    header('Location: lista.php');
    exit;

} catch (RuntimeException $excecao) {

    encerrarComErro(
        $excecao->getMessage(),
        500
    );
}