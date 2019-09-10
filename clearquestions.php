<?php

session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


// When form is submitted, check to see if question id is set
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if questionid is empty
    if(!empty(trim($_POST["questionid"]))){
        require_once "connections.php";

        $filteredidq = preg_replace('/[^0-9]/', '', filter_var($_POST['questionid'],FILTER_SANITIZE_NUMBER_INT));

        if( is_numeric($filteredidq) ){
            $sql = "UPDATE questions SET answered=1 WHERE id=$filteredidq";
            $res = $conn->query($sql);
            header("location: teachers-corner.php");
            exit;
        }

        //Give up if bad input
        echo "<script>alert('fail');</script>";
        header("location: teachers-corner.php");
        exit;
    }
}


//Clean all entries
require_once "connections.php";
$sql = "UPDATE questions SET answered=1 WHERE answered=0";
$res = $conn->query($sql);

header("location: teachers-corner.php");
exit;


?>
