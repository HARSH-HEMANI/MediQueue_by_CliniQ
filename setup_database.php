<?php
// step:1 - connect to the database
$con = mysqli_connect("localhost", "root", "", "mediqueue_db");
// 4th parameter (database name) inserted after creating database so, we don't need to select database separately(means there is no need to repeat step 3 everytime while running queries))
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


?>
    