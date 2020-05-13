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
    if(isset($_POST['changename'])){
      $name = $_POST['playname'];
      $playgroupid = $_SESSION['playgroup'];
      $sql = "UPDATE playgroup SET name='$name' WHERE id='$playgroupid';";
      $conn->query($sql);
    } else if(isset($_POST['adduser'])){
      $useremail = $_POST['mail'];
      $userid = userID($useremail, $conn);
      $playgroupid = $_SESSION['playgroup'];
      $sql = "INSERT INTO user_playgroup (user_id, playgroup_id) VALUES ('$userid', '$playgroupid');";
      $conn->query($sql);
    } else if(isset($_POST['back'])){
      header("location:main.php");
    } else if(isset($_POST['viewlists'])){
      $_SESSION['listowner'] = $_POST['userid'];
      header("location:lists.php");
    } else if(isset($_POST['msg'])){
      $_SESSION['msg'] = $_POST['userid'];
      header("location:message.php");
    } else if(isset($_POST['mutualwant'])){
      $_SESSION['mutuallist'] = "want";
      header("location:mutual.php");
    } else if(isset($_POST['mutualtrade'])){
      $_SESSION['mutuallist'] = "trade";
      header("location:mutual.php");
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

    <div class="container friends">
      <?php
        $playgroupid = $_SESSION['playgroup'];
        $sql = "SELECT * FROM playgroup WHERE id='$playgroupid';";
        $result = $conn->query($sql);
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        echo "<h1> $name </h1>";
        $id = $_SESSION['id'];
        $sql = "SELECT * FROM user INNER JOIN user_playgroup ON user.id=user_playgroup.user_id WHERE user_playgroup.playgroup_id='$playgroupid';";
        $result = $conn->query($sql);
        if($result->num_rows > 0){

          while($row = $result->fetch_assoc()) {
            $name = $row['displayname'];
            $uid = $row['id'];
            if($uid != $id){
              echo "<div class='container'><p>$name</p> <form method='POST' class='knap'> <input type='submit' name='viewlists' value='View lists'/> <input type='submit' name='msg' value='Message'/> <input type='hidden' name='userid' value='$uid'/> </form></div>";
            }
          }
        }
      ?>
    </div>

    <div class="other">
      <div class="container">
        <div class="container">
          <?php
            $playgroupid = $_SESSION['playgroup'];
            $sql = "SELECT * FROM playgroup WHERE id='$playgroupid';";
            $result = $conn->query($sql);
            $row = mysqli_fetch_assoc($result);
            $name = $row['name'];
            $creator = $row['user_id'];
            if($id == $creator){
              echo "<form method='POST'><table><tr><td><p>Change playgroup name:</p></td></tr><tr><td><input type='text' name='playname' placeholder='Write name here'/></td></tr><tr><td><input type='submit' name='changename' value='Change playgroup name'/></td></tr></table></form>";
            }
          ?>
        </div>
        <div class="container">
          <form method='POST'>
            <table>
              <tr>
                <td><p>Add a user:</p></td>
              </tr>
              <tr>
                <td><input type='email' name='mail' placeholder='example@btp.com'></td>
              </tr>
              <tr>
              <td><input type='submit' name='adduser' value='Add user'/></td>
              </tr>
            </table>
          </form>
        </div>
      </div>

      <div class="container">
        <form method='POST'>
          <input type='submit' name='mutualwant' value='Cards you want others have'/>
        </form>

        <form method='POST'>
          <input type='submit' name='mutualtrade' value='Cards you have others want'/>
        </form>
      </div>
    </div>
  </div>

</body>
</html>