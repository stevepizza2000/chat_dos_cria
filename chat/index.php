<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Chat Local</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 20px;
            text-align: center;
        }
        #chat {
            width: 80%;
            height: 300px;
            margin: 0 auto;
            padding: 10px;
            background: #fff;
            border: 1px solid #ccc;
            overflow-y: scroll;
        }
        form {
            margin-top: 10px;
        }
        input[type=text] {
            width: 60%;
            padding: 10px;
        }
        button {
            padding: 10px 15px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <h1>Chat Local em PHP 💬</h1>

    <div id="chat">
        <?php
            if (file_exists("mensagens.txt")) {
                echo nl2br(file_get_contents("mensagens.txt"));
            } else {
                echo "Nenhuma mensagem ainda...";
            }
        ?>
    </div>

    <form action="enviar.php" method="post">
        <input type="text" name="usuario" placeholder="Seu nome" required>
        <input type="text" name="mensagem" placeholder="Digite sua mensagem" required>
        <button type="submit">Enviar</button>
    </form>

    <script>
        // Atualiza o chat a cada 2 segundos
        setInterval(() => {
            fetch("mensagens.txt")
                .then(response => response.text())
                .then(data => {
                    document.getElementById("chat").innerHTML = data.replace(/\n/g, "<br>");
                });
        }, 2000);
    </script>

</body>
</html>
