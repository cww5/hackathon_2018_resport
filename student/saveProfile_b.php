<?php
  /*
    This script is to edit the student member's profile page.
  */

  $content = file_get_contents('php://input');
  $login = explode("&", $content);
  $user = $login[0];
  $first = $login[1];
  $last = $login[2];
  $stuID = $login[3];
  $email = $login[4];
  $major = $login[5];
  $gpa = $login[6];
  $class = $login[7];
  $college = $login[8];
  
  include '../db_con.php';
  
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
  if (!$connect) 
  {
    echo "DB Connection Failed";
    exit();
  }
  
  $upd = "UPDATE Students SET StuID = '$stuID',StuGPA='$gpa',LastName='$last',FirstName='$first',Class='$class',College='$college',Major='$major',Email='$email' WHERE ucid = '$user'";
  
  $result = mysqli_query($connect,$upd);
        
  if ($result !== false) 
  {
    //If updated successfully, echo positive message
    echo "Profile Updated";
  } 
  else 
  {
    //If not updated successfully, echo negative message
    echo "Profile Not Updated";
  }
?>