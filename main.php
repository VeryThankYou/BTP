<?php
session_start();
//This file has our connection to our database
include('config.php');

//Check if we have a session called email. This way we block users from changing the url and trying to skip login.
if(!isset($_SESSION['email'])){
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
  if(isset($_POST['newProject'])){
    $user = userID($_SESSION['email'], $conn);
    $name = $_POST['projectName'];

    $sql = "INSERT INTO project (name, user_id) VALUES ('$name', '$user');";
    $conn ->query($sql);

    $sql = "SELECT MAX(id) FROM project WHERE user_id=$user;";
    $result = $conn->query($sql);
    $fetch = $result;
    $row = mysqli_fetch_assoc($fetch);
    $project = $row['MAX(id)'];

    $sql = "INSERT INTO user_project (user_id, project_id) VALUES ('$user', '$project');";
    $conn ->query($sql);

   
    }else if(isset($_POST['open'])){

      $hentid = $_POST['openid'];
      $_SESSION['project'] = $hentid;
      header('location:project.php');

    }else if(isset($_POST['dlt'])){
      $commid = $_POST['dltid'];
      $sql = "DELETE FROM project WHERE id=$commid;";
      $conn->query($sql);
    }
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="css/main.css">
  <title>Document</title>
</head>

<body>

  <div class="header">

    <h1>LogPlan</h1>

    <form method="POST" class="header_Form">
  
      <input type="text" name="projectName">
      <input type="submit" name="newProject" value="Create Project">

    </form>

    <a href="index.php">
      <div class="logout">
        <p>Logout</p>
      </div>
    </a>    

  </div>

  <?php
  $userid = userID($_SESSION['email'], $conn);
  $sql = "SELECT * FROM project INNER JOIN user_project ON project.id = user_project.project_id WHERE user_project.user_id = $userid;";
  $result = $conn->query($sql);

  if($result->num_rows > 0){
  ?>

  <div class="projectTable">
    <p>Projects</p>
  <?php
  // løb alle rækker igennem
  while($row = $result->fetch_assoc()) {
  ?>
    <div>
      <div class="project">
      
      <?php
        $name = $row['name'];
        $id = $row['id'];
        $sql = "SELECT user_id FROM project WHERE id=$id;";
        $result2 = $conn->query($sql);
        $row2 = mysqli_fetch_assoc($result2);
        $creator = $row2['user_id'];
        echo "<h1>$name</h1> <form method='POST'> <input type='submit' name='open' value='Open' /><input type='hidden' value='$id' name='openid'/></form></div>";
        if($creator == $userid){
          echo "<div class='deletThis'><form method='POST'><input type='submit' name='dlt' value='Delete'><input type='hidden' name='dltid' value='$id'></form></div>";
      ?>
    </div>
  <?php
  }
  }
  ?>
  
  </div>

  <?php
  }
  ?>

<body>
</html>