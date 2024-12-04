<?php
session_start();

if (isset($_SESSION['studentID'])) {
    unset($_SESSION['studentID']);
}

header("Location: login.php");
exit;