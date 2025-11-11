<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Chat Local em PHP 💬</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 20px;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        #chat {
            width: 80%;
            max-width: 600px;
            height: 400px;
            margin: 0 auto;
            padding: 15px;
            background: #fff;
            border-radius: 10px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
            text-align: left;
        }
        .msg {
            margin: 5px 0;
            padding: 5px 10px;
            background: #e7f1ff;
            border-radius: 10px;
            display: inline-block;
        }
        form {
            margin-top: 15px;
        }
        input[type=text] {
            width: 35%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
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
            $arquivo = "mensagens.json";
            if (file_exists($arquivo)) {
                $mensagens = json_decode(file_get_contents($arquivo), true);
                if (!empty($mensagens)) {
                    foreach ($mensagens as $m) {
                        echo "<div class='msg'><b>[{$m['hora']}] {$m['usuario']}:</b> {$m['mensagem']}</div><br>";
                    }
                } else {
                    echo "Nenhuma mensagem ainda...";
                }
            } else {
                echo "Nenhuma mensagem ainda...";
            }
        ?>
    </div>

    <form id="form" action="enviar.php" method="post">
        <input type="text" name="usuario" placeholder="Seu nome" required>
        <input type="text" name="mensagem" placeholder="Digite sua mensagem" required>
        <button type="submit">Enviar</button>
    </form>

    <script>
        // Atualiza o chat a cada 2 segundos
        setInterval(() => {
            fetch("mensagens.json")
                .then(r => r.json())
                .then(data => {
                    let html = "";
                    for (const msg of data) {
                        html += `<div class='msg'><b>[${msg.hora}] ${msg.usuario}:</b> ${msg.mensagem}</div><br>`;
                    }
                    const chat = document.getElementById("chat");
                    chat.innerHTML = html;
                    chat.scrollTop = chat.scrollHeight;
                })
                .catch(() => {
                    document.getElementById("chat").innerHTML = "Nenhuma mensagem ainda...";
                });
        }, 2000);

        // Envia mensagem via AJAX sem recarregar
        const form = document.getElementById("form");
        form.addEventListener("submit", e => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch("enviar.php", { method: "POST", body: formData })
                .then(() => form.mensagem.value = ""); // limpa o campo
        });
    </script>

</body>
</html>

