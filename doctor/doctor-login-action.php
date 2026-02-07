<?php
session_start();

$demo = include "./doc-credential.php";

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if($email == $demo['email'] && $password == $demo['password']){

$_SESSION['doctor_id'] = 1;
$_SESSION['doctor_name'] = $demo['name'];

header("Location: doctor.php");
exit();

}

header("Location: login.php?error=1");
exit();
