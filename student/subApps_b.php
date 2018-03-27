<?php
  $content = file_get_contents('php://input');
  $login = explode("&", $content);
  //college&opID&ucid(or none if blank)
  $college = $login[0];
  $opID = $login[1];
  $ucid = $login[2];//facucid
  $user = $login[3];
  
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
    $query = "SELECT * FROM Opportunities WHERE StuInterest LIKE '$user&%' or
    StuInterest LIKE '%&$user' or StuInterest LIKE '%&$user&%' or
    StuInterest LIKE '$user'";
    $result = mysqli_query($connect,$query);
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
    $idOP = "$row[0]";
    $myJSON->opp=$info;
    $myJSON->maxOpps=$count;
    $myJSON->id= $idOP;
    
    $queryA = "SELECT * FROM Opportunities WHERE (Accepted LIKE '$user&%' or
    Accepted LIKE '%&$user' or Accepted LIKE '%&$user&%' or
    Accepted LIKE '$user') and OpID = '$idOP'";
    $resultA = mysqli_query($connect, $queryA);
    if (mysqli_num_rows($resultA) > 0)
    {
      $myJSON->status = "Accepted";
    }
    else
    {
      $queryR = "SELECT * FROM Opportunities WHERE (Rejected LIKE '$user&%' or
      Rejected LIKE '%&$user' or Rejected LIKE '%&$user&%' or
      Rejected LIKE '$user') and OpID = '$idOP'";     
      $resultR = mysqli_query($connect, $queryR);
      if (mysqli_num_rows($resultR) > 0)
      {
        $myJSON->status = "Rejected";
      }
      else
      {
        $myJSON->status = "Pending";
      }
    }

    $facucid = $row[2];
    $result4 = mysqli_query($connect,"SELECT * FROM Faculty WHERE ucid = '$facucid'");
    $row4 = mysqli_fetch_array($result4, MYSQLI_ASSOC);
    if ($result4 !== false)
    {
      $fn4 = $row4["FirstName"] . "&";
      $ln4 = $row4["LastName"] . "&";
      $em4 = $row4["Email"] . "&";
      $fe4 = $row4["fieldOfExpertise"] . "&";
      $ye4 = $row4["yearsOfExperience"] . "&";
      $sc4 = $row4["College"] . "&";
      $of4 = $row4["Office"] . "&";
      $info4 = $fn4 . $ln4 . $em4 . $fe4 . $ye4 . $sc4 . $of4;
      $myJSON->facInfo = $info4;
    }
    else
    {
      $myJSON->facInfo = NULL;
    }
    
    $ret = json_encode($myJSON);
    echo $ret;
  }
  //college filter set, ucid not set
  else if ($college != "none" && $ucid == "none")
  {
    $query = "SELECT * FROM Opportunities WHERE College = '$college' AND (StuInterest LIKE '$user&%' or
    StuInterest LIKE '%&$user' or StuInterest LIKE '%&$user&%' or
    StuInterest LIKE '$user')";
    $result = mysqli_query($connect, $query);
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
    
    $idOP = "$row[0]";
    $myJSON->opp=$info;
    $myJSON->maxOpps=$count;
    $myJSON->id= $idOP;
    
    $queryA = "SELECT * FROM Opportunities WHERE (Accepted LIKE '$user&%' or
    Accepted LIKE '%&$user' or Accepted LIKE '%&$user&%' or
    Accepted LIKE '$user') and OpID = '$idOP'";
    $resultA = mysqli_query($connect, $queryA);
    if (mysqli_num_rows($resultA) > 0)
    {
      $myJSON->status = "Accepted";
    }
    else
    {
      $queryR = "SELECT * FROM Opportunities WHERE (Rejected LIKE '$user&%' or
      Rejected LIKE '%&$user' or Rejected LIKE '%&$user&%' or
      Rejected LIKE '$user') and OpID = '$idOP'";     
      $resultR = mysqli_query($connect, $queryR);
      if (mysqli_num_rows($resultR) > 0)
      {
        $myJSON->status = "Rejected";
      }
      else
      {
        $myJSON->status = "Pending";
      }
    }
    
    $facucid = $row[2];
    $result4 = mysqli_query($connect,"SELECT * FROM Faculty WHERE ucid = '$facucid'");
    $row4 = mysqli_fetch_array($result4, MYSQLI_ASSOC);
    if ($result4 !== false)
    {
      $fn4 = $row4["FirstName"] . "&";
      $ln4 = $row4["LastName"] . "&";
      $em4 = $row4["Email"] . "&";
      $fe4 = $row4["fieldOfExpertise"] . "&";
      $ye4 = $row4["yearsOfExperience"] . "&";
      $sc4 = $row4["College"] . "&";
      $of4 = $row4["Office"] . "&";
      $info4 = $fn4 . $ln4 . $em4 . $fe4 . $ye4 . $sc4 . $of4;
      $myJSON->facInfo = $info4;
    }
    else
    {
      $myJSON->facInfo = NULL;
    }
    
    $ret = json_encode($myJSON);
    echo $ret;
  }
  //ucid filter set, college filter not set
  else if ($college == "none" && $ucid != "none")
  {
    $query = "SELECT * FROM Opportunities WHERE FacUCID = '$ucid' AND (StuInterest LIKE '$user&%' or
    StuInterest LIKE '%&$user' or StuInterest LIKE '%&$user&%' or
    StuInterest LIKE '$user')";
    $result = mysqli_query($connect, $query);
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
    
    $idOP = "$row[0]";
    $myJSON->opp=$info;
    $myJSON->maxOpps=$count;
    $myJSON->id= $idOP;
    
    $queryA = "SELECT * FROM Opportunities WHERE (Accepted LIKE '$user&%' or
    Accepted LIKE '%&$user' or Accepted LIKE '%&$user&%' or
    Accepted LIKE '$user') and OpID = '$idOP'";
    $resultA = mysqli_query($connect, $queryA);
    if (mysqli_num_rows($resultA) > 0)
    {
      $myJSON->status = "Accepted";
    }
    else
    {
      $queryR = "SELECT * FROM Opportunities WHERE (Rejected LIKE '$user&%' or
      Rejected LIKE '%&$user' or Rejected LIKE '%&$user&%' or
      Rejected LIKE '$user') and OpID = '$idOP'";     
      $resultR = mysqli_query($connect, $queryR);
      if (mysqli_num_rows($resultR) > 0)
      {
        $myJSON->status = "Rejected";
      }
      else
      {
        $myJSON->status = "Pending";
      }
    }
    
    $facucid = $row[2];
    $result4 = mysqli_query($connect,"SELECT * FROM Faculty WHERE ucid = '$facucid'");
    $row4 = mysqli_fetch_array($result4, MYSQLI_ASSOC);
    if ($result4 !== false)
    {
      $fn4 = $row4["FirstName"] . "&";
      $ln4 = $row4["LastName"] . "&";
      $em4 = $row4["Email"] . "&";
      $fe4 = $row4["fieldOfExpertise"] . "&";
      $ye4 = $row4["yearsOfExperience"] . "&";
      $sc4 = $row4["College"] . "&";
      $of4 = $row4["Office"] . "&";
      $info4 = $fn4 . $ln4 . $em4 . $fe4 . $ye4 . $sc4 . $of4;
      $myJSON->facInfo = $info4;
    }
    else
    {
      $myJSON->facInfo = NULL;
    }
    
    $ret = json_encode($myJSON);
    echo $ret;
  }
  //ucid filter and college filter both set
  else if ($college != "none" && $ucid != "none")
  {
    $query = "SELECT * FROM Opportunities WHERE (FacUCID, College) = ('$ucid', '$college') AND (StuInterest LIKE '$user&%' or
    StuInterest LIKE '%&$user' or StuInterest LIKE '%&$user&%' or
    StuInterest LIKE '$user')";
    $result = mysqli_query($connect, $query);
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
    
    $idOP = "$row[0]";
    $myJSON->opp=$info;
    $myJSON->maxOpps=$count;
    $myJSON->id= $idOP;
    
    $queryA = "SELECT * FROM Opportunities WHERE (Accepted LIKE '$user&%' or
    Accepted LIKE '%&$user' or Accepted LIKE '%&$user&%' or
    Accepted LIKE '$user') and OpID = '$idOP'";
    $resultA = mysqli_query($connect, $queryA);
    if (mysqli_num_rows($resultA) > 0)
    {
      $myJSON->status = "Accepted";
    }
    else
    {
      $queryR = "SELECT * FROM Opportunities WHERE (Rejected LIKE '$user&%' or
      Rejected LIKE '%&$user' or Rejected LIKE '%&$user&%' or
      Rejected LIKE '$user') and OpID = '$idOP'";     
      $resultR = mysqli_query($connect, $queryR);
      if (mysqli_num_rows($resultR) > 0)
      {
        $myJSON->status = "Rejected";
      }
      else
      {
        $myJSON->status = "Pending";
      }
    }
    
    $facucid = $row[2];
    $result4 = mysqli_query($connect,"SELECT * FROM Faculty WHERE ucid = '$facucid'");
    $row4 = mysqli_fetch_array($result4, MYSQLI_ASSOC);
    if ($result4 !== false)
    {
      $fn4 = $row4["FirstName"] . "&";
      $ln4 = $row4["LastName"] . "&";
      $em4 = $row4["Email"] . "&";
      $fe4 = $row4["fieldOfExpertise"] . "&";
      $ye4 = $row4["yearsOfExperience"] . "&";
      $sc4 = $row4["College"] . "&";
      $of4 = $row4["Office"] . "&";
      $info4 = $fn4 . $ln4 . $em4 . $fe4 . $ye4 . $sc4 . $of4;
      $myJSON->facInfo = $info4;
    }
    else
    {
      $myJSON->facInfo = NULL;
    }
    
    $ret = json_encode($myJSON);
    echo $ret;
  }
  
  //Close Connection
  mysqli_close($connect);
?>