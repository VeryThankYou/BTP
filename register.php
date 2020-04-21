<script>
function openAuth() {
  var x = document.getElementById("authDiv");
  if (x.style.display === "none") {
    x.style.display = "block";
  }
}
</script>
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

        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $displayname = mysqli_real_escape_string($conn, $_POST['dispname']);

        $rannum = strval(mt_rand(0, 9999));

        $_SESSION['rannum'] = $rannum;

        $msg = "Hi $displayname,\n\nHere is your authentication code: $rannum. Thank you for using our platform.\n\nBest regards,\nThe Balduvian Trading Post Team";
        $msg = wordwrap($msg, 70);

        mail($email, 'Authentication Code', $msg);


      }
    } else if(isset($_POST['check'])){
      if($_POST['auth'] == $_SESSION['rannum']) {
        //Otherwise, the info from the inputs are saved, the password is hashed, and everything gets saved as a new user in our database, in the table 'user'

        $email = mysqli_real_escape_string($conn, $_POST['hiddenEmail']);

        $password = mysqli_real_escape_string($conn, password_hash($_POST['hiddenPassword'], PASSWORD_DEFAULT));

        $displayname = mysqli_real_escape_string($conn, $_POST['hiddenDispname']);

        $sql = "INSERT INTO user (email, password, displayname) VALUES ('$email', '$password', '$displayname')";

        $conn ->query($sql);
        
        //At last the user gets redirected to login.php
        header("location:login.php");

      } else{
        echo "Wrong athentication key. If you need a new, fill out the first form again";
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
        <p class="title">BTP</p> 
    </div>  
    
    <div class="container input">
    <form method="POST">
      
      <h1>Register</h1>

        <table>
    
          <tr>
            <th>Email: <br></th>
            <th><input type="email" name="email" placeholder="Enter email" <?php if(isset($email)){echo 'value='.$email;}?> required></th>
          </tr>
      
          <tr>
            <th>Display Name: <br></th>
            <th><input type="text" name="dispname" placeholder="Display Name" <?php if(isset($displayname)){echo 'value='.$displayname;}?> required></th>
          </tr>
      
          <tr>
            <th>Password: <br></th>
            <th><input type="password" name="password" placeholder="Password" <?php if(isset($password)){echo 'value='.$password;}?> required></th>
          </tr>

          <tr>
            <th colspan="2"><input type="submit" value="Create" name="createUser" /> </th>
          </tr>
      
      </table>
    </form>
    </div>

    <div class="container auth" id="authDiv" style="display:none;">
      <form method="POST">

          <p>Authentication key:<br></p>
          <input type="text" name="auth" placeholder="0000" required>

          <input type="submit" name="check" value="Authenticate User">

          <input type="hidden" name="hiddenPassword" <?php if(isset($password)){echo 'value='.$password;}?> >
          <input type="hidden" name="hiddenDispname" <?php if(isset($displayname)){echo 'value='.$displayname;}?> >
          <input type="hidden" name="hiddenEmail" <?php if(isset($email)){echo 'value='.$email;}?> > 
      
    </div>
  

  <div class="container navi">
    <button class="switch_regIn" type="button" onclick="window.location.href='login.php'" name="btnCancel">Login</button>  
  </div>
</body>

</html>

<?php
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
    }else {
      echo "<script> openAuth(); </script>";
    }
  }
}

        