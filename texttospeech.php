<?php
require 'vendor/autoload.php';

use Google\Cloud\TextToSpeech\V1\Client\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding; 
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesizeSpeechRequest;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['text'])) {
    $text = $_POST['text'];

    // Defina o caminho para o arquivo de credenciais
    putenv('GOOGLE_APPLICATION_CREDENTIALS=credentials.json');

    // Criação do cliente para a API Text-to-Speech
    $client = new TextToSpeechClient();

    // Defina o texto a ser convertido
    $input = new SynthesisInput();
    $input->setText($text);

    // Definir a voz
    $voice = new VoiceSelectionParams();
    $voice->setLanguageCode('pt-BR');
    $voice->setSsmlGender(SsmlVoiceGender::FEMALE);

    // Defina o formato do áudio
    $audioConfig = new AudioConfig();
    $audioConfig->setAudioEncoding(AudioEncoding::MP3);

    // Criar o objeto SynthesizeSpeechRequest
    $request = new SynthesizeSpeechRequest();
    $request->setInput($input);
    $request->setVoice($voice);
    $request->setAudioConfig($audioConfig);

    $response = $client->synthesizeSpeech($request);

    // Salvar o arquivo de áudio
    $audioContent = $response->getAudioContent();
    $audioFile = 'output_' . time() . '.mp3';
    file_put_contents($audioFile, $audioContent);

    // Liberar o cliente
    $client->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Text-to-Speech com PHP</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Google Text-to-Speech</h1>
    <form method="POST">
        <label for="text">Digite o texto para conversão:</label><br>
        <textarea id="text" name="text" rows="4" cols="50" required></textarea><br><br>
        <button type="submit">Converter para Áudio</button>
    </form>
    <a href="index.php" id="btn-voltar">Voltar para o Início</a>

    <?php if (isset($audioFile)): ?>
        <p>Áudio gerado com sucesso!</p>
        <audio controls autoplay>
            <source src="<?= $audioFile . '?v=' . time() ?>" type="audio/mpeg">
            Seu navegador não suporta o elemento de áudio.
        </audio>
    <?php endif; ?>
</body>
</html>
