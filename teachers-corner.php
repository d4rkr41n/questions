<?php

session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


//For auto page refresh
//$page = $_SERVER['PHP_SELF'];
//$sec = "10";

?>


<html style="background:#FFFFFF;">
<head>
    <title>Teacher's Corner</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        setInterval(function(){
            $('#reloadme').load(document.URL +  ' #reloadme');
        }, 5000);
    </script>
</head>
<body style="background:#FFFFFF;">

<div class="topmenu">
    <h2>Teacher's Corner</h2>
    <form action="clearquestions.php" style="display: inline;" method="POST">
        <button class="submit wactionbutton"><span>Clear Questions </span></button>
    </form>

    <form action="<?php echo $page ?>" style="display: inline;" method="POST">
        <button class="submit wactionbutton"><span>Refresh </span></button>
    </form>

    <form action="logout.php" style="display: inline;" method="POST">
        <button class="submit wactionbutton"><span>Logout </span></button>
    </form>
</div>


<div id="reloadme">
<?php

//Get list of questions
require_once "connections.php";
$sql = "SELECT id,ip,text,time FROM questions WHERE answered <> 1";
$res = $conn->query($sql);


if ($res->num_rows > 0) {
// output data of each row
    while($row = $res->fetch_assoc()) {
        echo "<div class='question'><h3>" . $row["ip"] . " @ " . explode(' ',$row["time"])[1] . "</h3>";
        echo "<form class='xbutton' action='clearquestions.php' method='POST'><button value='" . $row["id"] . "' name='questionid'><span>X</span></button></form>";
        echo "<hr size='1'><br><p>" . $row["text"]. "</p><br></div><br>";
    }
} else {
    echo "<h3 style='text-align:center;margin:10px auto;'>No questions at this time</h3>";
}

//close the database connection
mysqli_close($conn);

?>
</div>
</body>
</html>
