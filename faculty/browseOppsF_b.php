<?php
  //facUCID&opid
  $content = file_get_contents('php://input');
  $login = explode("&", $content);
  $facUCID = $login[0];
  $opID = $login[1];
  
  include '../db_con.php';
  
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
  if (!$connect) 
  {
    echo "DB Connection Failed";
    exit();
  }
    
  $opIDA = (int)$opID - 1;
  
  $total = "SELECT * FROM Opportunities WHERE FacUCID = '$facUCID'";
  $result = mysqli_query($connect, $total);
  $count = $result->num_rows;
  
  mysqli_data_seek($result, $opIDA);
  $row = mysqli_fetch_row($result);
  //send name and list of students
    
  $myJSON->opp=$row[0];
  $myJSON->name=$row[1];
  
  $students = "$row[7]";
  if ($students == "x")
  {
    $myJSON->students = $students;
  }
  else
  {
    $myJSON->students = $students;
    $stuLst = explode("&", $students);
    $numStu = count($stuLst);
    $end = "";
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
        $myJSON->firstStudent = $firstStu;
      }
    }
    $myJSON->studentsString = $end;
  }   
  $myJSON->maxOpps=$count;
  $ret = json_encode($myJSON);
  echo $ret;

  //Close Connection
  mysqli_close($connect);
?>