<?php
require_once "pdo.php";
session_start();


if ( isset($_POST['FirstName']) 
    && isset($_POST['email'])
    && isset($_POST['password']) 
    && isset($_POST['ID'])){

    // Data validation
    if ( strlen($_POST['FirstName']) < 1 || strlen($_POST['password']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: edit.php?ID=".$_POST['ID']);
        return;
    }

    if ( strpos($_POST['email'],'@') === false ) {
        $_SESSION['error'] = 'Bad data';
        header("Location: edit.php?ID=".$_POST['ID']);
        return;
    }
    

    $sql = ("UPDATE RBasicInformation SET FirstName = :FirstName,
            LastName = :LastName,
            Birthdate = :Birthdate,
            M_F = :M_F,
            Height = :Height,
            email = :email, password = :password
            WHERE ID = :ID");
    $stmt = $pdo->prepare($sql);


try{
    $stmt->execute(array(
        ':FirstName' => $_POST['FirstName'],
        ':LastName' => $_POST['LastName'],
        ':M_F' => $_POST['M_F'],
        ':Birthdate' => $_POST['Birthdate'],
        ':Height' => $_POST['Height'],
        ':email' => $_POST['email'],
        ':password' => $_POST['password'],
        ':ID' => $_POST['ID']));

} catch (PDOException $e) {
    echo $e->getmessage();
}


$_SESSION['success'] = 'Record updated';
header( 'Location: index.php' ) ;
return;
}

// Make sure that ID is present
if ( ! isset($_GET['ID']) ) {
  $_SESSION['error'] = "Missing ID";
  header('Location: index.php');
  return; 
}


//Select query for ID
$stmt = $pdo->prepare("SELECT * FROM RBasicInformation where ID = :xyz");
$stmt->execute(array(":xyz" => $_GET['ID']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for ID';
    header( 'Location: index.php' ) ;
    return;
}


// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$n = htmlentities($row['FirstName']);
$l = htmlentities($row['LastName']);
$b = htmlentities($row['Birthdate']);
$g = htmlentities($row['M_F']);
$h = htmlentities($row['Height']);
$e = htmlentities($row['Email']);
$p = htmlentities($row['Password']);
$ID = $row['ID'];
?>


<p>Edit User</p>
<form method="post">

    <p>FirstName:
        <input type="text" name="FirstName" value="<?= $n ?>">
    </p>

    <p>LastName:
        <input type="text" name="LastName" value="<?= $l ?>">
    </p>

    <p>Birthday:
        <input type="date" name="Birthdate" value="<?= $b ?>" min="1900-01-01" max="2019-01-01"
    ></p>

    <p>Gender:
        Male<input type="radio" name="M_F" value="M" <?php if($g == 'M')  echo ' checked="checked"';?>>
        Female<input type="radio" name="M_F" value="F" <?php if($g == 'F')  echo ' checked="checked"';?>>
    </p>

    <p>Height:
        <input type="text" name="Height" value="<?= $h ?>">
    </p>

    <p>Email:
        <input type="text" name="email" value="<?= $e ?>">
    </p>

    <p>Password:
        <input type="text" name="password" value="<?= $p ?>">
    </p>

    <input type="hidden" name="ID" value="<?= $ID ?>">

<p>
    <input type="submit" value="Update"/>
    <a href="index.php">Cancel</a>
</p>

</form>
