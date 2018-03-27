<?php
  //facUCID&opid
  $content = file_get_contents('php://input');
  $ucid = $content;
  
  include '../db_con.php';
  
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
  if (!$connect) 
  {
    echo "DB Connection Failed";
    exit();
  }
  
  $tStu = "";
  $result = mysqli_query($connect,"SELECT * FROM Students WHERE ucid = '$ucid'");
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  mysqli_data_seek($result, $i);
  $rowSTU = mysqli_fetch_row($result);
  
  if ($result !== false) 
  {  
    //If updated successfully, echo JSON with information
    $fn = "$rowSTU[4]" . "&";
    $ln = "$rowSTU[3]" . "&";
    $id = "$rowSTU[1]" . "&";
    $em = "$rowSTU[9]" . "&";
    $gpa = "$rowSTU[2]" . "&";
    $mj = "$rowSTU[8]" . "&";
    $co = "$rowSTU[7]" . "&";
    $ra = "$rowSTU[5]" . "&";
    $tStu = $tStu . $fn . $ln . $id . $em . $gpa . $mj . $co . $ra . $ucid;
    
    $ret->thisStudent = $tStu;
  } 
  $data = json_encode($ret);
  echo $data;
  
  //Close Connection
  mysqli_close($connect);
?>