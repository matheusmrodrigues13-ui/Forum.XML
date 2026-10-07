<?php

session_start();

require_once __DIR__ . '/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: lista.php');
    exit;
}

if (!isset($_SESSION['usuario'])) {
    encerrarComErro(
        'Você precisa estar logado.',
        403
    );
}

if (!csrfValido($_POST['csrf_token'] ?? null)) {
    encerrarComErro(
        'Token de segurança inválido.',
        403
    );
}

$id = obterIndice($_POST['id'] ?? null);

if ($id === null) {
    encerrarComErro('Tópico inválido.');
}

$nome = trim(
    (string) ($_POST['nome'] ?? '')
);

$mensagem = trim(
    (string) ($_POST['mensagem'] ?? '')
);

if ($nome === '' || $mensagem === '') {
    encerrarComErro(
        'Preencha nome e comentário.'
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

    if (!isset($topicos->topico[$id]->comentarios)) {
        $topicos->topico[$id]->addChild(
            'comentarios'
        );
    }

    $comentario =
        $topicos
        ->topico[$id]
        ->comentarios
        ->addChild('comentario');

    adicionarTextoXml(
        $comentario,
        'nome',
        $nome
    );

    adicionarTextoXml(
        $comentario,
        'mensagem',
        $mensagem
    );

    salvarXml(
        $topicos,
        ARQUIVO_TOPICOS
    );

    $_SESSION['mensagem_sucesso'] =
        'Comentário adicionado com sucesso!';

    header('Location: lista.php');
    exit;

} catch (RuntimeException $excecao) {

    encerrarComErro(
        $excecao->getMessage(),
        500
    );
}

?>