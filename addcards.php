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

if($_SERVER["REQUEST_METHOD"] == "POST")  {
    if(isset($_POST['home'])){
        header("location:main.php");
    } else if(isset($_POST['back'])){
        header("location:lists.php");
    } else if(isset($_POST['expset'])){
        $expset = $_POST['name'];
        $sql = "SELECT * FROM card WHERE expset='$expset';";
        $result = $conn->query($sql);
    } else if(isset($_POST['sea'])){
      $term = $_POST['search'];
      $sql = "SELECT * FROM card WHERE name LIKE '%$term%';";
      $result = $conn->query($sql);
    } else if(isset($_POST['add'])){
      $userid = $_SESSION['id'];
      $cardid = $_POST['addid'];
      $numcards = $_POST['num'];
      $list = $_SESSION['listtoadd'];
      $sql = "SELECT * FROM user_card WHERE user_id='$userid' AND card_id='$cardid';";
      $result = $conn->query($sql);
      if($result->num_rows < 1){
        if($list=="wants"){
          $sql = "INSERT INTO user_card (card_id, user_id, want) VALUES ('$cardid', '$userid', '$numcards');";
          $conn->query($sql);
        } else if($list=="trades"){
          $sql = "INSERT INTO user_card (card_id, user_id, trading) VALUES ('$cardid', '$userid', '$numcards');";
          $conn->query($sql);
        }
      } else{
        if($list=="wants"){
          $sql = "UPDATE user_card SET want='$numcards;";
          $conn->query($sql);
        } else if($list=="trades"){
          $sql = "UPDATE user_card SET trading='$numcards;";
          $conn->query($sql);
        }
      }
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
  <form method="POST" class="knap">
    <input type='submit' name='back' value='Back'/>
  </form>

  <div class="mainCon">

    <div class="container soeg">
      <div>
        <form method="POST">
          <input type="text" name="search" placeholder="Card name" />
          <input type="submit" name="sea" value="Search" />
        </form>
      </div>

      <?php
        if(isset($result) and $result->num_rows > 0){
      ?>
      <div>
        <p>Results:</p>
        <?php
          while($row = $result->fetch_assoc()) {
        ?>
            <div>  
            <?php
              $name = $row['name'];
              $expset = $row['expset'];
              $id = $row['id'];
              echo "<div class='container'><h1>$name</h1> <h2>$expset</h2><form method='POST'> <input type='number' name='num'/> <input type='submit' name='add' value='Add card' /><input type='hidden' value='$id' name='addid'/></form></div>";
            ?>

            </div>
        <?php
          }
        ?>
      </div> 
      <?php
        }
      ?>
    </div>
  
    <div class="container saet">
      <?php
        $sql = "SELECT DISTINCT expset FROM card ORDER BY expset ASC;";
        $result2 = $conn->query($sql);
        while($row = $result2->fetch_assoc()) {
          echo "<div class='container'><form method='POST'><input type='submit' name='expset' value='" . $row['expset'] . "'/> <input type='hidden' name='name' value='" . $row['expset'] . "'/> </form></div>";
        }
      ?>
    </div>
  </div>

</body>
</html>