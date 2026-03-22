<?php
session_start();
session_unset();      // FIX: clear all session variables first
session_destroy();    // then destroy the session

header("Location: ./login.php");
exit();