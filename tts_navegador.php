<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Speech-to-Text e Text-to-Speech</title>
    <link rel="stylesheet" href="css/style.css">
   
</head>
<body>
    <h1>Google Speech-to-Text e Text-to-Speech</h1>
    <div>
        <div id="tts-content" class="content">
            <h2>Text-to-Speech</h2>
            <textarea id="tts-text" rows="5" cols="40" placeholder="Insira o texto para converter em fala..."></textarea>
            <br>
            <button onclick="convertToSpeech()">Converter para Fala</button>
        </div>

        <div id="stt-content" class="content">
            <h2>Speech-to-Text</h2>
            <p>Pressione o botão para começar a gravar e converter fala em texto:</p>
            <button onclick="startSpeechToText()">Iniciar Gravação</button>
            <div id="stt-result">Resultado: <span id="result-text"></span></div>
        </div>
        <a href="index.php" id="btn-voltar">Voltar para o Início</a>
    </div>
    <script>
        // Função para alternar para a tela de Text-to-Speech
        document.getElementById('btn-tts').addEventListener('click', function() {
            document.getElementById('tts-content').classList.add('active');
            document.getElementById('stt-content').classList.remove('active');
        });

        // Função para alternar para a tela de Speech-to-Text
        document.getElementById('btn-stt').addEventListener('click', function() {
            document.getElementById('stt-content').classList.add('active');
            document.getElementById('tts-content').classList.remove('active');
            
        });


        // Placeholder: Função para conversão de texto para fala
        function convertToSpeech() {
            const text = document.getElementById('tts-text').value;
            if (text) {
                const utterance = new SpeechSynthesisUtterance(text);
                speechSynthesis.speak(utterance);
            } else {
                alert('Por favor, insira um texto.');
            }
        }

        // Placeholder: Função para iniciar o reconhecimento de fala
        function startSpeechToText() {
            if ('webkitSpeechRecognition' in window) {
                const recognition = new webkitSpeechRecognition();
                recognition.lang = 'pt-BR';
                recognition.onresult = function(event) {
                    document.getElementById('result-text').textContent = event.results[0][0].transcript;
                };
                recognition.start();
            } else {
                alert('Seu navegador não suporta reconhecimento de fala.');
            }
        }
    </script>
    
</body>
</html>
