from flask import Flask, render_template, request, jsonify
import json, os, datetime

app = Flask(__name__)
ARQUIVO = "mensagens.json"

# Lê o JSON (ou cria um vazio)
def ler_mensagens():
    if os.path.exists(ARQUIVO):
        with open(ARQUIVO, "r", encoding="utf-8") as f:
            try:
                return json.load(f)
            except:
                return []
    return []

# Salva as mensagens
def salvar_mensagens(msgs):
    with open(ARQUIVO, "w", encoding="utf-8") as f:
        json.dump(msgs, f, ensure_ascii=False, indent=4)

@app.route("/")
def index():
    return render_template("index.html")

@app.route("/mensagens")
def mensagens():
    return jsonify(ler_mensagens())

@app.route("/enviar", methods=["POST"])
def enviar():
    usuario = request.form.get("usuario", "").strip()
    mensagem = request.form.get("mensagem", "").strip()
    if not usuario or not mensagem:
        return "Erro: campos vazios", 400

    msgs = ler_mensagens()
    nova = {
        "usuario": usuario,
        "mensagem": mensagem,
        "hora": datetime.datetime.now().strftime("%H:%M")
    }
    msgs.append(nova)
    msgs = msgs[-100:]  # mantém últimas 100
    salvar_mensagens(msgs)
    return "OK", 200

if __name__ == "__main__":
    app.run(port=5000, debug=True)


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Chat Local em Python 💬</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 20px;
            text-align: center;
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
        .msg { margin: 5px 0; background: #e7f1ff; padding: 6px 10px; border-radius: 10px; }
        input[type=text] { padding: 10px; width: 35%; }
        button { padding: 10px 15px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>

    <h1>Chat Local em Python 💬</h1>

    <div id="chat"></div>

    <form id="form">
        <input type="text" name="usuario" placeholder="Seu nome" required>
        <input type="text" name="mensagem" placeholder="Digite sua mensagem" required>
        <button type="submit">Enviar</button>
    </form>

    <script src="{{ url_for('static', filename='script.js') }}"></script>
</body>
</html>


function atualizarChat() {
    fetch("/mensagens")
        .then(r => r.json())
        .then(data => {
            let html = "";
            for (const msg of data) {
                html += `<div class='msg'><b>[${msg.hora}] ${msg.usuario}:</b> ${msg.mensagem}</div>`;
            }
            const chat = document.getElementById("chat");
            chat.innerHTML = html;
            chat.scrollTop = chat.scrollHeight;
        });
}

document.getElementById("form").addEventListener("submit", e => {
    e.preventDefault();
    const formData = new FormData(e.target);
    fetch("/enviar", { method: "POST", body: formData }).then(() => {
        e.target.mensagem.value = "";
        atualizarChat();
    });
});

setInterval(atualizarChat, 2000);
atualizarChat();
