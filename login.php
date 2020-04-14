<?php
//Here a session is started, so any login-details can be saved
session_start();
//Connect to our database
include('config.php');

//Variables for checking login are created
$email="";
$pw="";
$cpw="";
$cemail="";

//If the mail-input and the pw-input are send, the following happens:
if(!empty( $_POST['email'] ) && !empty( $_POST['pw'] )) {
  //The email and password from the input are saved in the variables $email and $pw
  $email = $_POST['email'];
  $pw = $_POST['pw'];
  
  //Here we check our database for a user with the email given as input
  $sql = "SELECT password FROM user WHERE email='$email';";
  $result = $conn->query($sql);
	$fetch = $result;
  $row = mysqli_fetch_assoc($fetch);
  
  //Here the hashed password of the user from the database, is saved in $cpw, and cmail is set to the mail given as input
	$cpw = $row['password'];
  $cemail = $email;
}
  //Check if the mail is correct, and if the written password matches the hashed password from the database
  if($email == $cemail && password_verify($pw, $cpw) == true) {
    //The mail is saved as a session-variable
    $_SESSION['email'] = $email;
    //Redirects to main.php
    header('location:main.php');
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

  <div class="header">
    <h1>LogPlan</h1>
  </div> 



  <div class="inputBox_regIn">
    <h1>Login</h1>  
    <form method="POST">
      <table class="table_regIn">

        <tr>
          <th>Email: <br/></th>
          <th><input type="text" name="email" placeholder="Enter email" required/></th>
        </tr>

        <tr>
          <th>Password: <br/></th>
          <th><input type="password" name="pw" placeholder="Password" required  /></th>
        </tr>

        <tr>
          <th colspan="2"><input type="submit" value="Login" /> </th>
        </tr>

      </table>
    </form>
  </div>

  <div>
    <button class="switch_regIn" type="button" onclick="window.location.href='register.php'" name="btnCancel">Register</button>  
  </div>

</body>
</html>