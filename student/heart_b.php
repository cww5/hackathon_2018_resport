<?php
  $content = file_get_contents('php://input');
  $login = explode("&", $content);
  $stuID = "$login[0]";
  $opID = $login[1];
  
  include '../db_con.php';
  
  $flag = false;
  
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
  if (!$connect) 
  {
    echo "DB Connection Failed";
    exit();
  }
  $opIDA = (int)$opID - 1;
  $result = mysqli_query($connect, "SELECT * FROM Opportunities");
  mysqli_data_seek($result, $opIDA);
  $row = mysqli_fetch_row($result);
  $stuInterest = "$row[7]";
  
  if (strlen($stuInterest) <= 2)
  {
    $stuInterestN = $stuID;
    $flag = true;
  }
  else
  {
    if(strpos($stuInterest, $stuID) === false)
    {
      $stuInterestN = $stuInterest . "&" . $stuID;
      $flag = true;
    }
    else
    {
      echo "ALREADY HEARTED"; //if flag is still false
    }
  }
  if ($flag == true)
  {
    $upd = "UPDATE Opportunities SET StuInterest='$stuInterestN' WHERE OpID = '$opID'";
    $result2 = mysqli_query($connect, $upd);
    if ($result2 !== false)
    {
      echo "HEARTED OPP"; //if flag is true
    }
    else
    {
      echo "ERROR TRY AGAIN"; //if flag is false
    }
  }
  //Close Connection
  mysqli_close($connect);
?>