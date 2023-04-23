

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
      <div class="title">Login Form</div>
      <form method="POST">
        <div class="field">
          <input type="text" name="email" required>
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
        <div class="signup-link">Not a member? <a href="register.php">Signup now</a></div>
      </form>
    </div>
  </body>
</html>



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
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Query the database for the user's email and password
    $sql = "SELECT * FROM register WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    // If a matching record is found, log the user in
    if (mysqli_num_rows($result) == 1) {
        // Start a session and store the user's email
        session_start();
        $_SESSION["email"] = $email;

        // Redirect the user to the home page
        header("Location: main.php");
        exit();
    } else {
        // If no matching record is found, display an error message
        $error = "Invalid email or password";
    }
}
?>