<?php
  $content = file_get_contents('php://input');
  $login = explode("&", $content);
  //college&opID&ucid(or none if blank)
  $college = $login[0];
  $opID = $login[1];
  $ucid = $login[2];
  
  include '../db_con.php';
  
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
  if (!$connect) 
  {
    echo "DB Connection Failed";
    exit();
  }
  
  $opIDA = (int)$opID -1;
  
  //no filters set
  if ($college == "none" && $ucid == "none")
  {
    $result = mysqli_query($connect, "SELECT * FROM Opportunities");
    $count = $result->num_rows;
    mysqli_data_seek($result, $opIDA);
    $row = mysqli_fetch_row($result);
    $name = "Name of Opportunity: " . $row[1] . "\n\n";
    $coll = "College: " . $row[8] . "\n\n";
    $title = "Title of Candidate: " . $row[4] . "\n\n";
    $num = "Number of Students Requested: " . $row[5] . "\n\n";
    $hours = "Weekly Hours: " . $row[6] . "\n\n";
    $desc = "Description: " . $row[3] . "\n\n";
    $catg = "Category: " . $row[9];
    
    $info = $name . $coll . $title . $num . $hours . $desc . $catg;
    
    $myJSON->opp=$info;
    $myJSON->maxOpps=$count;
    $myJSON->id="$row[0]";
    $ret = json_encode($myJSON);
    echo $ret;
  }
  //college filter set, ucid not set
  else if ($college != "none" && $ucid == "none")
  {
    $total = "SELECT * FROM Opportunities WHERE College = '$college'";
    $result = mysqli_query($connect, $total);
    $count = $result->num_rows;
    mysqli_data_seek($result, $opIDA);
    $row = mysqli_fetch_row($result);
    $name = "Name of Opportunity: " . $row[1] . "\n\n";
    $coll = "College: " . $row[8] . "\n\n";
    $title = "Title of Candidate: " . $row[4] . "\n\n";
    $num = "Number of Students Requested: " . $row[5] . "\n\n";
    $hours = "Weekly Hours: " . $row[6] . "\n\n";
    $desc = "Description: " . $row[3] . "\n\n";
    $catg = "Category: " . $row[9];
    
    $info = $name . $coll . $title . $num . $hours . $desc . $catg;
    
    $myJSON->opp=$info;
    $myJSON->maxOpps=$count;
    $myJSON->id="$row[0]";
    $ret = json_encode($myJSON);
    echo $ret;
  }
  //ucid filter set, college filter not set
  else if ($college == "none" && $ucid != "none")
  {
    $total = "SELECT * FROM Opportunities WHERE FacUCID = '$ucid'";
    $result = mysqli_query($connect, $total);
    $count = $result->num_rows;
    mysqli_data_seek($result, $opIDA);
    $row = mysqli_fetch_row($result);
    $name = "Name of Opportunity: " . $row[1] . "\n\n";
    $coll = "College: " . $row[8] . "\n\n";
    $title = "Title of Candidate: " . $row[4] . "\n\n";
    $num = "Number of Students Requested: " . $row[5] . "\n\n";
    $hours = "Weekly Hours: " . $row[6] . "\n\n";
    $desc = "Description: " . $row[3] . "\n\n";
    $catg = "Category: " . $row[9];
    
    $info = $name . $coll . $title . $num . $hours . $desc . $catg;
    
    $myJSON->opp=$info;
    $myJSON->maxOpps=$count;
    $myJSON->id="$row[0]";
    $ret = json_encode($myJSON);
    echo $ret;
  }
  //ucid filter and college filter both set
  else if ($college != "none" && $ucid != "none")
  {
    $total = "SELECT * FROM Opportunities WHERE (FacUCID, College) = ('$ucid', '$college')";
    $result = mysqli_query($connect, $total);
    $count = $result->num_rows;
    mysqli_data_seek($result, $opIDA);
    $row = mysqli_fetch_row($result);
    $name = "Name of Opportunity: " . $row[1] . "\n\n";
    $coll = "College: " . $row[8] . "\n\n";
    $title = "Title of Candidate: " . $row[4] . "\n\n";
    $num = "Number of Students Requested: " . $row[5] . "\n\n";
    $hours = "Weekly Hours: " . $row[6] . "\n\n";
    $desc = "Description: " . $row[3] . "\n\n";
    $catg = "Category: " . $row[9];
    
    $info = $name . $coll . $title . $num . $hours . $desc . $catg;
    
    $myJSON->opp=$info;
    $myJSON->maxOpps=$count;
    $myJSON->id="$row[0]";
    $ret = json_encode($myJSON);
    echo $ret;
  }
  //Close Connection
  mysqli_close($connect);
?>