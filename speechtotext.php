<?php
require 'vendor/autoload.php';

use Google\Cloud\TextToSpeech\V1\AudioEncoding;

use Google\Cloud\Speech\V1p1beta1\SpeechClient;
use Google\Cloud\Speech\V1p1beta1\RecognitionConfig;
use Google\Cloud\Speech\V1p1beta1\RecognitionAudio;

putenv('GOOGLE_APPLICATION_CREDENTIALS=credentials.json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['audio_file']['tmp_name'])) {
        // Speech-to-Text

        // Criação do cliente Speech-to-Text (v1p1beta1)
        $client = new SpeechClient();

        // O caminho para o arquivo de áudio
        $audioFile = $_FILES['audio_file']['tmp_name'];

        // Ler o arquivo de áudio como conteúdo binário
        $audioContent = file_get_contents($audioFile);

        // Configuração de reconhecimentoa
        $config = new RecognitionConfig();
        $config->setLanguageCode('pt-BR'); 
        $config->setEncoding(AudioEncoding::LINEAR16);
        $config->setSampleRateHertz(48000);

        // Configuração do reconhecimento de fala
        $audio = new RecognitionAudio();
        $audio->setContent($audioContent); 
        
        // Preparando a requisição
        $request = new \Google\Cloud\Speech\V1p1beta1\RecognizeRequest();
        $request->setConfig($config);
        $request->setAudio($audio); 
        
        // Chamada para transcrição
        $response = $client->recognize($config, $audio);
         
        $client->close();
    }   
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Speech-to-Text</title>
    <script>
        // Função para ativar o Speech-to-Text
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Google Speech-to-Text</h1>
    <div class="container">
        <div class="box">
            <h2>Gravação de Voz</h2>
            <p>Pressione o botão para começar a gravar e converter fala em texto:</p>
            <button onclick="startSpeechToText()">Iniciar Gravação</button>
            <div id="stt-result">Resultado: <span id="result-text"></span></div>
        </div>

        <div class="box">
            <h2>Upload de Arquivo de Áudio</h2>
            <form method="POST" enctype="multipart/form-data">
                <label for="audio_file">Escolha um arquivo de áudio:</label><br>
                <input type="file" name="audio_file" accept="audio/*" required><br><br>
                <button type="submit">Converter Fala para Texto</button>
            </form>
        </div>
        <a href="index.php" id="btn-voltar">Voltar para o Início</a>
    </div>

    <?php
    if (isset($response)) {
        try {
            echo "<br/>";
            echo '<div class="box">';
            echo '<h2>Resultado da Transcrição</h2>';
            foreach ($response->getResults() as $result) {
                $transcript = $result->getAlternatives()[0]->getTranscript();
                echo "<p>Texto reconhecido: " . htmlspecialchars($transcript) . "</p>";
            }
            echo '</div>';
        } catch (Exception $e) {
            echo '<div class="box">';
            echo '<h2>Erro</h2>';
            echo '<p>Erro ao reconhecer fala: ' . $e->getMessage() . '</p>';
            echo '</div>';
        }
    }
    ?>
</body>
</html>
