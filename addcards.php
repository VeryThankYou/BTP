<?php
session_start();
include('config.php');
//This file has our connection to our database

if(!isset($_SESSION['id'])){
  header('location: index.php');
  //Check if we have a session variable called id. This way we block users from changing the url and trying to skip login.
}


if($_SERVER["REQUEST_METHOD"] == "POST")  {
    if(isset($_POST['home'])){
      header("location:main.php");
      //Checks if the button named home has been clicked, if it has, user is sent to main.php
    } else if(isset($_POST['back'])){
      header("location:lists.php");
      //Checks if the button named back has been clicked, if it has, user is sent to lists.php
    } else if(isset($_POST['expset'])){
      //Checks if the button named expset has been clicked
      $expset = $_POST['name'];
      $sql = "SELECT * FROM card WHERE expset='$expset';";
      $result = $conn->query($sql);
      //Saves the chosen expansion set, and finds all cards of that set. This table is saved in $result
    } else if(isset($_POST['sea'])){
      //Checks if the button named sea has been clicked
      $term = $_POST['search'];
      $sql = "SELECT * FROM card WHERE name LIKE '%$term%';";
      $result = $conn->query($sql);
      //Saves the search term, and selects all cards where name contains the search term. This table is saved in $result
    } else if(isset($_POST['add'])){
      //Checks if the button named add has been clicked
      $userid = $_SESSION['id'];
      $cardid = $_POST['addid'];
      $numcards = $_POST['num'];
      $list = $_SESSION['listtoadd'];
      $sql = "SELECT * FROM user_card WHERE user_id='$userid' AND card_id='$cardid';";
      $result = $conn->query($sql);
      //Saves inputs, finds all rows in user_card with the current user's id and the chosen card's id
      if($result->num_rows < 1){
        //If less than one row is found
        if($list=="wants"){
          $sql = "INSERT INTO user_card (card_id, user_id, want) VALUES ('$cardid', '$userid', '$numcards');";
          $conn->query($sql);
          //Inserts a new row in user_card, with the current user's id, the chosen card's id, and the chosen number in the column corresponding to the current list
        } else if($list=="trades"){
          $sql = "INSERT INTO user_card (card_id, user_id, trading) VALUES ('$cardid', '$userid', '$numcards');";
          $conn->query($sql);
          //Inserts a new row in user_card, with the current user's id, the chosen card's id, and the chosen number in the column corresponding to the current list
        }
        unset($result);
        //Unset the variable $result
      } else{
        //If a row was found
        if($list=="wants"){
          $sql = "UPDATE user_card SET want='$numcards' WHERE user_id='$userid' AND card_id='$cardid'";
          $conn->query($sql);
          //The row is updated, so the current list's value is equal to the chosen number
        } else if($list=="trades"){
          $sql = "UPDATE user_card SET trading='$numcards' WHERE user_id='$userid' AND card_id='$cardid';";
          $conn->query($sql);
          //The row is updated, so the current list's value is equal to the chosen number
        }
        unset($result);
        //Unset the variable $result
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
          //If $result is set, and it contains more than 0 rows
      ?>
      <div>
        <p>Results:</p>
        <?php
          while($row = $result->fetch_assoc()) {
            //For each row
        ?>
            <div>  
            <?php
              $name = $row['name'];
              $expset = $row['expset'];
              $id = $row['id'];
              echo "<div class='container'><h1>$name</h1> <h2>$expset</h2><form method='POST'> <input type='number' name='num'/> <input type='submit' name='add' value='Add card' /><input type='hidden' value='$id' name='addid'/></form></div>";
              //Print the name and expansion set, and a form to add the card to a list
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
        //Selects all distinct expansion sets from card
        while($row = $result2->fetch_assoc()) {
          echo "<div class='container'><form method='POST'><input type='submit' name='expset' value='" . $row['expset'] . "'/> <input type='hidden' name='name' value='" . $row['expset'] . "'/> </form></div>";
          //Print each expansion set, with a submit to choose all cards from that set
        }
      ?>
    </div>
  </div>

</body>
</html>