<?php
require_once "pdo.php";
/*

//Printing Legend
$query = $pdo->prepare('show tables');
$query->execute();


echo ("<table border='1'>");
echo ("<th>TABLE NAMES");
while($rows = $query->fetch(PDO::FETCH_ASSOC)){
    echo ("<tr>"); 
    foreach($rows as $value){
        echo ("<td>");
        echo "<pre>"; 
        echo $value;
        echo "</pre>";
        echo ("</td>");
     }
     echo ("</tr>");
}
echo("</table"); 
*/



 //Creating HTML forms by iterating through database, matching the datatypes of each column to print associated input form
 $query=$pdo->query("SELECT DATA_TYPE, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME= 'RBasicInformation'");            

 echo "<form action='' method='post'> \n\n\n";
 echo "<pre>";

 while($row=$query->fetch(PDO::FETCH_ASSOC)) 
 {    
     //echo '<input type=" '. $row[DATA_TYPE]. ' " name=" '. $row[COLUMN_NAME]. ' " required placeholder=" '. $row[COLUMN_NAME]. ' "><br><br>';
     
         switch ($row[DATA_TYPE]) {
             
             //First and Last name
             case "text":
             echo $row[COLUMN_NAME];

             if($row[COLUMN_NAME] == "FirstName"){
                 echo '<input type="text" name="FirstName"><br><br>';
                 
             }


             if($row[COLUMN_NAME] == "LastName"){
                 echo '<input type="text" name="LastName"><br><br>';
             }
             break;


             //Birthday
             case "date":
             echo $row[COLUMN_NAME];                                         
             echo '<input type="date" name="Birthdate" min="1900-01-01" max="2019-01-01"><br><br>';
             break;


             //Gender
             case "char":
             echo "Male", '<input type="radio" name="M_F" value="M" >';
             echo "Female", '<input type="radio" name="M_F" value="F"><br><br>';
             break;

             
             //Height
             case "float":
                echo $row[COLUMN_NAME];
                echo '<input type="number" name="feet" min="1" max="7">';
                echo '<input type="number" name="inches" min="0" max="11"><br><br>';
                
                //Append Feet+inches into Height
                if(!empty($_POST[feet]) && !empty($_POST[inches]) ){
                //echo gettype($_POST[Height]);
                $_POST[Height]="$_POST[feet].$_POST[inches]";
                }
                elseif(!empty($_POST[feet])){
                    $_POST[Height]=$_POST[feet];
                }
                else{
                    $_POST[Height]=NULL;
                }
                //echo $_POST[Height];
                //echo gettype($_POST[Height]);
             break;            


             //Email and Password
             case "varchar":
             
             if($row[COLUMN_NAME] == "Email"){
                 echo $row[COLUMN_NAME];
                 echo '<input type="text" name="Email"><br><br>';
             }


             if($row[COLUMN_NAME] == "Password"){
                 echo $row[COLUMN_NAME];
                 echo '<input type="password" name="Password"><br><br>';
             }
             break;
             
         }                     
 }
 


 echo "</pre>";
 echo '<input type="submit" value="Search">';
 echo '<a href="index.php">Cancel</a>';
 echo '</form>';      

//Select Query With Positional Placeholders
try{
    $stmt=$pdo->prepare("SELECT * FROM RBasicInformation 
    Where FirstName LIKE :FirstName 
    AND LastName LIKE :LastName
    AND Birthdate LIKE :Birthdate
    AND M_F LIKE :M_F
    AND Height LIKE :Height 
    AND Email LIKE :Email 
    AND Password LIKE :Password");
    } catch (PDOException $e) {
        echo $e->getmessage();
    }

//Added wildcard % to Post variables
try {
    $stmt->execute(array(
        ':FirstName' => "$_POST[FirstName]%",
        ':LastName' => "$_POST[LastName]%",  
        ':Birthdate' => "$_POST[Birthdate]%",
        ':M_F' => "$_POST[M_F]%",
        ':Height' => "$_POST[Height]%",  
        ':Email' => "$_POST[Email]%",
        ':Password' => "$_POST[Password]%"
    ));
    
    } catch (PDOException $e) {
        echo $e->getmessage();
    } 

    
//Print Database Fields**************************************************
//If no above select queries are prepared, table does not print, along with the SQL test form below
echo ("<table border='1'>");
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                
    echo ("<tr>");
        foreach($row as $value){   
            echo ("<td>");
            echo "<pre>";
            echo $value;
            echo "</pre>";
            echo ("</td>");
        }
    echo("</tr>");     
}




//SQL TESTING
echo "<form method='post'> \n\n\n";
echo '<input type="text" name="sql">';
echo '<input type="submit" value="SQL">';
echo '</form>';   

//Use !empty() instead of isset, as $_POST inputs are always set
if(!empty($_POST[sql])){
    echo $_POST[sql];
    $sql=$_POST[sql];
    $pdo->exec($sql);
}



//INSERT INTO RBasicInformation (FirstName) VALUES ('Paul')






?>


