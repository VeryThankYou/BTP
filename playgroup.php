<?php
session_start();
include('config.php');
//This file has our connection to our database

if(!isset($_SESSION['id'])){
  header('location: index.php');
  //Check if we have a session variable called id. This way we block users from changing the url and trying to skip login.
}

function userID($email, $conn){
  //Defines the function with two parameters
  $sql = "SELECT id FROM user WHERE email='$email';";
  $result = $conn->query($sql);
  //Uses an SQL-statement to get the id of the user with email equal to the given parameter
  $row = mysqli_fetch_assoc($result);
  return $row['id'];
  //Returns the id
}

if ($_SERVER["REQUEST_METHOD"] == "POST")  {
    if(isset($_POST['changename'])){
      //Checks if the button named changename has been clicked
      $name = $_POST['playname'];
      $playgroupid = $_SESSION['playgroup'];
      $sql = "UPDATE playgroup SET name='$name' WHERE id='$playgroupid';";
      $conn->query($sql);
      //Save the new name and the current playgroups id, update the playgroups name in the database
    } else if(isset($_POST['adduser'])){
      //Checks if the button named adduser has been clicked
      $useremail = $_POST['mail'];
      $userid = userID($useremail, $conn);
      //Uses the function to get the id of the user with the given email
      $playgroupid = $_SESSION['playgroup'];
      $sql = "INSERT INTO user_playgroup (user_id, playgroup_id) VALUES ('$userid', '$playgroupid');";
      $conn->query($sql);
      //Insert the user and the playgroup's id into a new row in the table user_playgroup
    } else if(isset($_POST['back'])){
      header("location:main.php");
      //Checks if the button named back has been clicked, if it has, the user is sent to main.php
    } else if(isset($_POST['home'])){
      header("location:main.php");
      //Checks if the button named home has been clicked, if it has, the user is sent to main.php
    } else if(isset($_POST['viewlists'])){
      //Checks if the button named viewlists has been clicked
      $_SESSION['listowner'] = $_POST['userid'];
      header("location:lists.php");
      //Sets the session variable for listowner to the chosen user's id, the current user is sent to lists.php
    } else if(isset($_POST['msg'])){
      //Checks if the button named msg has been clicked
      $_SESSION['msg'] = $_POST['userid'];
      header("location:message.php");
      //Sets the session variable for msg to the chosen user's id, the current user is sent to message.php
    } else if(isset($_POST['mutualwant'])){
      //Checks if the button named mustualwant has been clicked
      $_SESSION['mutuallist'] = "want";
      header("location:mutual.php");
      //Sets the session variable for mutuallist to "want", the current user is sent to mutual.php
    } else if(isset($_POST['mutualtrade'])){
      //Checks if the button named mustualtrade has been clicked
      $_SESSION['mutuallist'] = "trade";
      header("location:mutual.php");
      //Sets the session variable for mutuallist to "trade", the current user is sent to mutual.php
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
        echo "<h1>$name</h1>";
        //Selects the current playgroup's name, prints it
        $id = $_SESSION['id'];
        $sql = "SELECT * FROM user INNER JOIN user_playgroup ON user.id=user_playgroup.user_id WHERE user_playgroup.playgroup_id='$playgroupid';";
        $result = $conn->query($sql);
        //Selects all users in the playgroup
        if($result->num_rows > 0){
          //If this returns a table with more than 0 rows
          while($row = $result->fetch_assoc()) {
            $name = $row['displayname'];
            $uid = $row['id'];
            //For each row, get the displayname and id of the player in this row
            if($uid != $id){
              echo "<div class='container'><p>$name</p> <form method='POST' class='knap'> <input type='submit' name='viewlists' value='View lists'/> <input type='submit' name='msg' value='Message'/> <input type='hidden' name='userid' value='$uid'/> </form></div>";
              //If the current user's id isn't equal to this row's id, print the row's displayname, and two submits to either see this player's lists, or to message them
            }
          }
        }
      ?>
    </div>

    <div class="other">
      <div class="container">

          <?php
            $playgroupid = $_SESSION['playgroup'];
            $sql = "SELECT * FROM playgroup WHERE id='$playgroupid';";
            $result = $conn->query($sql);
            $row = mysqli_fetch_assoc($result);
            $name = $row['name'];
            $creator = $row['user_id'];
            //Get the current playgroup's creator's id
            if($id == $creator){
              echo "<div class='container'><form method='POST'><table><tr><td><p>Change playgroup name:</p></td></tr><tr><td><input type='text' name='playname' placeholder='Write name here'/></td></tr><tr><td><input type='submit' name='changename' value='Change playgroup name'/></td></tr></table></form></div>";
              //If the current user's id equals the creator's id, print a form to change the playgroup's name
            }
          ?>
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