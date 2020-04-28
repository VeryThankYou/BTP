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

	<div>

			<?php
        $id = $_SESSION['id'];
        $sql = "SELECT card.name FROM card INNER JOIN user_card ON card.id = user_card.card_id WHERE user_card.user_id = $id AND user_card.want > 0;";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
          ?>
        
          <table class="listTable">
            <p>Wants</p>
          <?php
          // løb alle rækker igennem
          while($row = $result->fetch_assoc()) {
          ?>
              
              <?php
                $name = $row['name'];
                $num = $row['want'];
                echo "<tr>";
                echo "<th>$num</th><th>$name</th>";
                echo "</tr>";

              ?>
          <?php
          }
          ?>
          </table>
          <?php
          } else{
            echo "You have no cards on your wants-list.";
          }


          $id = $_SESSION['id'];
          $sql = "SELECT card.name FROM card INNER JOIN user_card ON card.id = user_card.card_id WHERE user_card.user_id = $id AND user_card.trading > 0;";
          $result = $conn->query($sql);
          if($result->num_rows > 0){
            ?>
          
            <table class="listTable">
              <p>Tradinglist</p>
            <?php
            // løb alle rækker igennem
            while($row = $result->fetch_assoc()) {
            ?>
                
                <?php
                  $name = $row['name'];
                  $num = $row['trading'];
                  echo "<tr>";
                  echo "<th>$num</th><th>$name</th>";
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

</body>
</html>