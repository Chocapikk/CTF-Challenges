<?php
session_start();

if (!isset($_SESSION['count'])) {
    $_SESSION['count'] = 0;
    $_SESSION['start_time'] = microtime(true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['contact']) && isset($_POST['captcha'])) {
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $captcha = $_POST['captcha'];

        $elapsed_time = microtime(true) - $_SESSION['start_time'];

        if ($_SESSION['captcha'] == $captcha && $elapsed_time <= 5) {
            $_SESSION['count']++;

            if ($_SESSION['count'] == 5) {
                echo "Bravo ! Voici le flag : BALGO{CE_CAPTCHA_EST_POURRI}";
                session_destroy();
                exit;
            } else {
                echo "Bravo ! Continuez.";
            }
        } else {
            if ($elapsed_time > 5) {
                echo "Temps écoulé. Essayez encore.";
            } else {
                echo "Mauvaise réponse. Essayez encore.";
            }
        }
    } else {
        echo "Formulaire non valide. Veuillez vérifier les champs.";
    }
}

$num1 = rand(0, 99);
$num2 = rand(0, 99);
$operator = rand(0, 1) ? '+' : '-';
$_SESSION['captcha'] = $operator == '+' ? $num1 + $num2 : $num1 - $num2;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captcha</title>
    <style>
      body {
    background-color: #0b3d0b;
    font-family: 'Courier New', monospace;
    color: #3cff3c;
}

form {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-top: 100px;
}

label, input {
    font-size: 1.2em;
    margin: 10px;
}

input[type="text"], input[type="email"] {
    background-color: #1e581e;
    color: #3cff3c;
    border: 2px solid #3cff3c;
    padding: 5px 10px;
}

input[type="submit"] {
    background-color: #3cff3c;
    color: #0b3d0b;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #1e581e;
    border: 2px solid #3cff3c;
    color: #3cff3c;
}

    </style>
</head>
<body>
    <h1>Challenge Captcha</h1>
    <p>
        Ce challenge consiste à résoudre 5 captchas en moins de 5 secondes.
        Si vous réussissez, vous obtiendrez un flag.
    </p>
    <p>Tentatives restantes : <?php echo 5 - $_SESSION['count']; ?></p>

    <form action="" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="contact">Message:</label>
        <input type="text" id="contact" name="contact" required><br><br>

        <label for="captcha"><?php echo "<captcha>$num1 $operator $num2</captcha>"; ?> =</label>
        <input type="text" id="captcha" name="captcha" required><br><br>

        <input type="submit" value="Envoyer">
    </form>
</body>
</html>
