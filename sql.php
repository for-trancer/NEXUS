<?php
    $server = "localhost";
    $user = "root";
    $pass = "n0p@554ub27h";
    $db = "nexus";

    $conn = new mysqli($server,$user,$pass,$db);
    if($conn->connect_error)
    {
        echo "sql connection error!";
    }
?>