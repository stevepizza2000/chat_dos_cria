<?php
date_default_timezone_set('America/Sao_Paulo');

if (!empty($_POST['usuario']) && !empty($_POST['mensagem'])) {
    $usuario = htmlspecialchars($_POST['usuario']);
    $mensagem = htmlspecialchars($_POST['mensagem']);
    $hora = date("H:i");
    $arquivo = "mensagens.json";

    // Lê o JSON atual (ou cria novo)
    if (file_exists($arquivo)) {
        $dados = json_decode(file_get_contents($arquivo), true);
        if (!is_array($dados)) $dados = [];
    } else {
        $dados = [];
    }

    // Adiciona nova mensagem
    $dados[] = [
        "usuario" => $usuario,
        "mensagem" => $mensagem,
        "hora" => $hora
    ];

    // Limita a 100 mensagens
    if (count($dados) > 100) {
        $dados = array_slice($dados, -100);
    }

    // Salva formatado
    file_put_contents($arquivo, json_encode($dados, JSON_PRETTY_PRINT));
}

http_response_code(200);
?>

