<?php
    $servername = "localhost";
    $username = "<usernamehere>";
    $password = "<passwordhere>";
    $database = "questions";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
        echo "Failed to connect to db";
    }
?>
