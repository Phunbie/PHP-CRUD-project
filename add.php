
<?php 
session_start();

if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
  }

require_once 'PDO.php';

function validateEdu() {
    for($i=1; $i<=9; $i++) {
      if ( ! isset($_POST['eduyear'.$i]) ) continue;
      if ( ! isset($_POST['school'.$i]) ) continue;
  
      $year = $_POST['eduyear'.$i];
      $desc = $_POST['school'.$i];
  
      if ( strlen($year) == 0 || strlen($desc) == 0 ) {
        return "All fields are required";
      }
  
      if ( ! is_numeric($year) ) {
        return "Position year must be numeric";
      }
    }
    return true;
  }

function validatePos() {
    for($i=1; $i<=9; $i++) {
      if ( ! isset($_POST['year'.$i]) ) continue;
      if ( ! isset($_POST['desc'.$i]) ) continue;
  
      $year = $_POST['year'.$i];
      $desc = $_POST['desc'.$i];
  
      if ( strlen($year) == 0 || strlen($desc) == 0 ) {
        return "All fields are required";
      }
  
      if ( ! is_numeric($year) ) {
        return "Position year must be numeric";
      }
    }
    return true;
  }



if ( isset($_POST['Cancel']) ) {
    //$_SESSION['Cancel'] = $_POST['Cancel'];
    header('Location: index.php');
    return;}
 



if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) )  {
        $valid = validatePos();
        $valid2 = validateEdu();
        if ($_POST['first_name']==''||$_POST['last_name']==''||$_POST['email']==''||$_POST['headline']==''||$_POST['summary']=='') {
            $_SESSION['failure'] = "All fields are required"; 
               header("Location: add.php");
               return;
    }

       elseif($valid !== true){
        $_SESSION['failure'] = $valid; 
        header("Location: add.php");
        return;
       }
       elseif($valid2 !== true){
        $_SESSION['failure'] = $valid; 
        header("Location: edit.php?profile_id=".$profile_id);
        return;
       }
   else  {
//
    $stmt = $pdo->prepare('INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) VALUES ( :ui, :fn, :ln, :em, :hl, :sm)');

    $stmt->execute(array(
        ':ui' => $_SESSION['user_id'],
        ':fn' => htmlentities($_POST['first_name']),
        ':ln' => htmlentities($_POST['last_name']),
        ':em' => htmlentities($_POST['email']),
        ':hl' => htmlentities($_POST['headline']),
        ':sm' => htmlentities($_POST['summary']),
    ));

    $profile_id = $pdo->lastInsertId();

    $rank = 1;
    for($i=1; $i<=9; $i++) {
      if ( ! isset($_POST['year'.$i]) ) continue;
      if ( ! isset($_POST['desc'.$i]) ) continue;
    
      $year = $_POST['year'.$i];
      $desc = $_POST['desc'.$i];
      $stmt = $pdo->prepare('INSERT INTO Position
        (profile_id, rank, year, description)
        VALUES ( :pid, :rank, :year, :desc)');
    
      $stmt->execute(array(
      ':pid' => $profile_id,
      ':rank' => $rank,
      ':year' => $year,
      ':desc' => $desc)
      );
    
      $rank++;
    }

    // insert the position entries
    $rank2 = 1;
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['eduyear'.$i]) ) continue;
        if ( ! isset($_POST['school'.$i]) ) continue;
        $year = $_POST['eduyear'.$i];
        $school = $_POST['school'.$i];
        //lookup the school if in database
        $institution_id = false;
        $stmt = $pdo->prepare('SELECT institution_id FROM Institution WHERE name = :nam');
        $stmt->execute(array(':nam' => $school));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row !== false) $institution_id = $row['institution_id'];
        //If there was no institution in the database, insert it
        if ($institution_id === false) {
            $stmt = $pdo->prepare('INSERT INTO Institution (name) VALUES (:nam)');
            $stmt->execute(array(':nam' => $school));
            $institution_id = $pdo->lastInsertId();
        }

        $stmt = $pdo->prepare('INSERT INTO Education (profile_id, rank, year, institution_id) VALUES (:pid, :rank, :year, :iid)');
        $stmt->execute(array(':pid' => $profile_id,'rank' => $rank2,':year' => $year,'iid' => $institution_id));

        $rank2++;
    }

    $_SESSION['success'] = "Record added";
    header("Location: index.php");
    return; 
   }
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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css"> 
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

    <title>Oluwafunbi Adeneye</title>
</head>
<body>
    <?php echo('<h1>Adding Profile for '.htmlentities($_SESSION['name']).'</h1>');
    if ( isset($_SESSION['failure']) ) {
        echo('<p style="color: red;">'.htmlentities($_SESSION['failure'])."</p>\n");
        unset($_SESSION['failure']);
    }  
    ?> 
    <div>
    <form method="post">
        <p>First Name:
        <input type="text" name="first_name"  size="40"></p>
        <p>Last Name:
        <input type="text" name="last_name"  size="40"></p>
        <p>Email:
        <input type="text" name="email"  size="10"></p>
        <p>Headline:
        <input type="text" name="headline"  size="10"></p>
        <p>Summary:<br>
        <textarea name="summary" rows="8" cols="80"></textarea></p>
        <p>
        Education: <input type="submit" id="addEdu" value="+">
        <div id="education_fields"></div>
        </p>
        <p>
        Position: <input type="submit" id="addPos" value="+">
        <div id="position_fields"></div>
        </p>
        <p>
            <input type="submit" value="Add">
            <input type="submit" name="Cancel" value="Cancel">
        </p>
    </form>
    <p>
    </p>
    </div>  
    <script type="text/javascript">
    countPos = 0;
    countEdu = 0;
    term = '';
    $(document).ready(function(){
        window.console && console.log('Document ready called');
        $('#addPos').click(function(event){
            event.preventDefault();
            if (countPos >= 9) {
                alert("Maximum of nine position entries exceeded");
                return;
            }
            countPos++;
            window.console && console.log("Adding position "+countPos);
            $("#position_fields").append(
                '<div id="position'+countPos+'">\
                <p>Year: <input type="text" name="year'+countPos+'" value="">\
                <input type="button" value="-" \
                    onclick="$(\'#position'+countPos+'\').remove();return false;"></p>\
                <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>'
            );

        })

        $('#addEdu').click(function(event){
            event.preventDefault();
            if (countEdu >= 9) {
                alert("Maximum of nine education entries exceeded");
                return;
            }
            countEdu++;
            window.console && console.log("Adding Education "+countEdu);
            $("#education_fields").append(
                '<div id="education'+countEdu+'">\
                <p>Year: <input type="text" name="eduyear'+countEdu+'" value="">\
                <input type="button" value="-" \
                    onclick="$(\'#education'+countEdu+'\').remove();return false;"></p>\
                School: <input onchange="term = this.value" type="text" size="80" name="school'+countEdu+'" class="school" value="" />\
            </div>'
            );

        })

        $('.school').autocomplete({ source: `school.php?term="${term}"` });
    }
)
    </script>
</body>
</html>
