<?php
  /*
  This script is for checking the login.
  We attempt to hash the passwords for increased security.
  */
  
  $content = file_get_contents('php://input');
  $urlNJIT = "https://cp4.njit.edu/cp/home/login";
  
  $login = explode("&", $content);
  $user = $login[0];
  $pass = $login[1];

  $tableName = "";
  $stuFlag = false;
  for ($i = 0; $i < 10; $i++)
  {
    $num = (string)$i;
    if (strpos($user, $num) !== false)
    {
      $stuFlag = true;
      break;
    }
  }
  if ($stuFlag == true)
  {
    $tableName = "Students";
  }
  else
  {
    $tableName = "Faculty";
  }
  
  

  include '../db_con.php';
    
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
  if (!$connect) 
  {
    echo "DB Connection Failed";
    exit();
  }
  
  $njitflag = false;
  $post_njit = "user=".$user."&pass=".$pass."&uuid=".'0xACA021';
  //Spoofing the NJIT login
  $loginN = curl_init();
  curl_setopt($loginN, CURLOPT_URL, $urlNJIT);
  curl_setopt($loginN, CURLOPT_POSTFIELDS, $post_njit);
  curl_setopt($loginN, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($loginN, CURLOPT_FOLLOWLOCATION, true);
  $outLoginN = curl_exec($loginN);
  curl_close($loginN); 
  
  //If NJIT Login is Successful
  if(strpos($outLoginN, "Successful") !== false)
  {
    $njitflag = true;
  }
  
  if ($tableName == "Faculty")
  {
    $resultI = false;
    //if we log in as our temporary teacher
    if ( ($user == "teacher" && $pass == "pass") || ($user == "faculty" && $pass == "pass"))
    {
      //Checks if the faculty is in the database
      $result = mysqli_query($connect, "SELECT * FROM Faculty WHERE ucid = '$user'"); 
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
      if($row['ucid'] == $user )//&& password_verify($pass, $row['Passwords'])) 
      {
        //If so, echo positive message
        $res = "GOODFACULTY";
        $resultI = true;
      }
      else 
      {
        //If not, insert faculty into database
        //$passH = password_hash($pass, PASSWORD_BCRYPT);
        //$ins = "INSERT INTO Faculty (ucid, Passwords) VALUES ('$user','$passH')";
        $ins = "INSERT INTO Faculty (ucid) VALUES ('$user')";
        $resultI = mysqli_query($connect, $ins);
      }
    }
    //if they log in as an actual faculty member at NJIT
    else if ($njitflag == true)
    {
      //Checks if the faculty is in the database
      $result = mysqli_query($connect, "SELECT * FROM Faculty WHERE ucid = '$user'"); 
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
      if($row['ucid'] == $user )  //&& password_verify($pass, $row['Passwords'])) 
      {
        //If so, positive message
        $res = "GOODFACULTY";
        $resultI = true;
      }
      else 
      {
        //If not, insert faculty into database
        //$passH = password_hash($pass, PASSWORD_BCRYPT);
        //$ins = "INSERT INTO Faculty (ucid, Passwords) VALUES ('$user','$passH')";
        $ins = "INSERT INTO Faculty (ucid) VALUES ('$user')";
        $resultI = mysqli_query($connect, $ins);
      }
    }
    //invalid faculty member
    else
    {
      $res = "BADFACULTY";
    }
    if ($resultI !== false) 
    {
      //If added successfully, echo positive message
      $res = "GOODFACULTY";
    } 
    else 
    {
      //If not added successfully, echo negative message
      $res = "BADFACULTY";
    }
    echo $res;
  } //end faculty if block
  
  //begin student if block
  else if ($tableName == "Students")
  {
    $resultI = false;
    if ($njitflag == true)
    {
      //Checks if the student is in the database
      $result = mysqli_query($connect, "SELECT * FROM Students WHERE ucid = '$user'"); 
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
      if($row['ucid'] == $user ) //&& password_verify($pass, $row['Passwords'])) 
      {
        //If so, echo positive message
        $res = "GOODSTUDENT";
        $resultI = true;
      }
      else 
      {
        //If not, insert student into database
        //$passH = password_hash($pass, PASSWORD_BCRYPT);
        //$ins = "INSERT INTO Students (ucid, Passwords) VALUES ('$user','$passH')";
        $ins = "INSERT INTO Students (ucid) VALUES ('$user')";
        $resultI = mysqli_query($connect, $ins);
      }
    }
    //invalid student member
    else
    {
      $res = "BADSTUDENT";
    }
    if ($resultI !== false) 
    {
      //If added successfully, echo positive message
      $res = "GOODSTUDENT";
    } 
    else 
    {
      //If not added successfully, echo negative message
      $res = "BADSTUDENT";
    }
    echo $res;
  }//end students if block

  //Close Connection
  mysqli_close($connect);
?>
