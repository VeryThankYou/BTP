<?php
session_start();
include('config.php');
//This file has our connection to our database


if(!isset($_SESSION['id'])){
  header('location: index.php');
  //Check if we have a session variable called id. This way we block users from changing the url and trying to skip login.
}


if ($_SERVER["REQUEST_METHOD"] == "POST")  {
  if(isset($_POST['newGroup'])){
    //Checks if the button named newGroup has been clicked
    $user = $_SESSION['id'];
    $name = $_POST['groupName'];
    //The current user's id and the given groupname is saved

    $sql = "INSERT INTO playgroup (name, user_id) VALUES ('$name', '$user');";
    $conn ->query($sql);
    //A new playroup is inserted into the database

    $sql = "SELECT MAX(id) FROM playgroup WHERE user_id=$user;";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    $playgroup = $row['MAX(id)'];
    //The new playgroup's id is found with a SQL statement

    $sql = "INSERT INTO user_playgroup (user_id, playgroup_id) VALUES ('$user', '$playgroup');";
    $conn ->query($sql);
    //It, and the user's id are used to insert a new row in the table user_playgroup

   
    }else if(isset($_POST['open'])){
      //If the input named "open" was used:
      $hentid = $_POST['openid'];
      $_SESSION['playgroup'] = $hentid;
      header('location:playgroup.php');
      //The chosen playgroup's id is saved as a sessionvariable, and the user is sent to playgroup.php

    }else if(isset($_POST['dlt'])){
      //If the input named "dlt" was used:
      $commid = $_POST['dltid'];
      $sql = "DELETE FROM playgroup WHERE id=$commid;";
      $conn->query($sql);
      //The chosen playgroup's id is used to find the playgroup in the database,  and it is deleted
    }else if(isset($_POST['list'])){
      //If the input named "list" was used:
      $_SESSION['listowner'] = $_SESSION['id'];
      header('location:lists.php');
      //The user's id is saved as the sessionvariable 'listowner', and the user is sent to lists.php
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

  <div class="mainCon">
    <div class="container mcGroup">
      <h1>Playgroups</h1>
      
      <form method="POST">
        <input type="text" name="groupName">
        <input type="submit" name="newGroup" value="Create Playgroup">

      </form>
  <?php
  $userid = $_SESSION['id'];
  $sql = "SELECT * FROM playgroup INNER JOIN user_playgroup ON playgroup.id = user_playgroup.playgroup_id WHERE user_playgroup.user_id = $userid;";
  $result = $conn->query($sql);
  //All playgroups the user is part of are found

  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()) {
      //If there are more than 0 playgroups found, for each row in the selected table:
    ?>  
      <div class="container stuff">

        <?php
          $name = $row['name'];
          $id = $row['id'];
          //The 'name' and 'id' column of the row are saved
          $sql = "SELECT user_id FROM playgroup WHERE id=$id;";
          $result2 = $conn->query($sql);
          $row2 = mysqli_fetch_assoc($result2);
          $creator = $row2['user_id'];
          //Get the creator of this playgroup
          echo "<p>$name</p> <div><form method='POST'> <input type='submit' name='open' value='Open' /><input type='hidden' value='$id' name='openid'/></form></div>";
          //Print the playgroup's name, and a submit to open it
          if($creator == $userid){
            echo "<div><form method='POST'><input type='submit' name='dlt' value='Delete'><input type='hidden' name='dltid' value='$id'></form></div>";
            //if the creator of the playgroup is the current user, make a submit to delete the playgroup
  
    }
    ?>

      </div>
   
   <?php
    }
    }
    ?>
    
    </div>

    <div class="container mcList">
      <h1>Lists</h1>
      
      <form method="POST">
        <input type="submit" name="list" value="View my lists">
      </form>
      
    </div>
  </div>

<body>
</html>