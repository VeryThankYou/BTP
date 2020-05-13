<?php
session_start();
//This file has our connection to our database
include('config.php');

//Check if we have a session called email. This way we block users from changing the url and trying to skip login.
if(!isset($_SESSION['id'])){
  header('location: index.php');  
}

function userEmail($userid, $conn){
  $sql = "SELECT email FROM user WHERE id='$userid';";
  $result = $conn->query($sql);
  $fetch = $result;
  $row = mysqli_fetch_assoc($fetch);
  return $row['email'];
}

function displayName($userid, $conn){
  $sql = "SELECT displayname FROM user WHERE id='$userid';";
  $result = $conn->query($sql);
  $fetch = $result;
  $row = mysqli_fetch_assoc($fetch);
  return $row['displayname'];
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST['send'])){
    $msgtext = $_POST['msgtxt'];
    $myid = $_SESSION['id'];
    $receiverid = $_SESSION['msg'];
    $sql = "INSERT INTO message (text, receiver_user_id, sender_user_id) VALUES ('$msgtext', '$receiverid', '$myid');";
    $conn->query($sql);
    $email = userEmail($receiverid, $conn);
    $recname = displayName($receiverid, $conn);
    $myname = displayName($myid, $conn);
    $msg = "Hi $recname, \n\nYou have a new message on Balduvian Trading Post from $myname. They hope to hear from you soon!\n\nBest regards,\nThe Balduvian Trading Post Team";
    $msg = wordwrap($msg, 70);
    mail($email, 'New message', $msg);
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
  $myid = $_SESSION['id'];
  $friendid = $_SESSION['msg'];
  $friendname = displayName($friendid, $conn);
  echo "<div> Messages with $friendname </div>";
  $sql = "SELECT * FROM message WHERE (receiver_user_id='$myid' AND sender_user_id='$friendid') OR (receiver_user_id='$friendid' AND sender_user_id='$myid');";
  $result = $conn->query($sql);
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $sender = $row['sender_user_id'];
      $receiver = $row['receiver_user_id'];
      $msg = $row['text'];
      $datetime = $row['time'];
      $meid = $_SESSION['id'];
      if($meid == $sender){
        echo "<div class='navngiv din klasse william'>$msg <br> $datetime </div>";
      } else{
        echo "<div class='navngiv din anden klasse william'>$msg <br> $datetime </div>";
      }
    }
  }
  ?>
<form method='POST'>
<input type='text' name='msgtxt' placeholder='Write your message here...'/>
<input type='submit' name='send' value='send'/>
</form>

</body>
</html>