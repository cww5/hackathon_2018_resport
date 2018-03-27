<?php
  $content = file_get_contents('php://input');
  $login = explode("&", $content);
  $status = $login[0]; //accept or reject
  $stuUCID = $login[1];
  $opName = $login[2];
  
  include '../db_con.php';
  
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
  if (!$connect) 
  {
    echo "DB Connection Failed";
    exit();
  }
  $query0 = "SELECT * FROM Opportunities WHERE Name = '$opName'";
  
  $result = mysqli_query($connect, $query0);
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);;
  
  $accList = $row["Accepted"];
  $rejList = $row["Rejected"];
  
  if ($status == 'accept')
  {
    $accepted = false;
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
    
    //Either way, add student to Accepted
    //if they weren't accepted already
    if ($accList == "TBD") //it is still TBD
    {
      $accList = $stuUCID;
      $accepted = true;
    }
    else
    {
      if(strpos($accList, $stuUCID) === false)
      { //UCID not found in accepted list
        $accList = $accList . "&" . $stuUCID;
        $accepted = true;
      }
      else
      { //UCID found in accepted list
        echo "ALREADY ACCEPTED";
      }
    }
    if ($accepted == true)
    {
      $upd = "UPDATE Opportunities SET Accepted = '$accList' WHERE Name = '$opName'";
      $result2 = mysqli_query($connect, $upd);
      if ($result2 !== false)
      {
        echo "ACCEPTED"; //if accepted is true
      }
      else
      {
        echo "ERROR"; //if accepted is false
      }
    }
  }
  
  
  
  if ($status == 'reject')
  {
    $rejected = false;
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
    
    //Either way, add student to Rejected
    //if they weren't rejected already
    if ($rejList == "TBD") //it is still TBD
    {
      $rejList = $stuUCID;
      $rejected = true;
    }
    else
    {
      if(strpos($rejList, $stuUCID) === false)
      { //UCID not found in rejected list
        $rejList = $rejList . "&" . $stuUCID;
        $rejected = true;
      }
      else
      { //UCID found in rejected list
        echo "ALREADY REJECTED";
      }
    }
    if ($rejected == true)
    {
      $upd = "UPDATE Opportunities SET Rejected = '$rejList' WHERE Name = '$opName'";
      $result2 = mysqli_query($connect, $upd);
      if ($result2 !== false)
      {
        echo "REJECTED"; //if rejected is true
      }
      else
      {
        echo "ERROR"; //if rejected is false
      }
    }
  }
  
  
  
  
  //Close Connection
  mysqli_close($connect);
?>