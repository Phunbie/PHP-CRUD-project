<?php 

require_once 'PDO.php';


$stmt = $pdo->prepare('SELECT name FROM Institution WHERE name LIKE :prefix');
$stmt->execute(array( ':prefix' => $_REQUEST['term']."%"));
$retval = array();
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
  $retval[] = $row['name'];
}
header('Content-Type: application/json; charset=utf-8');
echo(json_encode($retval, JSON_PRETTY_PRINT));

  ?>