<?php // Do not put any HTML above this line

session_start();

require_once 'PDO.php';

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';


if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "User name and password are required";
        header("Location: login.php");
        return;
    } 
    elseif (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: login.php");
        return;
    }

    else {
        $check = hash('md5', $salt.$_POST['pass']);

        $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');

        $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ( $row !== false ) {

            $_SESSION['name'] = $row['name'];

            $_SESSION['user_id'] = $row['user_id'];

            // Redirect the browser to index.php

            header("Location: index.php");
            return;
        }else {
            //error_log("Login fail ".$_SESSION['who']." $check");
            $_SESSION['error'] = "Incorrect password"; 
            header("Location: login.php");
            return;   
        }
 } 
}
// Check to see if we have some POST data, if we do process it

?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

    <title>Oluwafunbi Adeneye</title>
</head>
<body>

    <?php
    if ( isset($_SESSION['error']) ) {
        echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
        unset($_SESSION['error']);
    }

    ?>

    <h1>Please Log In</h1>
    <form method="POST">
    <label for="nam">User Name</label>
    <input type="text" name="email" id="nam"><br>
    <label for="id_1723">Password</label>
    <input type="password" name="pass" id="id_1723"><br>
    <input type="submit" onclick="return doValidate();" value="Log In">
    <a href="index.php">Cancel</a><p></p>
    </form>
     
    <script>
        function doValidate() {
         console.log('Validating...');
         try {
             pw = document.getElementById('id_1723').value;
             na = document.getElementById('nam').value;
             console.log("Validating pw="+pw);
             if (pw == null || pw == "" || na == null || na == "") {
                 alert("Both fields must be filled out");
                 return false;
             }
             return true;
         } catch(e) {
             return false;
         }
         return false;
     }
    </script>
    
</body>
</html>