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

if ($_SERVER["REQUEST_METHOD"] == "POST")  {
  //Checks if we have pushed the button named conversation
  if(isset($_POST['newGroup'])){
    $user = $_SESSION['id'];
    $name = $_POST['groupName'];

    $sql = "INSERT INTO playgroup (name, user_id) VALUES ('$name', '$user');";
    $conn ->query($sql);

    $sql = "SELECT MAX(id) FROM playgroup WHERE user_id=$user;";
    $result = $conn->query($sql);
    $fetch = $result;
    $row = mysqli_fetch_assoc($fetch);
    $playgroup = $row['MAX(id)'];

    $sql = "INSERT INTO user_playgroup (user_id, playgroup_id) VALUES ('$user', '$playgroup');";
    $conn ->query($sql);

   
    }else if(isset($_POST['open'])){

      $hentid = $_POST['openid'];
      $_SESSION['playgroup'] = $hentid;
      header('location:playgroup.php');

    }else if(isset($_POST['dlt'])){
      $commid = $_POST['dltid'];
      $sql = "DELETE FROM playgroup WHERE id=$commid;";
      $conn->query($sql);
    }else if(isset($_POST['list'])){
        $_SESSION['listowner'] = $_SESSION['id'];
        header('location:lists.php');
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
    
    <p class="title">BTP</p> 

    <a href="index.php" class="logout">
        <p>Logout</p>
    </a>    

  </div>


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

  if($result->num_rows > 0){
  ?>


    <?php
    // løb alle rækker igennem
    while($row = $result->fetch_assoc()) {
    ?>  
      <div class="container stuff">

        <?php
          $name = $row['name'];
          $id = $row['id'];
          $sql = "SELECT user_id FROM playgroup WHERE id=$id;";
          $result2 = $conn->query($sql);
          $row2 = mysqli_fetch_assoc($result2);
          $creator = $row2['user_id'];
          echo "<p>$name</p> <div><form method='POST'> <input type='submit' name='open' value='Open' /><input type='hidden' value='$id' name='openid'/></form></div>";
          if($creator == $userid){
            echo "<div><form method='POST'><input type='submit' name='dlt' value='Delete'><input type='hidden' name='dltid' value='$id'></form></div>";
  
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