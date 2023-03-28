<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Logger - Exemple</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>IP Logger - Exemple</h1>
        <?php
        $client_ip = $_SERVER["REMOTE_ADDR"];

        $x_forwarded_for = $_SERVER["HTTP_X_FORWARDED_FOR"];
        $elements = explode(",", $x_forwarded_for);
        $x_forwarded_for = trim($elements[0]);

        $whitelist =
            '/^(?:(?:[0-9]{1,3}\.){3}[0-9]{1,3}|[a-zA-Z0-9.\-&_\$\{\}<>\*]+)$/';

        if (preg_match($whitelist, $x_forwarded_for)) {
            $log_entry = $client_ip . " - " . $x_forwarded_for;
            $log_file = "log.txt";
            $cmd = "echo " . $log_entry . " >> " . $log_file;
            $output = shell_exec($cmd);
            echo "Adresse IP enregistrée : " . htmlspecialchars($log_entry);
        } else {
            echo "Tu es sur la bonne voie :) .";
        }
        ?>
    </div>
</body>
</html>
