<?php
session_start(); 

if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
  }


require_once 'PDO.php';

//$_SESSION['name'] = $_POST['email'];
//header("Location: view.php");
//return; 

if ( isset($_POST['delete']) && isset($_POST['profile_id']))  {
    //$sql = "DELETE FROM autos WHERE autos_id = :aot";
    //$stmt = $pdo->prepare($sql);
    //$stmt->execute(array(":aot" => $_POST['autos_id']));
    $stmt = $pdo->prepare("DELETE FROM profile WHERE profile_id = :aot");
    $stmt->execute(array(':aot' => $_POST['profile_id']));
    $_SESSION['success'] = 'Record deleted';
    header('Location: index.php');
    return;}

if ( isset($_POST['profile_id']) ) {
    //$_SESSION['Cancel'] = $_POST['Cancel'];
    header('Location: index.php');
    return;}
 
$profile_id = $_GET['profile_id'];

//SELECT first_name, last_name, headline FROM profile
$stmt2 = $pdo->prepare("SELECT profile_id, first_name, last_name, headline FROM profile where profile_id = :aut");
$stmt2->execute(array(":aut" => $profile_id));
$row = $stmt2->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for user id';
    header('Location: index.php');
    return;
}

$name = $row['first_name'].' '.$row['last_name'];
$profile_id = $row['profile_id'];

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
<div class="container">

<?php echo '<p>Confirm: Deleting '.$name.' profile</p>'; ?>
<form method="post">
    <input type="hidden" name="profile_id" value="<?= $profile_id ?>"> 
    <input type="submit" value="Delete" name="delete">
    <a href="index.php">Cancel</a>
</form>
</div>
</body>
</html>