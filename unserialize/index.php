<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Démonstration de désérialisation non sécurisée</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
        }
        header {
            background-color: #f0f0f0;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }
        main {
            padding: 20px;
        }
    </style>
</head>
<body>
    <header>
        Démonstration de désérialisation non sécurisée
    </header>
    <main>
        <p>Ceci est une démonstration de désérialisation non sécurisée en PHP. Ne pas utiliser cette démonstration pour des activités malveillantes. Ceci est à des fins éducatives uniquement.
        Utilise <b>/?source</b> pour voir le code source</p>

<?php

class FileWriter {
    public $path;
    public $content;

    public function __destruct() {
        if (!empty($this->path) && !empty($this->content)) {
            $decodedContent = base64_decode($this->content);
            file_put_contents($this->path, $decodedContent);
        }
    }
}

class Wrapper {
    public $fileWriter;

    public function __wakeup() {
        $this->fileWriter = new FileWriter();
    }
}

if (isset($_GET['source'])) {
    echo "<h2>Attention : Ne pas utiliser cette démonstration pour des activités malveillantes. Ceci est à des fins éducatives uniquement.</h2>";
    highlight_file(__FILE__);
    exit;
}

$validContent = base64_encode("Contenu valide.");
$validData = 'O:7:"Wrapper":1:{s:10:"fileWriter";O:10:"FileWriter":2:{s:4:"path";s:18:"/tmp/validfile.txt";s:7:"content";s:' . strlen($validContent) . ':"' . $validContent . '";}}';

if (isset($_COOKIE['data'])) {
    $unserializedData = unserialize($_COOKIE['data']);
}

?>

    </main>
</body>
  </html>
