<?php
require_once "pdo.php";
session_start();


if ( isset($_POST['FirstName']) 
    && isset($_POST['LastName']) 
    && isset($_POST['Birthdate'])
    && isset($_POST['M_F'])
    && isset($_POST['Height'])
    && isset($_POST['Email'])
    && isset($_POST['Password'])) {

    // Data validation

    if ( strpos($_POST['Email'],'@') === false ) {
        $_SESSION['error'] = 'Bad data';
        header("Location: add.php");
        return;
    }

    $sql = "INSERT INTO RBasicInformation (FirstName, LastName, Birthdate, M_F, Height, Email, Password)
              VALUES (:FirstName, :LastName, :Birthdate, :M_F, :Height, :email, :password)";
    $stmt = $pdo->prepare($sql);
    
	try{
    $stmt->execute(array(
        ':FirstName' => $_POST['FirstName'],
        ':LastName' => $_POST['LastName'],
        ':Birthdate' => $_POST['Birthdate'],
        ':M_F' => $_POST['M_F'],
        ':Height' => $_POST['Height'],
        ':email' => $_POST['Email'],
        ':password' => $_POST['Password']));
    } catch (PDOException $e) {
        echo $e->getmessage();
    }   
	
	
    $_SESSION['success'] = 'Record Added';
    
   
    try{
        header( 'Location: index.php' ) ;
      } catch (PDOException $e) {
         echo $e->getmessage();
      }
    
    return;
}


// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

//Retrieving Data types AND Column names from each column in table
$query=$pdo->query("SELECT DATA_TYPE, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME= 'RBasicInformation'");            


//Creating HTML forms by iterating through table, matching the datatypes of each column to print associated input form
echo "<form action='index.php' method='post'> \n\n\n";
echo "<pre>";

while($row=$query->fetch(PDO::FETCH_ASSOC)) 
{    
    //echo '<input type=" '. $row[DATA_TYPE]. ' " name=" '. $row[COLUMN_NAME]. ' " required placeholder=" '. $row[COLUMN_NAME]. ' "><br><br>';
    
        switch ($row[DATA_TYPE]) {
            
            //First and Last name
            case "text":
            
                echo $row[COLUMN_NAME];
                echo '<input type="text" name="'.$row[COLUMN_NAME].'" required placeholder=" '. $row[COLUMN_NAME]. ' "><br><br>';                   
            
            break;


            //Birthday
            case "date":
            
                echo $row[COLUMN_NAME];                                         
                echo '<input type="date" name="'.$row[COLUMN_NAME].'" min="1900-01-01" max="2019-01-01" required><br><br>';
            
            break;


            //Gender
            case "char":

                if($row[COLUMN_NAME] == "M_F"){
                    echo "Male", '<input type="radio" name="'.$row[COLUMN_NAME].'" value="M" required>';
                    echo "Female", '<input type="radio" name="'.$row[COLUMN_NAME].'" value="F"><br><br>';
                }

                //We're using chars as input type radio, need to explicitly state if/else conditions

            break;

            
            //HTML doesn't have float types, need to use "number" and state range.
            case "float":
            
                echo $row[COLUMN_NAME];

                if($row[COLUMN_NAME] == "Height"){
                    
                    echo '<input type="number" name="feet" required placeholder="Feet" min="1" max="7">';
                    echo '<input type="number" name="inches" required placeholder="Inches" min="0" max="11"><br><br>';
            
                    //Append Feet+inches into Height. PHP isn't strongly typed, and $_POST is just a string anyways
                    $_POST[Height]="$_POST[feet].$_POST[inches]";
                }

                else{
                    echo '<input type="number" name="'.$row[COLUMN_NAME].'" required placeholder=" '. $row[COLUMN_NAME]. ' "><br><br>';  
                }

            break;            


            //Email and Password
            case "varchar":
            
               if($row[COLUMN_NAME] == "Email"){
                    echo $row[COLUMN_NAME];
                    echo '<input type="email" name="'.$row[COLUMN_NAME].'" required placeholder=" '. $row[COLUMN_NAME]. ' "><br><br>';
                }

                elseif($row[COLUMN_NAME] == "Password"){
                    echo $row[COLUMN_NAME];
                    echo '<input type="password" name="'.$row[COLUMN_NAME].'" required placeholder=" '. $row[COLUMN_NAME]. ' "><br><br>';
                }

                else{
                    echo $row[COLUMN_NAME];
                    echo '<input type="text" name="'.$row[COLUMN_NAME].'" required placeholder=" '. $row[COLUMN_NAME]. ' "><br><br>'; 
                }
            break;
            
        }                     
}

echo "</pre>";
echo '<input type="submit">';
echo '</form>';      


?>

