<script>
function openAuth() {
  //Here we define a Javascript function
  var x = document.getElementById("authDiv");
  //The element with the id "authDiv" is saved in a variable
  if (x.style.display === "none") {
    x.style.display = "block";
    //If the element is not shown, show it
  }
}
</script>
<?php
  session_start();
  include('config.php');
  //We start a session here, which can be used to save login-details. We also connect to our database with config.php

  if ($_SERVER["REQUEST_METHOD"] == "POST"){  
    if(isset($_POST['createUser'])){
      //If an input is send in a form with the method POST, if the input's name is 'createUser', the following happens:
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      //The database is checked for users with the same email as what was given as an input

      $sql = "SELECT email FROM user WHERE email = '$email'";
      $result = $conn->query($sql);

      $row = mysqli_fetch_assoc($result);

      $count = mysqli_num_rows($result);

      if($count == 1) {
        echo "<span class='errorText'>Unable to create user. The email is already being used</span>";
        //If there is such a user, a failure-message is send to the webpage
      }else {

        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $displayname = mysqli_real_escape_string($conn, $_POST['dispname']);
        
        $rannum = strval(mt_rand(0, 9999));
        $_SESSION['rannum'] = $rannum;
        //If not, the given name and password are saved, and we generate a random 4-digit number, saved in a sessionvariable


        $msg = "Hi $displayname,\n\nHere is your authentication code: $rannum. Thank you for using our platform.\n\nBest regards,\nThe Balduvian Trading Post Team";
        $msg = wordwrap($msg, 70);
        //A mail is written with the 4-digit code in

        mail($email, 'Authentication Code', $msg);
        //Here the mail is sent to the given mail-address


      }
    } else if(isset($_POST['check'])){
      //If the the input goven has the name "check", the following happens:
      if($_POST['auth'] == $_SESSION['rannum']) {
        //If the random number is the same as the given authentication code,

        $email = mysqli_real_escape_string($conn, $_POST['hiddenEmail']);

        $password = mysqli_real_escape_string($conn, password_hash($_POST['hiddenPassword'], PASSWORD_DEFAULT));

        $displayname = mysqli_real_escape_string($conn, $_POST['hiddenDispname']);

        $sql = "INSERT INTO user (email, password, displayname) VALUES ('$email', '$password', '$displayname')";

        $conn ->query($sql);
        //The info from the inputs are saved, the password is hashed, and everything gets saved as a new user in our database, in the table 'user'

        header("location:login.php");
        //At last the user gets redirected to login.php


      } else{
        echo "Wrong athentication key. If you need a new, fill out the first form again";
        //If a wrong key is given, print an error-message
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
        <p>BTP</p> 
      </div>
    </div>
    
    <div class="container input">
    <form method="POST">
      
      <h1>Register</h1>

        <table>
    
          <tr>
            <th>Email: <br></th>
          </tr>
      
          <tr>
            <th><input type="email" name="email" placeholder="Enter email" <?php if(isset($email)){echo 'value='.$email;}
            //If an email is given from the first input, this input is saved as that again. This also happens for displayname and password inputs
            ?> required></th>
          </tr>


          <tr>
            <th>Display Name: <br></th>
          </tr>

          <tr>  
            <th><input type="text" name="dispname" placeholder="Display Name" <?php if(isset($displayname)){echo 'value='.$displayname;}?> required></th>
          </tr>
      

          <tr>
            <th>Password: <br></th>
          </tr>

          <tr>
            <th><input type="password" name="password" placeholder="Password" <?php if(isset($password)){echo 'value='.$password;}?> required></th>
          </tr>


          <tr>
            <th><input type="submit" value="Create" name="createUser" /> </th>
          </tr>
      
      </table>
    </form>
    </div>


    <div class="container auth" id="authDiv" style="display:none;">
      <form method="POST">


          <p>Authentication key:<br></p>
          <input type="text" name="auth" placeholder="0000" required>

          <input type="submit" name="check" value="Authenticate">

          <input type="hidden" name="hiddenPassword" <?php if(isset($password)){echo 'value='.$password;
          //As earlier, if these values were given by an earlier input, they are given as these inputs' values
          }?> >
          <input type="hidden" name="hiddenDispname" <?php if(isset($displayname)){echo 'value='.$displayname;}?> >
          <input type="hidden" name="hiddenEmail" <?php if(isset($email)){echo 'value='.$email;}?> > 
      </form>
    </div>
  

  <div class="container navi">
    <button type="button" onclick="window.location.href='login.php'" name="btnCancel">Go to Login</button>  
  </div>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST['createUser'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "SELECT email FROM user WHERE email = '$email'";
    $result = $conn->query($sql);

    $row = mysqli_fetch_assoc($result);

    $count = mysqli_num_rows($result);
    //Here it is once again checked if the given email are in use

    if($count == 1) {
      //If it is, nothing happens
    }else {
      echo "<script> openAuth(); </script>";
      //If not, the Javascript-function runs, to show the authentication input field
    }
  }
}

        