<?php
 
$pdo = new PDO('mysql:host=mysql.resume.dreamhosters.com;port=3306;dbname=resume_database','resume_mysql', 'mysql123');

// See the "errors" folder for details...
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


?>
