<?php
  //facid&opid
  $content = file_get_contents('php://input');
  $login = explode("&", $content);
  $facID = $login[0];
  $opID = $login[1];
  
  include '../db_con.php';
  
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
  if (!$connect) 
  {
    echo "DB Connection Failed";
    exit();
  }
  $opIDA = (int)$opID - 1;
  
  $total = "SELECT Description FROM Opportunities WHERE FacID = '$facID'";
  $result = mysqli_query($connect, $total);
  $count = $result->num_rows;
  
  mysqli_data_seek($result, $opIDA);
  $row = mysqli_fetch_row($result);
  
  $myJSON->opp=$row[0];
  $myJSON->maxOpps=$count;
  $ret = json_encode($myJSON);
  echo $ret;

  //Close Connection
  mysqli_close($connect);
?>