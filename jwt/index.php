<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JWT Vulnerability Example</title>
    <style>
        body {
            background-color: black;
            font-family: "Courier New", monospace;
            color: lime;
            font-size: 14px;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        textarea {
            background-color: black;
            color: lime;
            border: 1px solid lime;
            resize: none;
        }

        button {
            background-color: lime;
            border: none;
            color: black;
            padding: 5px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
        }

        button:hover {
            background-color: darkgreen;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php

        class SimpleJWT {
            public static function decode($jwt, $key) {
                $parts = explode('.', $jwt);

                if (count($parts) !== 3) {
                    return null;
                }

                list($header64, $payload64, $signature) = $parts;

                $header = json_decode(base64_decode(strtr($header64, '-_', '+/')), true);
                $payload = json_decode(base64_decode(strtr($payload64, '-_', '+/')), true);

                if (!$header || !$payload) {
                    return null;
                }

                if (isset($header['alg']) && $header['alg'] === 'none') {
                    return $payload;
                }

                $raw_signature = implode('.', array($header64, $payload64));

                if (hash_hmac('sha256', $raw_signature, $key) === $signature) {
                    return $payload;
                }

                return null;
            }
        }

        $example_jwt = 'eyJhbGciOiAibm9uZSIsICJ0eXAiOiAiSldUIn0.eyJ1c2VybmFtZSI6ICJ1c2VyIiwgInJvbGUiOiAidXNlciJ9.';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['jwt'])) {
                $jwt = $_POST['jwt'];
                $payload = SimpleJWT::decode($jwt, 'secret-key');

                if ($payload) {
                    echo "Bienvenue, " . htmlspecialchars($payload['username']) . "!<br>";

                    if (isset($payload['role']) && $payload['role'] === 'admin') {
                        echo "Flag: Balgo{you_passed_the_vibe_check}";
                    }
                } else {
                    echo "Token JWT invalide.";
                }
            }
                    } else {
        ?>

        <form method="POST">
            <p>Entrez votre token JWT :</p>
            <textarea name="jwt" rows="4" cols="40" placeholder="<?php echo htmlspecialchars($example_jwt); ?>"></textarea><br>
            <button type="submit">Vérifier le token JWT</button>
        </form>

        <?php
            }
        ?>
    </div>
</body>
</html>

