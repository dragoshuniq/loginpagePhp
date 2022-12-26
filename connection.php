<?php

$hostname = "localhost";
$username = "root";
$password = "";

$dbname = "myapp";

$conn = mysqli_connect($hostname, $username, $password, $dbname);

if (!$conn) {
    echo "Connection failed!";
}


function console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}