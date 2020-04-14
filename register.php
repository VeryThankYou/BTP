<?php
//We start a session here, which can be used to save login-details. We also connect to our database with config.php
  session_start();
  include('config.php');

  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    //If an input is send in a form with the method POST, if the input's name is 'btnCreateUser', the following happens:
    if(isset($_POST['createUser'])){
      //The database is checked for users with the same email as what was given as an input
      $email = mysqli_real_escape_string($conn, $_POST['email']);

      $sql = "SELECT email FROM user WHERE email = '$email'";
      $result = $conn->query($sql);

      $row = mysqli_fetch_assoc($result);

      $count = mysqli_num_rows($result);

      if($count == 1) {
        //If there is such a user, a failure-message is send to the webpage
        echo "<span class='errorText'>Unable to create user. The email is already being used</span>";
      }else {
        //Otherwise, the info from the inputs are saved, the password is hashed, and everything gets saved as a new user in our database, in the table 'user'
        $password = mysqli_real_escape_string($conn, password_hash($_POST['password'], PASSWORD_DEFAULT));

        $displayname = mysqli_real_escape_string($conn, $_POST['dispname']);

        $sql = "INSERT INTO user (email, password, displayname) VALUES ('$email', '$password', '$displayname')";

        $conn ->query($sql);
        
        //At last the user gets redirected to login.php
        header("location:login.php");
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
    <title>Document</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

  <div class="header">
    <h1>LogPlan</h1>
  </div>

  <div class="inputBox_regIn">
    <form method="POST">
      <h1>Register</h1>

      <table class="table_regIn">
  
        <tr>
          <th>Email: <br></th>
          <th><input type="email" name="email" placeholder="Enter email" required></th>
        </tr>
    
        <tr>
          <th>Display Name: <br></th>
          <th><input type="text" name="dispname" placeholder="Display Name" required></th>
        </tr>
    
        <tr>
          <th>Password: <br></th>
          <th><input type="password" name="password" placeholder="Password" required></th>
        </tr>

        <tr>
          <th colspan="2"><input type="submit" value="Create" name="createUser" /> </th>
        </tr>
    
      </table>
    </form>
  </div>

  <div>
    <button class="switch_regIn" type="button" onclick="window.location.href='login.php'" name="btnCancel">Login</button>  
  </div>
  
</body>

</html>

