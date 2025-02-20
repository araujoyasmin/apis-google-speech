<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu de Fala</title>
    <link rel="stylesheet" href="css/style.css">
   
</head>
<body>
    <h1>Google Speech Menu</h1>
    <button onclick="navegarPara('texttospeech.php')">Text to Speech</button>
    <button onclick="navegarPara('speechtotext.php')">Speech to Text</button>
    <button onclick="navegarPara('tts_navegador.php')">Transcrição pelo navegador</button>

    <script>
        function navegarPara(pagina) {
            window.location.href = pagina;
        }
    </script>
</body>
</html>
