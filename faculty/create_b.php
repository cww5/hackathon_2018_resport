<?php
  /* This script is for creating the research opportunities.*/
  
  $content = file_get_contents('php://input');

  $login = explode("&", $content);
  $facUCID = $login[0];
  $oppName = $login[1];
  $college = $login[2];
  $stuRole = $login[3];
  $numStud = $login[4];
  $weeklyH = $login[5];
  $desc = $login[6];
  $catg = $login[7];
  
  include '../db_con.php';
  
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
  if (!$connect) 
  {
    echo "DB Connection Failed";
    exit();
  }
  
  $result = mysqli_query($connect, "SELECT COUNT(*) FROM Opportunities");
  $row = mysqli_fetch_row($result);
  $count = $row[0];
  $new = $count + 1;
  $new = (string)$new;
  
  $ins = "INSERT INTO Opportunities(FacUCID, Name, College, StudentRole, NumOfStudents, HoursWeekly, Description, Category, OpID) VALUES ('$facUCID','$oppName','$college','$stuRole','$numStud','$weeklyH','$desc','$catg','$new')";
  $resultI = mysqli_query($connect, $ins);
  
  
  if ($resultI !== false) 
  {
    //If added successfully, echo positive message
    echo "Insert Successful";
  } 
  else 
  {
    //If not added successfully, echo negative message
    echo "Insert Unsuccessful";
  }
  //Close Connection
  mysqli_close($connect);
?>