<?php
session_start();
//This file has our connection to our database
include('config.php');

//Check if we have a session called email. This way we block users from changing the url and trying to skip login.
if(!isset($_SESSION['id'])){
  header('location: index.php');  
}

function userID($email, $conn){
  $sql = "SELECT id FROM user WHERE email='$email';";
  $result = $conn->query($sql);
  $fetch = $result;
  $row = mysqli_fetch_assoc($fetch);
  return $row['id'];
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['back'])){
        header("location:playgroup.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="styling/maincss.css">
  <title>Balduvian Trading Post</title>
</head>

<body>

  <div class="header">
    <div class="header_left">
      <form method="POST">
        <input type='submit' name='back' value='BTP'>
      </form>
    </div>

    <div class="header_right">
      <a href="index.php">

        <p>Logout</p>
      </a>
    </div>
  </div>

  <div style="clear:both;"></div>
  <?php
  $list = $_SESSION['mutuallist'];
  if($list == "want"){
      echo "<h2> This is a list of cards that you want that your playgroup have </h2>";
  } else if($list == "trade"){
      echo "<h2> This is a list of cards that you have that your playgroup want </h2>";
  }
  $playgroupid = $_SESSION['playgroup'];
  $myid = $_SESSION['id'];
  
  if($list == "want"){
    $sql = "SELECT * FROM otherslist INNER JOIN mylist ON mylist.smcid=otherslist.stcid WHERE stpgid='$playgroupid' AND stuid<>'$myid' AND sttrading>0 AND smwant>0 AND smuid='$myid';";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $exp = $row['stexp'];
            $cname = $row['stcname'];
            $uname = $row['stdname'];
            $num = $row['sttrading'];
            echo "<p> $num, $cname, $exp, $uname </p>";
        }
    }
  } else if($list == "trade"){
    $sql = "SELECT * FROM otherslist INNER JOIN mylist ON mylist.smcid=otherslist.stcid WHERE stpgid='$playgroupid' AND stuid<>'$myid' AND stwant>0 AND smtrading>0 AND smuid='$myid';";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $exp = $row['stexp'];
            $cname = $row['stcname'];
            $uname = $row['stdname'];
            $num = $row['stwant'];
            echo "<p> $num, $cname, $exp, $uname </p>";
        }
    }
  }

  ?>
<form method='POST'>
<input type='submit' name='back' value='Back'/>
</form>
</body>
</html>