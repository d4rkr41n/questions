<?php
include("connections.php");

$text = filter_var($_POST["question"],FILTER_SANITIZE_STRING);
$ip = $_SERVER["REMOTE_ADDR"];
if(isset($text) && $text != ""){
    //Validate text length
    if(strlen($text)>255){
        $lenerror = "<p style='color:red;font-weight:bold;'>Your question was too long!</p><br>";
    }else{
        $lenerror = "";
        //Insert question
        $sql = "INSERT INTO questions (ip, text) VALUES ('$ip', '$text')";
        $res = $conn->query($sql);

        if($res != TRUE){
            echo "Error: " . $conn->error;
        }

        header("location: index.php");
        die();
    }
}

?>

<html>
<head>
    <title>Anonymous Questions</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
   <div id="nav">
     <ul>
        <li><a href="https://pingforagoodtime.com">Home <span></span></a></li>
        <li><a href="https://pingforagoodtime.com/questions/login.php">Login <span></span></a></li>
     </ul>
   </div>

<form class="accessform" action="" method="post">
    <h1>Questions?</h1>
    <?php
        if($lenerror != ""){echo $lenerror;}
    ?>
    <input id="question" type="text" name="question" placeholder="Ask a question!"><br>
    <button class="submit" type="submit"><span>Ask </span></button>
</form>

</body>
</html>
