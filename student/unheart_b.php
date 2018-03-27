<?php
  $content = file_get_contents('php://input');
  $login = explode("&", $content);
  $stuUCID = "$login[0]";
  $opID = $login[1];
  
  include '../db_con.php';
  
  $flag1 = false;
  
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
  
  //Different cases for finding a student in the string of students
  $mid = "&" . $stuUCID . "&";
  $end = "&" . $stuUCID;
  $beg = $stuUCID . "&";
  $only = $stuUCID;
  if (strlen($stuInterest) >= 2)
  {
    if(strpos($stuInterest, $mid) !== false)
    {
      $stuInterestN = str_replace($mid, "&", $stuInterest);
      $flag1 = true;
    }
    else if(strpos($stuInterest, $end) !== false)
    {
      $stuInterestN = str_replace($end, "", $stuInterest);
      $flag1 = true;
    }
    else if(strpos($stuInterest, $beg) !== false)
    {
      $stuInterestN = str_replace($beg, "", $stuInterest);
      $flag1 = true;
    }
    else if(strpos($stuInterest, $only) !== false)
    {
      $stuInterestN = str_replace($only, "x", $stuInterest);
      $flag1 = true;
    }
  }
  //This code will be to remove the student from the acclist or the rejlist
  if ($flag1 == true)
  {
    $opName = "$row[1]";
    $accList = "$row[10]";
    $rejList = "$row[11]";
    
    //check if ucid is in rejected
    //if so remove ucid from rejected and update
    if (strpos($rejList, $stuUCID) !== false)
    { //If UCID in Rejected, remove it
      $rejListA = explode("&", $rejList);
      $rejLen = count($rejListA);
      for ($i = 0; $i < $rejLen; $i++)
      {
        if ($rejListA[$i] == $stuUCID)
        {
          $rejListA[$i] = "";
          if ($i == $rejLen - 1)
          {
            $rejListA[$i - 1] = str_replace("&","",$rejListA[$i - 1]);
          }
        }
        else
        { 
          if ($i != $rejLen - 1)
          {
            $rejListA[$i] = $rejListA[$i] . "&";
          }
        }
      }
      if ($rejLen > 1)
      {
        $rejList = implode("", $rejListA);
      }
      else
      {
        $rejList = "TBD";
      }
      $updR = "UPDATE Opportunities SET Rejected = '$rejList' WHERE Name = '$opName'";
      $result2 = mysqli_query($connect, $updR);
    }
    
    //check if ucid is in accepted
    //if so remove ucid from accepted and update
    if (strpos($accList, $stuUCID) !== false)
    { //If UCID in Accepted, remove it
      $accListA = explode("&", $accList);
      $accLen = count($accListA);
      for ($i = 0; $i < $accLen; $i++)
      {
        if ($accListA[$i] == $stuUCID)
        {
          $accListA[$i] = "";
          if ($i == $accLen - 1)
          {
            $accListA[$i - 1] = str_replace("&","",$accListA[$i - 1]);
          }
        }
        else
        { 
          if ($i != $accLen - 1)
          {
            $accListA[$i] = $accListA[$i] . "&";
          }
        }
      }
      if ($accLen > 1)
      {
        $accList = implode("", $accListA);
      }
      else
      {
        $accList = "TBD";
      }
      $updA = "UPDATE Opportunities SET Accepted = '$accList' WHERE Name = '$opName'";
      $result2 = mysqli_query($connect, $updA);
    }
    
    
    
  }
  
  if ($flag1 == true)
  {
    $upd = "UPDATE Opportunities SET StuInterest='$stuInterestN' WHERE OpID = '$opID'";
    $result2 = mysqli_query($connect, $upd);
    if ($result2 !== false)
    {
      echo "UNHEARTED"; //if flag1 is true
    }
    else
    {
      echo "ERROR"; //if flag1 is false
    }
  }
  else
  {
    echo "ERROR"; //if flag1 is false
  }
  //Close Connection
  mysqli_close($connect);
?>