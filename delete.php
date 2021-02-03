<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['ID']) ) {
    $sql = "DELETE FROM RBasicInformation WHERE ID = :zip";
    $stmt = $pdo->prepare($sql);

    try{
      $stmt->execute(array(':zip' => $_POST['ID']));
    } catch (PDOException $e) {
      echo $e->getmessage(); 
    } 
    
    $_SESSION['success'] = 'Record deleted';
	
	  try{
      header( 'Location: index.php' ) ;
    } catch (PDOException $e) {
       echo $e->getmessage();
    }

    return;
} 


//Make sure that ID is present
if (!isset($_GET['ID']) ) {

  $_SESSION['error'] = "Missing ID";
  header('Location: index.php');
  return;

}


$stmt = $pdo->prepare("SELECT FirstName, LastName, ID FROM RBasicInformation where ID = :xyz");

try{
    $stmt->execute(array(":xyz" => $_GET['ID']));
  } catch (PDOException $e) {
    echo $e->getmessage();
  }


$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ( $row === false ) {

  $_SESSION['error'] = 'Bad value for ID';
  header( 'Location: index.php' ) ;
  return;

}

?>
<p>Confirm: Deleting <?= htmlentities($row['FirstName'])." ".htmlentities($row['LastName']) ?></p>

<form method="post">
<input type="hidden" name="ID" value="<?= $row['ID'] ?>">
<input type="submit" value="Delete" name="delete">
<a href="index.php">Cancel</a>
</form>
