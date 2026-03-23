<?php
// Step 1: Create connection
$con = mysqli_connect("localhost", "root", "");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Step 2: Select database
if (!mysqli_select_db($con, "mediqueue_db")) {
    die("Database selection failed: " . mysqli_error($con));
}
