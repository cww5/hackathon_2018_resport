<?php
  /*
    This script is to edit the faculty member's profile page.
  */

  $content = file_get_contents('php://input');
  $login = explode("&", $content);
  $user = $login[0];
  $first = $login[1];
  $last = $login[2];
  //$facID = $login[3];
  $email = $login[3];
  $field = $login[4];
  $years = $login[5];
  $office = $login[6];
  $college = $login[7];
    
  include '../db_con.php';
  
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
  if (!$connect) 
  {
    echo "DB Connection Failed";
    exit();
  }
  
  $upd = "UPDATE Faculty SET LastName='$last',FirstName='$first',College='$college',
  fieldOfExpertise='$field',yearsOfExperience='$years',Email='$email',Office='$office' WHERE ucid = '$user'";
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