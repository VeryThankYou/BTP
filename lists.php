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

  } else if(isset($_POST['back'])){
    $user = $_SESSION['id'];
    $owner = $_SESSION['listowner'];
    if($user == $owner){
      header('location:main.php');
    } else{
      header('location:playgroup.php');
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
    <p>BTP</p> 

    <a href="index.php" class="logout">
        <p>Logout</p>
    </a>    

    <form method="POST">
      <input type='submit' name='back' value='Back'>
    </form>

  </div>

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

        <?php
          $id = $_SESSION['listowner'];
          $sql = "SELECT * FROM card INNER JOIN user_card ON card.id = user_card.card_id WHERE user_card.user_id = $id AND user_card.want > 0;";
          $result = $conn->query($sql);
          if($result->num_rows > 0){
        ?>
      </div>



          <table>
            
              
            <?php
              while($row = $result->fetch_assoc()) {
            ?>
            
              <?php
                $name = $row['name'];
                $num = $row['want'];
                echo "<tr>";
                echo "<th>$num</th><td>$name</td>";
                echo "</tr>";

              ?>
            <?php
            }
            ?>

          </table>

      <?php
        } else{
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
        $id = $_SESSION['id'];
        $sql = "SELECT * FROM card INNER JOIN user_card ON card.id = user_card.card_id WHERE user_card.user_id = $id AND user_card.trading > 0;";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
      ?>
            
          <table>
              
            <?php
              while($row = $result->fetch_assoc()) {
            ?>
                
              <?php
                $name = $row['name'];
                $num = $row['trading'];
                echo "<tr>";
                echo "<th>$num</th><td>$name</td>";
                echo "</tr>";
              ?>

            <?php
            }
            ?>
          </table>

      <?php
        } else{
          echo "You have no cards on your trading-list.";
        }
      ?>
    </div>

	</div>

</body>
</html>