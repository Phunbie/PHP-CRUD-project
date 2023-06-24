<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'fred', 'zap');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<!--
CREATE DATABASE misc CHARACTER SET=utf8mb4;
CREATE USER 'fred'@'localhost' IDENTIFIED BY 'zap';
GRANT ALL ON misc.* TO 'fred'@'localhost'; 

CREATE DATABASE misc2 CHARACTER SET=utf8mb4;
CREATE USER 'shey'@'localhost' IDENTIFIED BY 'tap';
GRANT ALL ON misc.* TO 'shey'@'localhost'; 
-->