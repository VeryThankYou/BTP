<?php
session_start();
// To have access to the sessions variables
include('config.php');
// Contains our connection to our database
session_unset();
//Unsets all session variables
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Checks if a submit button that is inside the form, has been pushed.
  if(isset($_POST["login"])) {
    //Checks if it is the login button
    header('location:login.php');
    //If it is, send user to login.php
  } 
  else {
      //There are only 2 buttons so if it is not the first then it is the second.
      header('location:register.php');
      //Sends the user to register.php
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
            <p>BTP</p> 
        </div>
    </div>
    
    <div class="container input">
        <form method="POST">
            <input type="submit" name="login" value="Login">
            <p>OR</p>
            <input type="submit" name="register" value="Register">
        </form>

    </div>
</body>
</html>