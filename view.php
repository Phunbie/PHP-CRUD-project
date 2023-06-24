<?php
session_start(); 

require_once 'PDO.php';

if ( ! isset($_GET['profile_id']) ) {
    die('ACCESS DENIED');
  }


//$stmt4 = $pdo->prepare("SELECT year, name from Education JOIN Institution 
//ON Education.institution_id = Institution.institution_id where profile_id = :aut ORDER BY rank");
//$stmt4->execute(array(':aut' => $profile_id));
//$educations = $stmt4->fetchAll(PDO::FETCH_ASSOC);


$stmt4 = $pdo->prepare("SELECT year, name from Education JOIN Institution 
    ON Education.institution_id = Institution.institution_id where profile_id = :aut ORDER BY rank");
$stmt4->execute(array(':aut' =>  $_GET['profile_id']));
$educations = $stmt4->fetchAll(PDO::FETCH_ASSOC);


$stmt2 = $pdo->prepare("SELECT * from profile where profile_id = :aut");
$stmt2->execute(array(':aut' => $_GET['profile_id']));
$row = $stmt2->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for user id';
    header('Location: index.php');
    return;
}

$first_name = $row['first_name'];
$last_name = $row['last_name'];
$email = $row['email'];
$headline = $row['headline'];
$summary = $row['summary'];


$stmt3 = $pdo->prepare("SELECT year, description from position where profile_id = :aut ORDER BY rank");
$stmt3->execute(array(':aut' => $_GET['profile_id']));
$positions = array();
while ($row2 =$stmt3->fetch(PDO::FETCH_ASSOC)){
    $positions[] = $row2;
}


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
        <h1>Profile information</h1>
<?php
       echo '<p>First Name:'.$first_name.'</p><p>Last Name:'.$last_name.'</p><p>Email:'.$email.'</p><p>Headline:<br>'.$headline.'</p><p>Summary:<br>'.$summary.'</p><p></p>';
       //$str = json_encode($positions);
       echo '<p>Education</P>';
       echo '<ul>';
       foreach ($educations as $education) {
          echo '<li>'.$education["year"].': '.$education["name"].'</li>';
        }
       echo '</ul>';
       echo '<p>Position</P>';
       echo '<ul>';
             foreach ($positions as $position) {
                echo '<li>'.$position["year"].': '.$position["description"].'</li>';
              }
        echo '</ul>';
    ?>
        <a href="index.php">Done</a>
    </div>

</body>
</html>