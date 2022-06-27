<?php 
// starts a session
session_start();
?>

<!doctype html>
<html>
<head>
  <title>Registration and Login Page</title>
  <meta charset="UTF-8">
  <meta name="keywords" content="Register page, PHP session">
  <meta name="author" content="Emmanuel Onah">
  <!-- <meta http-equiv="refresh" content="100"> -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    .Register{
        display: block;
        text-align: center;
        width:50%;
        float:left;
        height:100%;
        border-right: 5px solid grey;
        box-sizing: border-box;
        margin: 0px;
        padding:0;
    }

    .Login{
        display: block;
        text-align: center;
        width:50%;
        float:right;
        height:100%;
        margin:0px;
        padding:0;
        box-sizing: border-box;
    }    
</style>

</head>
<body>

<?php
// define variables and set to empty values
$username = $email = $gender = "";
$usernameErr = $emailErr = $genderErr = $passwordErr = $cPasswordErr = 
$cPasswordErr2 = $successMsg = $noSuccessMsg = "";

if (isset($_REQUEST["submit"])){
    if (empty($_POST["username"])) {
        $usernameErr = "Name is required";
    } else {
        // store username in session superglobal
        $_SESSION["username"] = $_POST["username"];
        // removes whitespace, slashes, convert characters to HTML entities and store the value in $username
        $username = trim(stripslashes(htmlspecialchars($_SESSION["username"])));
        // check if username only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z-' ]*$/", $username)){
            $usernameErr = "Only letters and white space are allowed";
        }
    }

    if (empty($_POST["email"])){
       $emailErr = "Email is required";
    } else {
        // store email in session superglobals
        $_SESSION["email"] = $_POST["email"];
        // removes whitespace, slashes, convert characters to HTML entities and store the value in $email
        $email = trim(stripslashes(htmlspecialchars($_SESSION["email"])));
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["gender"])){
       $genderErr = "Gender is required.";
    }else{
        // store gender in session superglobal
        $_SESSION["gender"] = $_POST["gender"];
        // removes whitespace, slashes, convert characters to HTML entities and store the value in $gender
        $gender = trim(stripslashes(htmlspecialchars($_SESSION["gender"])));
    }

    if (empty($_POST["password"]) && $_POST["password"] < 4){
       $passwordErr = "Password is required and must be greater than 4 characters.";
    }else{
        // hash and store password in session superglobal
        $_SESSION["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);
    }

    if (empty($_POST["cPassword"])){
        $cPasswordErr = "Confirm password is required.";
    }
    
    // check if password and confirm password are equal
    if ($_POST["password"] !== $_POST["cPassword"]){
        $cPasswordErr2 = "Password and Confirm password must be equal.";
    }
    
    // check if all the conditions for registration are met simultaneously
    if ($_POST["password"] > 4 && $_POST["password"] === $_POST["cPassword"] && isset($_POST["username"]) &&
     isset($_POST["email"]) && isset($_POST["gender"]) && trim(stripslashes(htmlspecialchars($_SESSION["email"]))) && 
    trim(stripslashes(htmlspecialchars($_SESSION["username"]))) && trim(stripslashes(htmlspecialchars($_SESSION["gender"]))) &&
    filter_var($email, FILTER_VALIDATE_EMAIL)){
        $successMsg = "Registration successful";
    }else{
        $noSuccessMsg = "Registration unsuccessful";
    }
}
?>

<div class= "Register" style="display:block; text-align:center; font-family:Comic Sans MS;">
<h2>Enter your details to create or register your account or login if you already have an account</h2>

<span style="color:red;">All fields are required</span>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<p>Username: <input type="text" name="username" placeholder="Enter your username">
<span style="color:red;"><?php echo $usernameErr;?></span></p>
<p>Email: <input type="text" name="email" placeholder="Enter your email address">
<span style="color:red;"><?php echo $emailErr;?></span></p>
<p>Gender:
<input type="radio" name="gender" value="Male"> Male
<input type="radio" name="gender" value="Female"> Female
<input type="radio" name="gender" value="Other"> Other
<span style="color:red;"><?php echo $genderErr;?></span></p>
<p>Password: <input type="password" name="password" placeholder="Enter your password">
<span style="color:red;"><?php echo $passwordErr;?></span>
<br><span style="color:red;"> Note: Password must contain at least 5 characters.</span></p>
<p>Confirm Password: <input type="password" name="cPassword" placeholder="Confirm password">
<span style="color:red;"><?php echo $cPasswordErr;?></span>
<span style="color:red;"><?php echo $cPasswordErr2;?></p>
<p><input type="submit" name="submit" value="Sign Up"></p>
<p id="top">Already have an Account? <a href="#bottom">Login Here</a></p>
<br><span style="color:#4CAF50;font-size:50px;"><?php echo $successMsg;?></span>
<span style="color:#F44336; font-size:50px;"><?php echo $noSuccessMsg;?></span>
</form>
</div>

<?php
// define variables and set to empty values
$usernameErrMsg = $passwordErrMsg = $loginMsg = $loginMsg2 = "";

if (isset($_REQUEST["submit_form"])){
    if (empty($_POST["loginUsername"])){
        $usernameErrMsg = "Username required";
    }else{
        // store login username in session superglobal
        $_SESSION["loginUsername"] = $_POST["loginUsername"];
    }

    if  (empty($_POST["loginPassword"])){
        $passwordErrMsg = "Password required";
    }else{
        // store login password in session superglobal
        $_SESSION["loginPassword"] = $_POST["loginPassword"];
    }

    // check if the login username and registration username are the same
    // check if the login password and registration password are the same
    if (password_verify(@$_SESSION["loginPassword"], @$_SESSION["password"]) && @$_SESSION["loginUsername"] === @$_SESSION["username"]){
        $loginMsg = "Login Successful";
    }else{
        $loginMsg2 = "Login NOT successful"; 
    }
        
}  
?>

<div class= "Login" style="display:block; text-align:center; font-family:Comic Sans MS;">
<h2>Enter your details to login to your account</h2>
<span style="color:red;">All fields are required</span>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<p>Username: <input type="text" name="loginUsername" placeholder="Enter your Username">
<span style="color:red;"><?php echo $usernameErrMsg;?></span></p> 
<p>Password: <input type="password" name="loginPassword" placeholder="Enter your password"> 
<span style="color:red;"><?php echo $passwordErrMsg;?></p>
<p><input type="submit" name="submit_form" value="Sign In"></p>
<p id="bottom">Don't have an Account? <a href="#top">Register Here</a></p>
<br><br><span style="color:#4CAF50; font-size:50px;"><?php echo $loginMsg;?></span>
<span style="color:#F44336; font-size:50px;"><?php echo $loginMsg2;?></span>
</form>
</div>

</body>
</html>
