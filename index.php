<?php 
session_start();



require_once 'PDO.php';


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
    <h2>Resume Registry</h2>



<?php
if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}


    if ( isset($_SESSION['name']) ) {
        $stmt2 = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM profile");
        $row = $stmt2->fetch(PDO::FETCH_ASSOC);
        if($row){
            echo '<table border="1">';
            echo '<thead><tr>';
            echo '<th>Name</th>';
            echo '<th>Headline</th>';
            echo '<th>Action</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            $stm = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM profile");
            while ($row1 = $stm->fetch(PDO::FETCH_ASSOC)){
                echo '<tr><td><a href="view.php?profile_id='.$row1['profile_id'].'">'.$row1['first_name'].' '.$row1['last_name'].'</a></td><td>'.$row1['headline'].'</td>';
                echo '<td><a href="edit.php?profile_id='.$row1['profile_id'].'">Edit</a> / <a href="delete.php?profile_id='.$row1['profile_id'].'">Delete</a></td></tr>';
                }
                echo '</tbody></table>';
            }

                echo "<p><a href='add.php'>Add New Entry</a></p>";
                echo "<p><a href='logout.php'>Logout</a></p>";
    }
    else{
        echo "<P><a href='login.php'>Please log in</a></p>";
        $stmt2 = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM profile");
        $row = $stmt2->fetch(PDO::FETCH_ASSOC);
        if($row){
            echo '<table border="1">';
            echo '<thead><tr>';
            echo '<th>Name</th>';
            echo '<th>Headline</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            $stm = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM profile");
            while ($row1 = $stm->fetch(PDO::FETCH_ASSOC)){
                echo '<tr><td><a href="view.php?profile_id='.$row1['profile_id'].'">'.$row1['first_name'].' '.$row1['last_name'].'</a></td><td>'.$row1['headline'].'</td></tr>';
                }
                echo '</tbody></table>';
            }

        
    }   
    ?>
</body>
</html>

