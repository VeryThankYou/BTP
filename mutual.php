<?php
session_start();
include('config.php');
//This file has our connection to our database

if(!isset($_SESSION['id'])){
  header('location: index.php');
  //Check if we have a session variable called id. This way we block users from changing the url and trying to skip login.
}

if(!isset($_SESSION['mutuallist'])){
  header('location: main.php');  
  //Check if we have a session variable called mutuallist. This way we block users from changing the url, and this page doesn't work without that session variable.
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['back'])){
      header("location:playgroup.php");
      //Checks if the button named back has been clicked, if it has, the user is sent to playgroup.php
    } else if(isset($_POST['home'])){
      header("location:main.php");
      //Checks if the button named home has been clicked, if it has, the user is sent to main.php
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
    <input type='submit' name='back' value='Back'>
  </form>

  <div class="mainCon">
    <div class="container combiList">
      <?php
      $list = $_SESSION['mutuallist'];
      //Saves the session variable for mutuallist
      if($list == "want"){
          echo "<h2>Cards that you want that your playgroup has:</h2>";
          //If this equals want, describe the cards being shown
      } else if($list == "trade"){
          echo "<h2>Cards that you have that your playgroup wants:</h2>";
          //If this equals trade, describe the cards being shown
      }
      $playgroupid = $_SESSION['playgroup'];
      $myid = $_SESSION['id'];
      //Save the current user's id, and the current playgroup's id
      
      if($list == "want"){
        $sql = "SELECT * FROM otherslist INNER JOIN mylist ON mylist.smcid=otherslist.stcid WHERE stpgid='$playgroupid' AND stuid<>'$myid' AND sttrading>0 AND smwant>0 AND smuid='$myid';";
        $result = $conn->query($sql);
        //Select all cards the current user wants, that other users of the playgroup are trading
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
              //For each card in the resulting table
              $exp = $row['stexp'];
              $cname = $row['stcname'];
              $uname = $row['stdname'];
              $num = $row['sttrading'];
              echo "<div class='container combiCard'>";
              echo "<div class='container'><h1>$num - $cname</h1></div>";
              echo "<div class='container'><p>Set: $exp</p><p>Owner: $uname</p></div>";
              echo "</div>";
              //Print the card's name, how many is being offered, its expansion set, and who is offering them
            }
        }
      } else if($list == "trade"){
        $sql = "SELECT * FROM otherslist INNER JOIN mylist ON mylist.smcid=otherslist.stcid WHERE stpgid='$playgroupid' AND stuid<>'$myid' AND stwant>0 AND smtrading>0 AND smuid='$myid';";
        $result = $conn->query($sql);
        //Select all cards the current user is trading, that other users of the playgroup are want
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
              //For each card in the resulting table
                $exp = $row['stexp'];
                $cname = $row['stcname'];
                $uname = $row['stdname'];
                $num = $row['stwant'];
                echo "<div class='container combiCard'>";
                echo "<div class='container'><h1>$num - $cname</h1></div>";
                echo "<div class='container'><p>Set: $exp</p><p>Owner: $uname</p></div>";
                echo "</div>";
              //Print the card's name, how many are wanted, its expansion set, and who wants them
            }
        }
      }

      ?>
    </div>
  </div>

</body>
</html>