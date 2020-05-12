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
<?php
$playgroupid = $_SESSION['playgroup'];
$sql = "SELECT * FROM playgroup WHERE id='$playgroupid';";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);
$name = $row['name'];
$creator = $row['user_id'];
echo "<h1> $name </h1>";
$id = $_SESSION['id'];
if($id == $creator){
  echo "<form method='POST'> <input type='text' name='playname' placeholder='Write name here'/> <input type='submit' name='changename' value='Change playgroup name'/> </form>";
}
$sql = "SELECT * FROM user INNER JOIN user_playgroup ON user.id=user_playgroup.user_id WHERE user_playgroup.playgroup_id='$playgroupid';";
$result = $conn->query($sql);
if($result->num_rows > 0){

    // løb alle rækker igennem
    while($row = $result->fetch_assoc()) {
    ?>  
      <div class="container stuff">

        <?php
          $name = $row['displayname'];
          $uid = $row['id'];
          if($uid != $id){
            echo "<p>$name</p> <form method='POST'> <input type='submit' name='viewlists' value='View lists'/> <input type='submit' name='msg' value='Message'/> <input type='hidden' name='userid' value='$uid'/> </form>";
          }
          
    }
  }
?>

<form method='POST'>
<input type='email' name='mail' placeholder='example@btp.com'>
<input type='submit' name='adduser' value='Add user'/>
</form>

<form method='POST'>
<input type='submit' name='back' value='Back'/>
</form>

</body>
</html>