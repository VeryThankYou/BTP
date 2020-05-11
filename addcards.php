<script>
function dropdown() {
  var x = document.getElementById("myDropdown");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else{
    x.style.display = "none";
  }
}

</script>
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
    if(isset($_POST['back'])){
        header("location:lists.php");
    } else if(isset($_POST['expset'])){
        $_SESSION['expset'] = $_POST['name'];
        header("location:cardsbyexp.php");
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
  <title>Document</title>
</head>
<body>
<div class="dropdown">
  <button class="dropbtn">Dropdown</button>
  <div class="dropdown-content">
    <?php
    $sql = "SELECT DISTINCT expset FROM card ORDER BY expset ASC;";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        echo "<form method='POST'> <input type='submit' name='expset' value='" . $row['expset'] . "'/> <input type='hidden' name='name' value='" . $row['expset'] . "'/> </form>";
    }
    ?>
  </div>
</div>
<form method="POST">
<input type='submit' name='back' value='Back'/>
</form>
</body>
</html>