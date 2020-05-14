<?php
session_start();
include('config.php');
//This file has our connection to our database

if(!isset($_SESSION['id'])){
  header('location: index.php');
  //Check if we have a session variable called id. This way we block users from changing the url and trying to skip login. 
}

if(!isset($_SESSION['msg'])){
  header('location: main.php');  
  //Check if we have a session variable called msg. This way we block users from changing the url, and this page doesn't work without that session variable.
}

function userEmail($userid, $conn){
  //Defines the function with two parameters
  $sql = "SELECT email FROM user WHERE id='$userid';";
  $result = $conn->query($sql);
  $row = mysqli_fetch_assoc($result);
  //Uses an SQL-statement to get the email of the user with id equal to the given parameter
  return $row['email'];
  //Returns the email
}

function displayName($userid, $conn){
  //Defines the function with two parameters
  $sql = "SELECT displayname FROM user WHERE id='$userid';";
  $result = $conn->query($sql);
  $row = mysqli_fetch_assoc($result);
  //Uses an SQL-statement to get the displayname of the user with id equal to the given parameter
  return $row['displayname'];
  //Returns the displayname
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST['send'])){
    //Checks if the button named send has been clicked
    $msgtext = $_POST['msgtxt'];
    $myid = $_SESSION['id'];
    $receiverid = $_SESSION['msg'];
    //Save the written text, the current user's id, and the id of the receiving user
    $sql = "INSERT INTO message (text, receiver_user_id, sender_user_id) VALUES ('$msgtext', '$receiverid', '$myid');";
    $conn->query($sql);
    //Inserts the message into the table message
    $email = userEmail($receiverid, $conn);
    $recname = displayName($receiverid, $conn);
    $myname = displayName($myid, $conn);
    //Use the functions to get the receiver's email and displayname, and the current user's displayname
    $msg = "Hi $recname, \n\nYou have a new message on Balduvian Trading Post from $myname. They hope to hear from you soon!\n\nBest regards,\nThe Balduvian Trading Post Team";
    $msg = wordwrap($msg, 70);
    //Save a string in msg, make sure that it has appropriate number of characters per line
    mail($email, 'New message', $msg);
    //Send the string as an email to the receiver
  } else if(isset($_POST['home'])){
    header('location:main.php');
    //Checks if the button named home has been clicked, if it has, the user is sent to main.php
  } else if(isset($_POST['back'])){
    header('location:playgroup.php');
    //Checks if the button named back has been clicked, if it has, the user is sent to playgroup.php
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
        <input type='submit' name='home' value='BTP'>
      </form>
    </div>

    <div class="header_right">
      <a href="index.php">

        <p>Logout</p>
      </a>
    </div>
  </div>

  <div style="clear:both;"></div>
  
  <form method='POST' class="knap">
    <input type='submit' name='back' value='Back'/>
  </form>

  <div class="mainCon">
    <div class="container beskedBoks">
      <?php
      $myid = $_SESSION['id'];
      $friendid = $_SESSION['msg'];
      $friendname = displayName($friendid, $conn);
      //Gets the receiving user's displayname
      echo "<div class='container'><h1>Messages with $friendname</h1></div>";
      //Prints the displayname
      $sql = "SELECT * FROM message WHERE (receiver_user_id='$myid' AND sender_user_id='$friendid') OR (receiver_user_id='$friendid' AND sender_user_id='$myid');";
      $result = $conn->query($sql);
      //Selects all messages between these two users
      if($result->num_rows > 0){
        //If there are more than 0 messages
        echo "<div class='beskeder'>";
        while($row = $result->fetch_assoc()){
          //For each message do the following:
          $sender = $row['sender_user_id'];
          $receiver = $row['receiver_user_id'];
          $msg = $row['text'];
          $datetime = $row['time'];
          $meid = $_SESSION['id'];
          //Save the message's text, sender, receiver and time of sending. The current user's id is also saved
          if($meid == $sender){
            //If the current user's id equals the sender's id
            echo "<div class='container send'>$msg <br><p> $datetime </p></div>";
            echo "<div></div>";
            //Print the message with one type of styling
          } else{
            echo "<div class='container receive'>$msg <br><p> $datetime </p></div>";
            echo "<div></div>";
            //Otherwise, print the message with another type of styling
          }
        }
        echo "</div>";
      }
      ?>
      <div style="clear:both;"></div>
      <form method='POST' class='container'>
        <input type='text' name='msgtxt' placeholder='Write your message here...'/>
        <input type='submit' name='send' value='Send'/>
      </form>
    </div>
  </div>
</body>
</html>