<?php
session_start();
include('config.php');
//This file has our connection to our database

if(!isset($_SESSION['id'])){
  header('location: index.php');  
  //Check if we have a session variable called id. This way we block users from changing the url and trying to skip login.
}

if($_SERVER["REQUEST_METHOD"] == "POST")  {
  if(isset($_POST['addwants'])){
    //Checks if the button named addwants has been clicked
    $_SESSION['listtoadd'] = "wants";
    header('location:addcards.php');
    //If it is, the session variable 'listtoadd' is set to "wants", and the user is sent to addcards.php

  } else if(isset($_POST['addtrades'])){
    //Checks if the button named addtrades has been clicked
    $_SESSION['listtoadd'] = "trades";
    header('location:addcards.php');    
    //If it is, the session variable 'listtoadd' is set to "trades", and the user is sent to addcards.php
  } else if(isset($_POST['home'])){
    header('location:main.php');
    //Checks if the button named home has been clicked, if it is, the user is sent to main.php
  } else if(isset($_POST['back'])){
    //Checks if the button named back has been clicked
    $user = $_SESSION['id'];
    $owner = $_SESSION['listowner'];
    //The user's id and the listowner's id are saved
    if($user == $owner){
      header('location:main.php');
      //If these are equal, the user is sent to main.php
    } else{
      header('location:playgroup.php');
      //Otherwise, the user is sent to playgroup.php
    }
  } else if(isset($_POST['dlttr'])){
    //Checks if the button named dlttr has been clicked
    $cid = $_POST['dltid'];
    $uid = $_SESSION['id'];
    //The chosen card's id and the user's id are saved
    $sql = "UPDATE user_card SET trading='0' WHERE user_id='$uid' AND card_id='$cid';";
    $conn->query($sql);
    //The row in user_card with these id's are found, and trading is set to 0
  } else if(isset($_POST['dltwa'])){
    //Checks if the button named dltwa has been clicked
    $cid = $_POST['dltid'];
    $uid = $_SESSION['id'];
    //The chosen card's id and the user's id are saved
    $sql = "UPDATE user_card SET want='0' WHERE user_id='$uid' AND card_id='$cid';";
    $conn->query($sql);
    //The row in user_card with these id's are found, and want is set to 0
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

	<div class="mainList">
    <div class="container list">

      <div class="listHeader">
        <h1>Wants</h1>

        <?php
          $user = $_SESSION['id'];
          $owner = $_SESSION['listowner'];
          if($user == $owner){
            echo "<form method='POST'> <input type='submit' name='addwants' value='Add cards'/> </form>";
            //If the current user is the owner of the list, make a submit to add cards to wantslist
          }
        ?>
      </div>
        <?php
          $id = $_SESSION['listowner'];
          $sql = "SELECT * FROM card INNER JOIN user_card ON card.id = user_card.card_id WHERE user_card.user_id = $id AND user_card.want > 0;";
          $result = $conn->query($sql);
          //Find all cards the listowner has on his/her wantslist
          if($result->num_rows > 0){
            //If more than 0 cards are found
        ?>
          <div class="listFlow">
            <table>
                
              <?php
                while($row = $result->fetch_assoc()) {
                  //For each of the found cards
                  $name = $row['name'];
                  $num = $row['want'];
                  echo "<tr>";
                  echo "<th>$num</th><td>$name</td>";
                  //Print the amount and name

                  $user = $_SESSION['id'];
                  $owner = $_SESSION['listowner'];
                  $cid = $row['card_id'];
                  if($user == $owner){
                    echo "<td><form method='POST'> <input type='submit' name='dltwa' value='Delete'> <input type='hidden' name='dltid' value='$cid'> </form></td>";
                    //If the list is owned by the current user, print a delete input
                  }
                ?>
                </tr>
              
              <?php
                } 
              ?>
            </table>
          </div>
        <?php
      }else{
          echo "You have no cards on your wants-list.\n";
          //If no cards were found, print that no cards were found
        }
      ?>
    </div>

    <div class="container list">
      <div class="listHeader">
        <h1>Offers</h1>
      
        <?php
          $user = $_SESSION['id'];
          $owner = $_SESSION['listowner'];
          if($user == $owner){
            echo "<form method='POST'> <input type='submit' name='addtrades' value='Add cards'/> </form>";
            //If the current user is the owner of the list, make a submit to add cards to tradeslist
          }

        ?>
      </div>
      
      <?php
        $id = $_SESSION['listowner'];
        $sql = "SELECT * FROM card INNER JOIN user_card ON card.id = user_card.card_id WHERE user_card.user_id = $id AND user_card.trading > 0;";
        $result = $conn->query($sql);
        //Find all cards the listowner has on his/her tradeslist
        if($result->num_rows > 0){
          //If more than 0 cards are found
      ?>
          <div class="listFlow">    
            <table>

              <?php
                while($row = $result->fetch_assoc()) {
                  //For each of the found cards

                  $name = $row['name'];
                  $num = $row['trading'];
                  echo "<tr>";
                  echo "<th>$num</th><td>$name</td>";
                  //Print the amount and name

                  $user = $_SESSION['id'];
                  $owner = $_SESSION['listowner'];
                  $cid = $row['card_id'];
                  if($user == $owner){
                    echo "<td><form method='POST'> <input type='submit' name='dlttr' value='Delete'> <input type='hidden' name='dltid' value='$cid'> </form></td>";
                    //If the list is owned by the current user, print a delete input
                  }
                }
                echo "</tr>";
              ?>

            </table>
          </div>
      <?php
        } else{
          echo "You have no cards on your trading-list.";
          //If no cards were found, print that no cards were found
        }
      ?>
    </div>

	</div>

</body>
</html>