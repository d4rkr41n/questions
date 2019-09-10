<?php
session_start();

// Check if the user is already logged in, if yes then redirect them to teacher page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: teachers-corner.php");
    exit;
}

require_once "connections.php";

// check the form data given
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check for empty fields
    if(empty(trim($_POST["formusername"]))){
        $formusername_err = "Please enter username.";
    } else{
        $formusername = trim($_POST["formusername"]);
    }

    if(empty(trim($_POST["formpassword"]))){
        $formpassword_err = "Please enter your password.";
    } else{
        $formpassword = trim($_POST["formpassword"]);
    }

    // Make sure creds are right
    if(empty($formusername_err) && empty($formpassword_err)){
        // Prepare a select statement
        $sql = "SELECT username, password FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the statement
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $formusername;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store the result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $formusername, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($formpassword, $hashed_password)){
                            // Password was correct
                            session_start();

                            // Store session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["username"] = $formusername;

                            // Redirect to teacher page
                            header("location: teachers-corner.php");
                        } else{
                            // Display invalid login (password fail)
                            $form_err = "<span style='padding:3px;background:red;color:white;font-weight:bold;width:auto;'>Invalid Login.</span><br>";
                        }
                    }
                } else{
                    // Display invalid login (username fail)
                    $form_err = "<span style='padding:3px;background:red;color:white;font-weight:bold;width:auto;'>Invalid Login.</span><br>";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);

}


?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Login</title>
</head>
<body>

   <div id="nav">
     <ul>
        <li><a href="https://pingforagoodtime.com">Home <span></span></a></li>
        <li><a href="https://pingforagoodtime.com/questions/index.php">Ask <span></span></a></li>
     </ul>
   </div>

<form class="animate accessform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="admin-login">
    <h1>Teacher's Corner</h1>
    <?php

    echo "$form_err";
    ?>


    <input id="username" type="text" name="formusername" value="" placeholder="Enter your username" required><br>
    <input id="password" type="password" name="formpassword" placeholder="Enter your password" required><br>
    <button class="submit" type="submit"><span>Login </span></button>
</form>

</body>
</html>
