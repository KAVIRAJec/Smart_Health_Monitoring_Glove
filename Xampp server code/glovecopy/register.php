<?php
// Database credentials
$host = "localhost";
$user = "root";
$password = "";
$database = "glove";

// Attempt to connect to the database
$conn = mysqli_connect($host, $user, $password, $database);


// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// If the login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["uname"];
    $password = $_POST["password"];
    
    // Insert the user's email and password into the users table
    $sql = "INSERT INTO register (username , email, password) VALUES ('$email' ,'$email', '$password')";
    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>". mysqli_error($conn);
    }
}

?>
<!DOCTYPE html>
<!-- Created By CodingLab - www.codinglabweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
   <!----<title>Login Form Design | CodeLab</title>---->
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div class="wrapper">
      <div class="title">Sign UP</div>
      <form method="POST">
        <div class="field">
          <input type="text" name="uname" required>
          <label>Email Address</label>
        </div>
        <div class="field">
          <input type="password" name="password" required>
          <label>Password</label>
        </div>
        <div class="content">
          <div class="checkbox">
            <input type="checkbox" id="remember-me">
            <label for="remember-me">Remember me</label>
          </div>
          <div class="pass-link"><a href="#">Forgot password?</a></div>
        </div>
        <div class="field">
          <input type="submit" value="Login">
        </div>
        <div class="signup-link">Already a member? <a href="login.php">log in</a></div>
      </form>
    </div>
  </body>
</html>
