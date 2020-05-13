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
  if(isset($_POST['addwants'])){
    $_SESSION['listtoadd'] = "wants";
    header('location:addcards.php');

  } else if(isset($_POST['addtrades'])){
    $_SESSION['listtoadd'] = "trades";
    header('location:addcards.php');
  } else if(isset($_POST['home'])){
    header('location:main.php');
  } else if(isset($_POST['back'])){
    $user = $_SESSION['id'];
    $owner = $_SESSION['listowner'];
    if($user == $owner){
      header('location:main.php');
    } else{
      header('location:playgroup.php');
    }

  } else if(isset($_POST['dlttr'])){
    $cid = $_POST['dltid'];
    $uid = $_SESSION['id'];
    $sql = "UPDATE user_card SET trading='0' WHERE user_id='$uid' AND card_id='$cid';";
    $conn->query($sql);
  } else if(isset($_POST['dltwa'])){
    $cid = $_POST['dltid'];
    $uid = $_SESSION['id'];
    $sql = "UPDATE user_card SET want='0' WHERE user_id='$uid' AND card_id='$cid';";
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

      <div class="skrt">
        <h1>Wants</h1>

        <?php
          $user = $_SESSION['id'];
          $owner = $_SESSION['listowner'];
          if($user == $owner){
            echo "<form method='POST'> <input type='submit' name='addwants' value='Add cards'/> </form>";
          }
        ?>
      </div>
        <?php
          $id = $_SESSION['listowner'];
          $sql = "SELECT * FROM card INNER JOIN user_card ON card.id = user_card.card_id WHERE user_card.user_id = $id AND user_card.want > 0;";
          $result = $conn->query($sql);
          if($result->num_rows > 0){
        ?>
          <div class="listFlow">
            <table>
                
              <?php
                while($row = $result->fetch_assoc()) {
              ?>
              
                <?php
                  $name = $row['name'];
                  $num = $row['want'];
                  echo "<tr>";
                  echo "<th>$num</th><td>$name</td>";
                ?>

                <?php
                  $user = $_SESSION['id'];
                  $owner = $_SESSION['listowner'];
                  $cid = $row['card_id'];
                  if($user == $owner){
                    echo "<td><form method='POST'> <input type='submit' name='dltwa' value='Delete'> <input type='hidden' name='dltid' value='$cid'> </form></td>";
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
        }
      ?>
    </div>

    <div class="container list">
      <div class="skrt">
        <h1>Offers</h1>
      
        <?php
          $user = $_SESSION['id'];
          $owner = $_SESSION['listowner'];
          if($user == $owner){
            echo "<form method='POST'> <input type='submit' name='addtrades' value='Add cards'/> </form>";
          }

        ?>
      </div>
      
      <?php
        $id = $_SESSION['listowner'];
        $sql = "SELECT * FROM card INNER JOIN user_card ON card.id = user_card.card_id WHERE user_card.user_id = $id AND user_card.trading > 0;";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
      ?>
          <div class="listFlow">    
            <table>

              <?php
                while($row = $result->fetch_assoc()) {
              ?>

              <?php
                $name = $row['name'];
                $num = $row['trading'];
                echo "<tr>";
                echo "<th>$num</th><td>$name</td>";
              ?>
              <?php
                $user = $_SESSION['id'];
                $owner = $_SESSION['listowner'];
                $cid = $row['card_id'];
                if($user == $owner){
                  echo "<td><form method='POST'> <input type='submit' name='dlttr' value='Delete'> <input type='hidden' name='dltid' value='$cid'> </form></td>";
                }
              }
                echo "</tr>";
              ?>

            </table>
          </div>
      <?php
        } else{
          echo "You have no cards on your trading-list.";
        }
      ?>
    </div>

	</div>

</body>
</html>