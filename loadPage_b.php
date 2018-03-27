<?php
  /*
    This script is to load a faculty/student profile page.
  */

  //"facID&tableCheck"
  $content = file_get_contents('php://input');
  $login = explode("&", $content);
  $user = $login[0];
  $tableCheck = $login[1];
  
  include 'db_con.php';
  
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
  if (!$connect) 
  {
    echo "DB Connection Failed";
    exit();
  }
  
  if ($tableCheck == 'Student')
  {
    $result = mysqli_query($connect,"SELECT * FROM Students WHERE ucid = '$user'");
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($result !== false) 
    {  
      //If updated successfully, echo JSON with information
      
      $fn = $row["FirstName"];
      $ln = $row["LastName"];
      $id = $row["StuID"];
      $em = $row["Email"];
      $mj = $row["Major"];
      $gpa = $row["StuGPA"];
      $cs = $row["Class"];
      $sc = $row["College"];
      
      $ret->first = $fn;
      $ret->last = $ln;
      $ret->stuID = $id;
      $ret->email = $em;
      $ret->major = $mj;
      $ret->gpa = $gpa;
      $ret->classStanding = $cs;
      $ret->college = $sc;
    } 
    $result2 = mysqli_query($connect,"SELECT * FROM Opportunities");
    $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
    if ($result2 !== false) 
    {
      $name = "Name of Opportunity: " . $row2['Name'] . "\n\n";
      $coll = "College: " . $row2['College'] . "\n\n";
      $title = "Title of Candidate: " . $row2['StudentRole'] . "\n\n";
      $num = "Number of Students Requested: " . $row2['NumOfStudents'] . "\n\n";
      $hours = "Weekly Hours: " . $row2['HoursWeekly'] . "\n\n";
      $desc = "Description: " . $row2['Description'] . "\n\n";
      $catg = "Category: " . $row2['Category'];
      $id = $row2['OpID'];
      $id = (string)$id;
      
      $info = $name . $coll . $title . $num . $hours . $desc . $catg;
      $ret->opp = $info;
      $ret->id = $id;
    }
    
    $query3 = "SELECT * FROM Opportunities WHERE StuInterest LIKE '$user&%' or
    StuInterest LIKE '%&$user' or StuInterest LIKE '%&$user&%' or
    StuInterest LIKE '$user'";
    $result3 = mysqli_query($connect, $query3);
    $row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC);
    if ($result3 !== false)
    {
      
      $name3 = "Name of Opportunity: " . $row3['Name'] . "\n\n";
      $coll3 = "College: " . $row3['College'] . "\n\n";
      $title3 = "Title of Candidate: " . $row3['StudentRole'] . "\n\n";
      $num3 = "Number of Students Requested: " . $row3['NumOfStudents'] . "\n\n";
      $hours3 = "Weekly Hours: " . $row3['HoursWeekly'] . "\n\n";
      $desc3 = "Description: " . $row3['Description'] . "\n\n";
      $catg3 = "Category: " . $row3['Category'];
      $id3 = $row3['OpID'];
      $id3 = (string)$id3;
      
      $queryA = "SELECT * FROM Opportunities WHERE (Accepted LIKE '$user&%' or
      Accepted LIKE '%&$user' or Accepted LIKE '%&$user&%' or
      Accepted LIKE '$user') and OpID = '$id3'";
      $resultA = mysqli_query($connect, $queryA);
      if (mysqli_num_rows($resultA) > 0)
      {
        $ret->status = "Accepted";
      }
      else
      {
        $queryR = "SELECT * FROM Opportunities WHERE (Rejected LIKE '$user&%' or
        Rejected LIKE '%&$user' or Rejected LIKE '%&$user&%' or
        Rejected LIKE '$user') and OpID = '$id3'";     
        $resultR = mysqli_query($connect, $queryR);
        if (mysqli_num_rows($resultR) > 0)
        {
          $ret->status = "Rejected";
        }
        else
        {
          $ret->status = "Pending";
        }
      }
      $info3 = $name3 . $coll3 . $title3 . $num3 . $hours3 . $desc3 . $catg3;
      $ret->firstOpp = $info3;
      $ret->firstID = $id3;
      
      $facucid = $row3['FacUCID'];
    }
    else
    {
      $ret->firstOpp = NULL;
      $ret->firstID = NULL;
    }
    //FacUCID
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
      $ret->facInfo = $info4;
    }
    else
    {
      $ret->facInfo = NULL;
    }    
    
    $data = json_encode($ret);
    echo $data;
  }
  
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
          
  else if ($tableCheck == 'Faculty')
  {
    $result = mysqli_query($connect,"SELECT * FROM Faculty WHERE ucid = '$user'");
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $result2 = mysqli_query($connect,"SELECT * FROM Opportunities WHERE FacUCID = '$user'");
    $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC); 
         
    if ($result !== false && $result2 !== false) 
    {  
      //If updated successfully, echo JSON with information
      
      $fn = $row["FirstName"];
      $ln = $row["LastName"];
      //$id = $row["FacID"];
      $sc = $row["College"];
      $fe = $row["fieldOfExpertise"];
      $ye = $row["yearsOfExperience"];
      $em = $row["Email"];
      $of = $row["Office"];
      
      $ret->first = $fn;
      $ret->last = $ln;
      //$ret->facID = $id;
      $ret->college = $sc;
      $ret->field = $fe;
      $ret->experience = $ye;
      $ret->email = $em;
      $ret->office = $of;
      
      $name = $row2["Name"];
      $students = $row2["StuInterest"]; //ucid&ucid....
      $ret->opName = $name;
      $ret->students = $students;
      
      $stuLst = explode("&", $students);
      $numStu = count($stuLst);
      $end = "";
      $firstStu = "";
      for ($i = 0; $i < $numStu; $i++)
      {
        $curUCID = $stuLst[$i];
        $resultSTU = mysqli_query($connect, "SELECT * FROM Students WHERE ucid = '$curUCID'");
        mysqli_data_seek($resultSTU, $i);
        $rowSTU = mysqli_fetch_row($resultSTU);
        $first = "$rowSTU[4]";
        $last = "$rowSTU[3]";
        $class = "$rowSTU[5]";
        $num = (string)($i + 1);
        $end = $end . $num . ") " . $first . " " . $last . " - " . $class . "\n--------------------------------------------------\n";
        if ($i == 0)
        {
          $fnFS = "$rowSTU[4]" . "&";
          $lnFS = "$rowSTU[3]" . "&";
          $idFS = "$rowSTU[1]" . "&";
          $emFS = "$rowSTU[9]" . "&";
          $gpaFS = "$rowSTU[2]" . "&";
          $mjFS = "$rowSTU[8]" . "&";
          $coFS = "$rowSTU[7]" . "&";
          $raFS = "$rowSTU[5]" . "&";
          $ucidFS = $curUCID;
          $firstStu = $firstStu . $fnFS . $lnFS . $idFS . $emFS . $gpaFS . $mjFS . $coFS . $raFS . $ucidFS;
          $ret->firstStudent = $firstStu;
        }
      }
      $ret->studentsString = $end;
      
      $data = json_encode($ret);
      echo $data;
    }
  }
  //Close Connection
  mysqli_close($connect);
?>