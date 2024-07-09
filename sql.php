<?php
    $server = "localhost";
    $user = "root"; // use your own username
    $pass = ""; // replace with your password
    $db = ""; // replace with your db name

    $conn = new mysqli($server,$user,$pass,$db);
    if($conn->connect_error)
    {
        echo "sql connection error!";
    }
?>
