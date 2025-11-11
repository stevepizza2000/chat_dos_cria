<?php
if (!empty($_POST['usuario']) && !empty($_POST['mensagem'])) {
    $usuario = htmlspecialchars($_POST['usuario']);
    $mensagem = htmlspecialchars($_POST['mensagem']);

    $linha = "[$usuario]: $mensagem\n";
    file_put_contents("mensagens.txt", $linha, FILE_APPEND);
}

header("Location: index.php");
exit;
?>
